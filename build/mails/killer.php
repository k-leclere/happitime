<?php

// Remplacez ces valeurs par les vôtres depuis le tableau de bord Supabase
$supabaseUrl = getenv('SUPABASE_URL');
$supabaseApiKey = getenv('SUPABASE_KEY');

// Créez une URL pour la requête
$tableName = 'killerparty';

date_default_timezone_set('UTC');

function l($message) {
    echo $message.PHP_EOL;
}

function mailing($destinataire, $sujet, $message) {
    $expediteur = "happitime@additi.fr"; // Adresse e-mail de l'expéditeur

    // En-têtes de l'e-mail
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: $expediteur\r\n";
    $headers .= "Reply-To: $expediteur\r\n";

    $messageHtml = file_get_contents(__DIR__.'/template.html');

    $messageHtml = str_replace('!!TITRE!!', $sujet, $messageHtml);
    $messageHtml = str_replace('!!MESSAGE!!', $message, $messageHtml);

    $smtpConfig = [
        'host' => '127.0.0.1', // Adresse du serveur SMTP
        'port' => 587, // Port SMTP (587 est généralement utilisé pour le chiffrement TLS)
        'secure' => 'tls', // Vous pouvez utiliser 'ssl' au lieu de 'tls' si votre serveur SMTP le requiert
    ];

    l('mail envoyé à '.$destinataire. ' sujet : '.$sujet. ' message : '.$message);

    // Envoi de l'e-mail
    if (mail('kevin.leclere@additi.fr'/*$destinataire*/, $sujet, $messageHtml, $headers)) {
        echo "L'e-mail a été envoyé avec succès.";
    } else {
        echo "L'envoi de l'e-mail a échoué.";
    }
}

function majPlayer($player) {

    global $supabaseUrl, $supabaseApiKey, $tableName;
    // Créez l'URL de l'API pour la mise à jour
    $updateUrl = "{$supabaseUrl}/rest/v1/{$tableName}?id=eq.{$player->id}";

    // Convertissez les données en JSON
    $dataJson = json_encode($player);

    // Configuration de la requête cURL
    $ch = curl_init($updateUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        "apikey: $supabaseApiKey"
    ));

    // Exécutez la requête cURL
    $response = curl_exec($ch);

    // Gérez la réponse
    if ($response === false) {
        echo 'Erreur cURL : ' . curl_error($ch);
    } else {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 200) {
            echo 'Ligne mise à jour avec succès.';
        } else {
            echo 'Erreur lors de la mise à jour de la ligne. Code HTTP : ' . $httpCode;
        }
    }

    // Fermez la session cURL
    curl_close($ch);
}

$selectQuery = '*';
$where = ['gt' => ['killed_at', date('Ymd H:i:s', time() - 60*60)]];
$killedRequestUrl = $allRequestUrl = $supabaseUrl . '/rest/v1/' . $tableName . 
    '?select=' . urlencode($selectQuery);


foreach($where as $key=>$value) {
    $killedRequestUrl .= '&'.$value[0].'='.$key.'.'.urlencode($value[1]);
}

// Créez des options pour la requête
$options = [
    'http' => [
        'header' => 'Content-Type: application/json' . "\r\n" . 'apikey: ' . $supabaseApiKey,
        'method' => 'GET',
    ],
];

// Créez le context pour la requête
$context = stream_context_create($options);

// Effectuez la requête HTTP GET
$killedResponse = file_get_contents($killedRequestUrl, false, $context);
$allResponse = file_get_contents($allRequestUrl, false, $context);


// Vérifiez si la requête a réussi
if ($killedResponse !== false) {
    $kills = json_decode($killedResponse);
    $all = json_decode($allResponse);
    
    $allDispo = array_filter($all, function($value) {
        return empty($value->killed_by);
    });

    $allCibles = array_unique(array_reduce($allDispo, function($acc, $value) {
        $acc[] = $value->cible;
        return $acc;
    }));
    
    $ciblesDispo = [];
    
    foreach ($allDispo as $key => $value) {
        // Vérifie si la clé 'cible' existe dans le deuxième tableau
        if (!in_array($value->nom, $allCibles)) {
            $ciblesDispo[] = $value;
        }
    }
    
    foreach($kills as $kill) {

        l('Nouveau tué: '.$kill->nom);

        $newCibleFor = array_filter($all, function($value) use($kill, $ciblesDispo) {
            return $value->cible === $kill->nom;
        });

        $killer = array_filter($all, function($value) use($kill) {
            return $value->nom === $kill->killed_by;
        });
        
        if($killer) {
            $killer = current($killer);
            l('killer -> '.$killer->nom);
        }
        else {
            l('ERREUR KILLER NON TROUVE');
        }

        foreach($newCibleFor as $cibleToChange) {

            if(!empty($ciblesDispo)) {
                $cible = array_shift($ciblesDispo);
            }
            else {
                $cible = $allDispo[array_rand($allDispo)];
            }

            l('Nouvelle Cible : '. $cible->nom .' pour '.$cibleToChange->nom);

            $sujet = $cibleToChange->nom==$killer->nom && $killer->cible==$kill->nom ? 'Bravo, vous avez tué votre cible !' : 'Votre cible a été tuée !';
            $message = '<b>'.$cible->nom.'</b> est désormais votre nouvelle cible.<br/>Voici pour votre mission pour l\'éliminer : <br/><b>'.$cible->mission.'</b>';
            mailing($cibleToChange->email, $sujet, $message);

            $cibleToChange->cible = $cible->nom;
            majPlayer($cibleToChange);
        }

        if($killer->cible!=$kill->nom) {
            $sujet = 'Bravo vous avez découvert votre tueur !';
            $message = 'Votre cible reste la même : <b>'.$killer->cible.'</b>.<br/>Faites attention un nouveau tueur est à vos trousses !';
            mailing($killer->email, $sujet, $message);
        }
    }
} else {
    echo 'Erreur lors de la requête : ' . error_get_last()['message'];
}
?>

