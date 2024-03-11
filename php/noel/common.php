<?php

// Remplacez ces valeurs par les vôtres depuis le tableau de bord Supabase
$supabaseUrl = getenv('SUPABASE_URL');
$supabaseApiKey = getenv('SUPABASE_KEY');

// Créez une URL pour la requête
$tableName = 'noel';

date_default_timezone_set('UTC');

function l($message)
{
    echo $message . PHP_EOL;
}

function mailing($destinataire, $sujet, $message)
{
    $expediteur = "happitime@additi.fr"; // Adresse e-mail de l'expéditeur

    // En-têtes de l'e-mail
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: $expediteur\r\n";
    $headers .= "Reply-To: $expediteur\r\n";

    $messageHtml = file_get_contents(__DIR__ . '/template.html');

    $messageHtml = str_replace('!!TITRE!!', $sujet, $messageHtml);
    $messageHtml = str_replace('!!MESSAGE!!', $message, $messageHtml);

    $smtpConfig = [
        'host' => '127.0.0.1', // Adresse du serveur SMTP
        'port' => 587, // Port SMTP (587 est généralement utilisé pour le chiffrement TLS)
        'secure' => 'tls', // Vous pouvez utiliser 'ssl' au lieu de 'tls' si votre serveur SMTP le requiert
    ];

    l('mail envoyé à ' . $destinataire . ' sujet : ' . $sujet . ' message : ' . $message);

    // Envoi de l'e-mail
    //if (mail($destinataire, $sujet, $messageHtml, $headers)) {
    if (mail('kevin.leclere@additi.fr', $sujet, $messageHtml, $headers)) {
        l("L'e-mail a été envoyé avec succès.");
    } else {
        l("L'envoi de l'e-mail a échoué.");
    }
}

function majPlayer($player)
{

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
        l('Erreur cURL : ' . curl_error($ch));
    } else {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 200 || $httpCode === 204) {
            l('Ligne mise à jour avec succès.');
        } else {
            l('Erreur lors de la mise à jour de la ligne. Code HTTP : ' . $httpCode);
        }
    }

    // Fermez la session cURL
    curl_close($ch);
}