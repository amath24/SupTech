<?php
// Démarrer la session en toute sécurité si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Configuration globale SupTech
 */
define('APP_NAME', 'ISM Management');
define('APP_SUBTITLE', 'Portail Administratif');
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/SupTech');

/**
 * Retourne l'URL d'un asset
 */
function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Retourne l'URL d'une page
 */
function url(string $path): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Menu de navigation principal
 */
function getMenuItems(): array
{
    $role = strtolower(trim($_SESSION['user_role'] ?? ''));
    $items = [];

    // =========================================================
    // MENU ADMINISTRATEUR : Accès total
    // =========================================================
    if ($role === 'administrateur') {
        $items = [
            ['id' => 'dashboard',       'label' => 'Dashboard',        'icon' => 'dashboard',       'url' => url('dashboard/dashboard.php')],
            ['id' => 'etudiants',       'label' => 'Étudiants',        'icon' => 'groups',          'url' => url('gestion_des_etudiants/gestiondesetudiant.php')],
            ['id' => 'enseignants',     'label' => 'Enseignants',      'icon' => 'school',          'url' => url('gestion_des_enseignants/gestiondesenseignants.php')],
            ['id' => 'classes',         'label' => 'Classes',          'icon' => 'class',           'url' => url('gestion_des_classes/gestiondesclasse.php')],
            ['id' => 'salles',          'label' => 'Salles',           'icon' => 'meeting_room',    'url' => url('gestion_des_salle/gestiondessalle.php')],
            ['id' => 'modules',         'label' => 'Modules',          'icon' => 'book',            'url' => url('gestion_des_modules/gestiondesmodule.php')],
            ['id' => 'inscriptions',    'label' => 'Inscriptions',     'icon' => 'person_add',      'url' => url('gestion_des_inscriptions/gestiondesinscription.php')],
            ['id' => 'paiements',       'label' => 'Paiements',        'icon' => 'payments',        'url' => url('gestion_des_paiements/gestiondespaiements.php')],
            ['id' => 'notes',           'label' => 'Notes',            'icon' => 'description',     'url' => url('gestion_des_notes_enseignant/gestiondesnote.php')],
            ['id' => 'emploi_du_temps', 'label' => 'Emploi du temps',  'icon' => 'calendar_today',  'url' => url('emploi_du_temps/emploi_du_temps.php')],
            ['id' => 'statistiques',    'label' => 'Statistiques',     'icon' => 'query_stats',     'url' => url('statistique/statistique.php')],
        ];
    }
    // =========================================================
    // MENU ENSEIGNANT : Périmètre pédagogique
    // =========================================================
    elseif ($role === 'enseignant') {
        $items = [
            ['id' => 'notes',           'label' => 'Saisie des Notes', 'icon' => 'description',     'url' => url('gestion_des_notes_enseignant/gestiondesnote.php')],
            ['id' => 'emploi_du_temps', 'label' => 'Mon Emploi du temps','icon' => 'calendar_today',  'url' => url('emploi_du_temps/emploi_du_temps.php')],
        ];
    }
    // =========================================================
    // MENU SCOLARITÉ : Périmètre administratif et financier
    // =========================================================
    elseif ($role === 'scolarité' || $role === 'scolarite') {
        $items = [
            ['id' => 'etudiants',       'label' => 'Étudiants',        'icon' => 'groups',          'url' => url('gestion_des_etudiants/gestiondesetudiant.php')],
            ['id' => 'modules',         'label' => 'Mes Modules',      'icon' => 'book',            'url' => url('gestion_des_modules/gestiondesmodule.php')],
            ['id' => 'classes',         'label' => 'Classes',          'icon' => 'class',           'url' => url('gestion_des_classes/gestiondesclasse.php')],
            ['id' => 'salles',          'label' => 'Salles',           'icon' => 'meeting_room',    'url' => url('gestion_des_salle/gestiondessalle.php')],
            ['id' => 'inscriptions',    'label' => 'Inscriptions',     'icon' => 'person_add',      'url' => url('gestion_des_inscriptions/gestiondesinscription.php')],
            ['id' => 'paiements',       'label' => 'Paiements',        'icon' => 'payments',        'url' => url('gestion_des_paiements/gestiondespaiements.php')],
            ['id' => 'emploi_du_temps', 'label' => 'Emploi du temps',  'icon' => 'calendar_today',  'url' => url('emploi_du_temps/emploi_du_temps.php')],
        ];
    }

    return $items;
}
