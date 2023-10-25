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

    $allDispo = array_filter($all, function ($value) {
        return empty($value->killed_by);
    });
    
    foreach ($allDispo as $key => $player) {

        $cible = current(array_filter($all, function($value) use($player) {
            return $value->nom === $player->cible;
        }));

        l('Mail '.$player->nom);

        $sujet = 'Plus que 24h pour réussir cette mission ! ';
        $message = '<b>'.$cible->nom.'</b> est toujours votre cible.<br/><br/>Voici la mission pour l\'éliminer : <br/><b>'.$cible->mission.'</b>
        <div class="content">
          Si vous ne réussissez pas à tuer votre cible dans les 24H, votre mission va évoluer.<br/>
          Vous recevrez un nouveau mail demain matin vous indiquant la nouvelle mission à réaliser.
        </div>';
    }
} else {
    echo 'Erreur lors de la requête : ' . error_get_last()['message'];
}
?>

