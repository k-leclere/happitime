<?php
include(__DIR__ . '/common.php');

$selectQuery = '*';
$where = ['gt' => ['killed_at', date('Ymd H:i:s', time() - 60 * 60)]];
$killedRequestUrl = $allRequestUrl = $supabaseUrl . '/rest/v1/' . $tableName .
    '?select=' . urlencode($selectQuery);


foreach ($where as $key => $value) {
    $killedRequestUrl .= '&' . $value[0] . '=' . $key . '.' . urlencode($value[1]);
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

    $allDispo = array_filter($all, function ($value) {
        return empty($value->killed_by);
    });

    $allCibles = array_unique(array_reduce($allDispo, function ($acc, $value) {
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

    foreach ($kills as $kill) {

        l('Nouveau tué: ' . $kill->nom);

        $newCibleFor = array_filter($allDispo, function ($value) use ($kill, $ciblesDispo) {
            return $value->cible === $kill->nom;
        });

        $killer = array_filter($all, function ($value) use ($kill) {
            return $value->nom === $kill->killed_by;
        });

        $cibleOfKill = array_filter($all, function ($value) use ($kill) {
            return $value->nom === $kill->cible;
        });


        if ($killer) {
            $killer = current($killer);
            l('killer -> ' . $killer->nom);
        } else {
            l('ERREUR KILLER NON TROUVE');
            continue;
        }

        $cibleOfKillOk = false;
        while (!$cibleOfKillOk) {
            if ($cibleOfKill) {
                $cibleOfKill = current($cibleOfKill);
                if (empty($cibleOfKill->killed_by)) {
                    l('cible Of Kill -> ' . $cibleOfKill->nom);
                    $cibleOfKillOk = true;
                } else {
                    $cibleOfKill = array_filter($all, function ($value) use ($cibleOfKill) {
                        return $value->nom === $cibleOfKill->cible;
                    });
                }
            }
        }

        if ($killer->cible != $kill->nom) {
            $sujet = 'Bravo vous avez découvert votre tueur !';
            $message = 'Votre cible reste la même : <b>' . $killer->cible . '</b>.<br/>Faites attention un nouveau tueur est à vos trousses !';
            mailing($killer->email, $sujet, $message);

            $ok = false;
            while (!$ok) {
                $key = array_rand($all);
                if ($all[$key]->nom != $killer->nom) {
                    l('Attribution nouvelle Mission aleatoire ' . $all[$key]->mission);
                    $killer->mission = $all[$key]->mission;
                    majPlayer($killer);
                    $ok = true;
                }
            }
        }

        foreach ($newCibleFor as $cibleToChange) {

            l('Nouvelle cible pour ' . $cibleToChange->nom);

            $cible = null;
            if ($cibleToChange->nom != $cibleOfKill->nom) {
                l('Attribution Cible of kill ' . $cibleOfKill->nom);
                $cible = $cibleOfKill;
            } else if (!empty($ciblesDispo)) {
                foreach ($ciblesDispo as $key => $cibleDispo) {
                    l('Attribution Cible dispo ' . $ciblesDispo[$key]->nom);
                    if ($cibleToChange->nom != $ciblesDispo[$key]->nom) {
                        $cible = $ciblesDispo[$key];
                        unset($ciblesDispo[$key]);
                    }
                }
            }


            if (empty($cible)) {
                $ok = false;
                $cpt = 0;
                while (!$ok) {
                    $key = array_rand($allDispo);
                    l('Attribution Cible aleatoire ' . $allDispo[$key]->nom);
                    if ($cibleToChange->nom != $allDispo[$key]->nom) {
                        $cible = $allDispo[$key];
                        $ok = true;
                    }
                    if ($cpt > 100) {
                        l('JE NE RETROUVE PERSONNE, UN GAGNANT ?');
                        die();
                    }
                    $cpt++;
                }
            }

            $sujet = $cibleToChange->nom == $killer->nom && $killer->cible == $kill->nom ? 'Bravo, vous avez tué votre cible !' : 'Votre cible a été tuée !';
            $message = '<b>' . $cible->nom . '</b> est désormais votre nouvelle cible.<br/><br/>Voici votre mission pour l\'éliminer : <br/><b>' . $cible->mission . '</b>';
            mailing($cibleToChange->email, $sujet, $message);

            $cibleToChange->cible = $cible->nom;
            majPlayer($cibleToChange);
        }

        $sujet = 'Vous avez été tué 🔪 ! ';
        $message = $killer->nom . ' vous a eu !<br/>Le jeu s\'arrête malheureusement là pour vous.<br /><h1>Merci d\'avoir participé !</h1>';
        mailing($kill->email, $sujet, $message);
    }
} else {
    echo 'Erreur lors de la requête : ' . error_get_last()['message'];
}
