<?php
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Emploi du temps';
$activePage = 'emploi_du_temps';

require_once __DIR__ . '/../include/head.php';

// Connexion à la base de données
$pdo = Database::getInstance();

// Récupérer la classe sélectionnée
$selected_classe_id = isset($_GET['classe_id']) ? (int)$_GET['classe_id'] : 0;

$user_role = strtolower($_SESSION['user_role'] ?? '');
$user_id = $_SESSION['user_id'] ?? 0;
$id_enseignant = 0;
if ($user_role === 'enseignant') {
    $stmt = $pdo->prepare("SELECT id_enseignant FROM enseignants WHERE id_utilisateur = ?");
    $stmt->execute([$user_id]);
    $id_enseignant = $stmt->fetchColumn() ?: 0;
}

// Récupérer la liste des classes
$sqlClasses = "SELECT id_classe, nom, niveau FROM classes";
$paramsClasses = [];

if ($user_role === 'enseignant') {
    $sqlClasses .= " WHERE id_classe IN (SELECT id_classe FROM assignation_modules WHERE id_enseignant = ?)";
    $paramsClasses[] = $id_enseignant;
}
$sqlClasses .= " ORDER BY niveau, nom";

$stmtClasses = $pdo->prepare($sqlClasses);
$stmtClasses->execute($paramsClasses);
$classes = $stmtClasses->fetchAll();

if ($selected_classe_id === 0 && count($classes) > 0) {
    $selected_classe_id = $classes[0]['id_classe'];
}

// Fetch the timetable for the selected class
$seances_raw = [];
if ($selected_classe_id > 0) {
    $sql = "
        SELECT 
            et.id_seance, et.jour, et.heure_debut, et.heure_fin,
            m.nom AS module_nom,
            e.nom AS prof_nom, e.prenom AS prof_prenom,
            s.nom AS salle_nom
        FROM emploi_temps et
        JOIN assignation_modules am ON et.id_assignation = am.id_assignation
        JOIN modules m ON am.id_module = m.id_module
        JOIN enseignants e ON am.id_enseignant = e.id_enseignant
        JOIN salles s ON et.id_salle = s.id_salle
        WHERE am.id_classe = :classe_id
        ORDER BY et.heure_debut ASC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['classe_id' => $selected_classe_id]);
    $seances_raw = $stmt->fetchAll();
}

$jours_semaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

// Extract unique timeslots
$timeslots = [];
foreach ($seances_raw as $s) {
    $slot = date('H:i', strtotime($s['heure_debut'])) . ' - ' . date('H:i', strtotime($s['heure_fin']));
    if (!in_array($slot, $timeslots)) {
        $timeslots[] = $slot;
    }
}
usort($timeslots, function($a, $b) {
    return strcmp(substr($a, 0, 5), substr($b, 0, 5));
});

// Build matrix
$matrix = [];
foreach ($timeslots as $slot) {
    foreach ($jours_semaine as $jour) {
        $matrix[$slot][$jour] = [];
    }
}

foreach ($seances_raw as $s) {
    $slot = date('H:i', strtotime($s['heure_debut'])) . ' - ' . date('H:i', strtotime($s['heure_fin']));
    $jour = $s['jour'];
    $matrix[$slot][$jour][] = $s;
}

?>
<body class="admin-layout">
    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../include/header.php'; ?>

        <main class="main-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h2 class="page-title">Emploi du temps</h2>
                    <p class="page-subtitle mb-0">Gestion hebdomadaire des cours et ressources</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <form method="GET" action="emploi_du_temps.php" class="d-flex gap-2 m-0">
                        <select name="classe_id" class="form-select form-select-sm" style="min-width:250px;" onchange="this.form.submit()">
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id_classe'] ?>" <?= $c['id_classe'] == $selected_classe_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['niveau'] . ' - ' . $c['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    
                    <?php if (strtolower(trim($_SESSION['user_role'] ?? '')) === 'administrateur'): ?>
                    <a href="ajouter_seance.php" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size:18px;">add</span>
                        Ajouter
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="surface-card">
                <div class="table-responsive p-3">
                    <?php if (empty($timeslots)): ?>
                        <div class="alert alert-info mb-0 d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined">info</span>
                            Aucun cours n'est programmé pour cette classe.
                        </div>
                    <?php else: ?>
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px" class="text-center">Heure</th>
                                <?php foreach ($jours_semaine as $jour): ?>
                                <th class="text-center" style="min-width: 150px;"><?= $jour ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($timeslots as $slot): ?>
                            <tr>
                                <td class="fw-bold align-middle text-center bg-light text-primary"><?= str_replace(' - ', '<br>-<br>', $slot) ?></td>
                                <?php foreach ($jours_semaine as $jour): ?>
                                <td class="align-top">
                                    <?php if (!empty($matrix[$slot][$jour])): ?>
                                        <?php foreach ($matrix[$slot][$jour] as $seance): ?>
                                            <div class="card p-2 mb-2 shadow-sm border-0 border-start border-primary border-4 bg-white" style="border-radius: 4px;">
                                                <div class="fw-bold text-primary mb-1" style="font-size: 0.95rem;"><?= htmlspecialchars($seance['module_nom']) ?></div>
                                                <div class="small text-muted d-flex align-items-center gap-1 mb-1">
                                                    <span class="material-symbols-outlined" style="font-size:14px;">person</span>
                                                    <?= htmlspecialchars($seance['prof_prenom'] . ' ' . $seance['prof_nom']) ?>
                                                </div>
                                                <div class="small text-muted d-flex align-items-center gap-1">
                                                    <span class="material-symbols-outlined" style="font-size:14px;">room</span>
                                                    <?= htmlspecialchars($seance['salle_nom']) ?>
                                                </div>
                                                <?php if (strtolower(trim($_SESSION['user_role'] ?? '')) === 'administrateur'): ?>
                                                <div class="mt-2 text-end">
                                                    <a href="supprimer_seance.php?id=<?= $seance['id_seance'] ?>" class="text-danger text-decoration-none small d-inline-flex align-items-center p-1 rounded hover-bg-light" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette séance ?');">
                                                        <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>

    <style>
        .hover-bg-light:hover { background-color: #f8f9fa; }
    </style>

    <?php require_once __DIR__ . '/../include/footer.php'; ?>