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

