<?php
/**
 * Système de journalisation basé sur un fichier JSON
 */

function log_action($action, $details, $utilisateur_id = null, $nom_complet = 'Inconnu', $role = 'Inconnu') {
    // Définir le chemin vers le fichier de logs
    $log_dir = __DIR__ . '/../gestion_des_journaux';
    $log_file = $log_dir . '/activity_logs.json';

    // Créer le dossier s'il n'existe pas
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0777, true);
    }

    // Lire les logs existants
    $logs = [];
    if (file_exists($log_file)) {
        $content = file_get_contents($log_file);
        if ($content) {
            $logs = json_decode($content, true) ?: [];
        }
    }

    // Préparer la nouvelle entrée
    $nouvelle_entree = [
        'id' => uniqid(),
        'date' => date('Y-m-d H:i:s'),
        'adresse_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Inconnue',
        'utilisateur_id' => $utilisateur_id,
        'nom_complet' => $nom_complet,
        'role' => $role,
        'action' => $action,
        'details' => $details
    ];

    // Ajouter au début du tableau pour avoir les plus récents en premier
    array_unshift($logs, $nouvelle_entree);

    // Ne garder que les 1000 derniers logs pour éviter que le fichier ne devienne trop volumineux
    if (count($logs) > 1000) {
        $logs = array_slice($logs, 0, 1000);
    }

    // Sauvegarder dans le fichier
    file_put_contents($log_file, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
