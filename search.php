<?php
    function readCSV($filename) {
        $data = [];
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }

    // Recherche dans le fichier CSV en fonction du terme de recherche
    if (isset($_GET['query'])) {
        $query = strtolower($_GET['query']);
        $results = [];

        $data = readCSV('data.csv');
        $data2 = readCSV('data2.csv');

        // Créer un tableau de correspondance à partir de data2 pour plus facilement ajouter des données complémentaires
        $data2Map = [];
        foreach ($data2 as $row2) {
            $alphaName = strtolower($row2[0] ?? ''); // Utiliser la première colonne de data2 comme clé (alphaName)
            $data2Map[$alphaName] = [
                'region' => $row2[1] ?? '',
                'description' => $row2[2] ?? '',
                'image' => $row2[3] ?? './images/blason1.jpg',
            ];
        }

        // Chercher dans le premier fichier et fusionner avec les données du second fichier
        foreach ($data as $row) {
            if (isset($row[1]) && strpos(strtolower($row[1]), $query) !== false) {
                $alphaName = strtolower($row[1]); // Utiliser la deuxième colonne de data pour alphaName

                // Créer le résultat de base avec les informations de data.csv
                $result = [
                    'index' => $row[0],
                    'alphaName' => $row[1],
                    'codeACF' => $row[2],
                    'nomVente' => $row[3],
                    'base' => $row[4],
                    'anc' => $row[5] ?? '',
                    'fiefAlpha' => $row[6] ?? '',
                    'fiefTL' => $row[7] ?? '',
                    'histo' => $row[8] ?? '',
                    'source' => $row[9],
                    'region' => $row[10]
                ];

                // Ajouter les informations supplémentaires de data2.csv si elles existent
                if (isset($data2Map[$alphaName])) {
                    $result = array_merge($result, $data2Map[$alphaName]);
                }

                $results[] = $result;
            }
        }

        echo json_encode($results);
    }
?>
