<?php
include(__DIR__ . '/common.php');

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
            return $value->nom == $player->to_nom && $value->prenom == $player->to_prenom;
        }));

        l('Mail '.$player->nom);

        $sujet = 'Happitime - Résultat tirage au sort Happy Christmas Tree 2023';
        $message = 'Bonjour<b>' .$player->prenom.'</b><br/>, le père Noël a fait le tirage au sort.<br/> 
        ' .$cible->prenom.' '.$cible->nom.' sera l’heureux(se) bénéficiaire de ton cadeau cette année ! <br/>';
        if($cible->liste) {
            $message .= 'Voici sa liste au père Noël:<br/>'.$cible->liste;
        }
        mailing($player->email, $sujet, $message);
    }
} else {
    echo 'Erreur lors de la requête : ' . error_get_last()['message'];
}
?>

