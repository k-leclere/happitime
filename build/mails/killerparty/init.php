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
    if (mail('kevin.leclere@additi.fr'/*$destinataire*/, 'KillerParty 🎃 - ' . $sujet, $messageHtml, $headers)) {
        echo "L'e-mail a été envoyé avec succès.";
    } else {
        echo "L'envoi de l'e-mail a échoué.";
    }
}


$selectQuery = '*';
$allRequestUrl = $supabaseUrl . '/rest/v1/' . $tableName . 
    '?select=' . urlencode($selectQuery);

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
$allResponse = file_get_contents($allRequestUrl, false, $context);


// Vérifiez si la requête a réussi
if ($allResponse !== false) {
    $all = json_decode($allResponse);
    
    foreach ($all as $key => $player) {

        $cible = current(array_filter($all, function($value) use($player) {
            return $value->nom === $player->cible;
        }));

        l('Mail '.$player->nom. ' - Cible : '. $cible->nom);

        $sujet = 'Venez découvrir votre cible ! ';
        $message = '<b>'.$cible->nom.'</b> est votre cible.<br/><br/>Voici la mission pour l\'éliminer : <br/><b>'.$cible->mission.'</b>
        <div class="content">
        <ul><h2>Lorsque vous avez piégé :</h2></ul>
         <li>Dire discrètement à votre victime que vous l\'avez piégée. </li>
         <li>Vous envoyez un mail à Happitime pour prévenir de votre succès (happitime@additi.fr)</li>
         <li>Vous recevrez un nouveau mail pour vous prévenir de votre nouvelle cible et mission.</li>
         <ul><h2>Lorsque vous êtes victime :</h2></ul>
         <li>Malheureusement cela signifie que vous ne faites plus partie de la chasse.</li>
         <li>Ne pas communiquer le nom de votre piégeur car la chasse continue pour lui.</li>
         <li>Votre nom sera affiché dans le tableau des victimes le lendemain matin.</li>
         </ul>
        </div>
        <div class="content">
            Le principe du <b>contre-kill</b> sera appliquée lors de cette chasse.<br/>
            Si vous suspectez un collègue de vous piéger, vous devez lui dire formellement "je te suspecte de me piéger en me faisant faire telle mission".<br/>
            Si c\'est exact, cela se retourne contre le piégeur qui est éliminé. Sinon, le jeu continue.<br/>
            Attention il faut rester honnête et de bonne foi.<br/>
            Ce n\'est qu\'un jeu 🙂
        </div>
        ';
        mailing($player->email, $sujet, $message);
    }
} else {
    echo 'Erreur lors de la requête : ' . error_get_last()['message'];
}
?>

