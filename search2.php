<?php
// Pour afficher les erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function readCSV($filename) {
    $data = [];
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
            // Convertir chaque cellule en UTF-8
            $row = array_map('utf8_encode', $row);
            $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}

// Recherche dans le fichier CSV en fonction du terme de recherche
if (isset($_GET['query'])) {
    $query = trim(strtolower($_GET['query'])); // Trim pour enlever les espaces

    $results = [];
    $data = readCSV('CopieSLOAND.csv');

    foreach ($data as $row) {
        $alphaName = strtolower(trim($row[0])); // Assure-toi de vérifier la casse et d'enlever les espaces
        if (strpos($alphaName, $query) !== FALSE) {
            $results[] = [
                'alphaName' => $row[0],
                'codeACF' => $row[1],
                'region' => $row[9],
                'description' => $row[10],
                'anc' => $row[4],
                'base' => $row[3],
                'fiefAlpha' => $row[2],
                'source' => $row[5],
                'image' => 'path_to_image' // Assuming you have a path to the image
            ];
        }
    }

    echo json_encode($results);
}
?>