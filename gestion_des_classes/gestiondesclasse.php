<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Gestion des Classes';
$activePage = 'classes';

// Données de session pour le header.php
$userName = $_SESSION['user_nom'] ?? 'Utilisateur';
$userRole = $_SESSION['user_role'] ?? 'Rôle';
$userInitials = strtoupper(substr($_SESSION['user_nom'] ?? 'U', 0, 1));

// Connexion BDD
$db = Database::getInstance();

// Récupération des filtres
$filiere_filter = $_GET['filiere'] ?? '';
$niveau_filter = $_GET['niveau'] ?? '';

// Récupération dynamique de toutes les filières et niveaux existants pour les filtres
$filieres = $db->query("SELECT DISTINCT filiere FROM classes WHERE filiere IS NOT NULL AND filiere != '' ORDER BY filiere")->fetchAll(PDO::FETCH_COLUMN);
$niveaux = $db->query("SELECT DISTINCT niveau FROM classes WHERE niveau IS NOT NULL AND niveau != '' ORDER BY niveau")->fetchAll(PDO::FETCH_COLUMN);

// Construction de la requête pour les classes avec effectif réel
$queryStr = "
    SELECT c.*, COUNT(e.id_etudiant) AS effectif_actuel
    FROM classes c
    LEFT JOIN etudiants e ON c.id_classe = e.id_classe
";

$whereClauses = [];
$params = [];

if (!empty($filiere_filter)) {
    $whereClauses[] = "c.filiere = ?";
    $params[] = $filiere_filter;
}
if (!empty($niveau_filter)) {
    $whereClauses[] = "c.niveau = ?";
    $params[] = $niveau_filter;
}

if (!empty($whereClauses)) {
    $queryStr .= " WHERE " . implode(" AND ", $whereClauses);
}

$queryStr .= " GROUP BY c.id_classe ORDER BY c.niveau, c.nom";

$stmt = $db->prepare($queryStr);
$stmt->execute($params);
$classes = $stmt->fetchAll();

// Statistiques globales (non filtrées)
$totalClasses = $db->query("SELECT COUNT(*) FROM classes")->fetchColumn();
$totalFilieres = $db->query("SELECT COUNT(DISTINCT filiere) FROM classes")->fetchColumn();

// Calcul du taux d'occupation global
$stmtCapacity = $db->query("
    SELECT 
        SUM(c.effectif_max) as total_max,
        (SELECT COUNT(*) FROM etudiants) as total_etudiants
    FROM classes c
");
$capacityData = $stmtCapacity->fetch();
$totalMax = (int)($capacityData['total_max'] ?? 0);
$totalEtudiants = (int)($capacityData['total_etudiants'] ?? 0);
$capaciteGlobalePercent = $totalMax > 0 ? round(($totalEtudiants / $totalMax) * 100) : 0;

// Helper pour les icônes en fonction de la filière
function getIconForFiliere(string $filiere): string {
    $f = strtolower(trim($filiere));
    if (strpos($f, 'info') !== false || strpos($f, 'dev') !== false || strpos($f, 'génie') !== false || strpos($f, 'web') !== false || strpos($f, 'techno') !== false) {
        return 'terminal';
    } elseif (strpos($f, 'gestion') !== false || strpos($f, 'commerce') !== false || strpos($f, 'finan') !== false || strpos($f, 'compta') !== false || strpos($f, 'market') !== false || strpos($f, 'business') !== false) {
        return 'public';
    } elseif (strpos($f, 'rh') !== false || strpos($f, 'ressources') !== false || strpos($f, 'admin') !== false) {
        return 'groups';
    }
    return 'architecture';
}

// Helper pour le niveau (badge styling)
function getNiveauClass(string $niveau): string {
    $n = strtolower(trim($niveau));
    if (strpos($n, 'l1') !== false || strpos($n, 'licence 1') !== false) {
        return 'l3';
    } elseif (strpos($n, 'l2') !== false || strpos($n, 'licence 2') !== false) {
        return 'l3';
    } elseif (strpos($n, 'l3') !== false || strpos($n, 'licence 3') !== false) {
        return 'l3';
    } elseif (strpos($n, 'm1') !== false || strpos($n, 'master 1') !== false) {
        return 'm1';
    } elseif (strpos($n, 'm2') !== false || strpos($n, 'master 2') !== false) {
        return 'm1';
    } elseif (strpos($n, 'bts') !== false) {
        return 'bts';
    }
    return 'bts';
}

// Helper pour le progress bar color
function getProgressBarColor(string $niveau): string {
    $class = getNiveauClass($niveau);
    if ($class === 'l3') return 'progress-bar-secondary';
    if ($class === 'm1') return 'progress-bar-tertiary';
    return 'progress-bar-primary';
}

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <!-- En-tête de page -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="page-title">Gestion des Classes</h2>
                <p class="page-subtitle mb-0">Supervisez et gérez les effectifs académiques.</p>
            </div>
            <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addClasseModal">
                <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span>
                Ajouter une classe
            </button>
        </div>

        <!-- Notifications flash -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 border-0 shadow-sm" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
                <span class="material-symbols-outlined">check_circle</span>
                <div><?= htmlspecialchars($_SESSION['success']) ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 border-0 shadow-sm" role="alert" style="background-color: #f8d7da; color: #842029;">
                <span class="material-symbols-outlined">error</span>
                <div><?= htmlspecialchars($_SESSION['error']) ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Statistiques -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon primary">
                        <span class="material-symbols-outlined">domain</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Total Classes</p>
                        <p class="stat-value mb-0"><?= $totalClasses ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon secondary">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Filières Actives</p>
                        <p class="stat-value mb-0"><?= $totalFilieres ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon tertiary">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Capacité Globale</p>
                        <p class="stat-value mb-0"><?= $capaciteGlobalePercent ?>%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <form method="GET" action="gestiondesclasse.php" class="filter-bar d-flex flex-wrap gap-3 align-items-center mb-4">
            <div class="flex-grow-1" style="min-width: 200px;">
                <div class="input-group">
                    <span class="input-group-text"><span class="material-symbols-outlined" style="font-size: 18px;">filter_list</span></span>
                    <select class="form-select" name="filiere" onchange="this.form.submit()">
                        <option value="">Toutes les filières</option>
                        <?php foreach ($filieres as $f): ?>
                            <option value="<?= htmlspecialchars($f) ?>" <?= $filiere_filter === $f ? 'selected' : '' ?>>
                                <?= htmlspecialchars($f) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="flex-grow-1" style="min-width: 200px;">
                <div class="input-group">
                    <span class="input-group-text"><span class="material-symbols-outlined" style="font-size: 18px;">layers</span></span>
                    <select class="form-select" name="niveau" onchange="this.form.submit()">
                        <option value="">Tous les niveaux</option>
                        <?php foreach ($niveaux as $n): ?>
                            <option value="<?= htmlspecialchars($n) ?>" <?= $niveau_filter === $n ? 'selected' : '' ?>>
                                <?= htmlspecialchars($n) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php if (!empty($filiere_filter) || !empty($niveau_filter)): ?>
                <a href="gestiondesclasse.php" class="btn btn-outline-suptech">Réinitialiser</a>
            <?php endif; ?>
        </form>

        <!-- Grille des classes -->
        <div class="row g-4">
            <?php if (empty($classes)): ?>
                <div class="col-12 text-center py-5">
                    <div class="surface-card p-5 border rounded bg-white text-muted shadow-sm">
                        <span class="material-symbols-outlined" style="font-size: 64px; color: var(--suptech-outline-variant);">domain_disabled</span>
                        <h3 class="h5 mt-3 fw-semibold text-dark">Aucune classe trouvée</h3>
                        <p class="mb-4">Il n'y a pas de classe correspondant à vos critères de recherche ou la base de données ne contient aucune classe.</p>
                        <button type="button" class="btn btn-primary-suptech d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addClasseModal">
                            <span class="material-symbols-outlined">add_circle</span>
                            Créer la première classe
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($classes as $classe): ?>
                    <?php 
                    $effectif_actuel = (int)$classe['effectif_actuel'];
                    $effectif_max = (int)$classe['effectif_max'];
                    $percent = $effectif_max > 0 ? round(($effectif_actuel / $effectif_max) * 100, 1) : 0;
                    
                    // Déterminer la couleur du pourcentage
                    $badgeBg = 'bg-primary-subtle text-primary';
                    if ($percent >= 90) {
                        $badgeBg = 'bg-danger-subtle text-danger';
                    } elseif ($percent >= 75) {
                        $badgeBg = 'bg-warning-subtle text-warning';
                    } else {
                        $badgeBg = 'bg-success-subtle text-success';
                    }
                    ?>
                    <div class="col-lg-6 col-xl-4">
                        <div class="class-card shadow-sm border border-light">
                            <div class="class-card-header">
                                <span class="material-symbols-outlined bg-icon"><?= htmlspecialchars(getIconForFiliere($classe['filiere'])) ?></span>
                                <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 1;">
                                    <div>
                                        <span class="badge-niveau <?= htmlspecialchars(getNiveauClass($classe['niveau'])) ?>">Niveau: <?= htmlspecialchars($classe['niveau']) ?></span>
                                        <h3 class="h5 fw-semibold mb-1"><?= htmlspecialchars($classe['nom']) ?></h3>
                                        <h4 class="h6 text-primary mb-0"><?= htmlspecialchars($classe['filiere']) ?></h4>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-link text-muted p-1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="material-symbols-outlined">more_vert</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="alert('Modification à venir dans un prochain sprint')"><span class="material-symbols-outlined" style="font-size: 18px;">edit</span> Modifier</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="#" onclick="alert('Suppression à venir dans un prochain sprint')"><span class="material-symbols-outlined" style="font-size: 18px;">delete</span> Supprimer</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 flex-grow-1 d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-3 p-3 rounded border bg-light">
                                    <div class="avatar-sm bg-light text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%;">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">account_tree</span>
                                    </div>
                                    <div>
                                        <p class="stat-label mb-0">Filière</p>
                                        <p class="fw-medium mb-0"><?= htmlspecialchars($classe['filiere']) ?></p>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-end mb-2">
                                        <div>
                                            <p class="stat-label mb-0">Effectif</p>
                                            <p class="mb-0"><span class="fs-4 fw-bold"><?= $effectif_actuel ?></span> <span class="text-muted">/ <?= $effectif_max ?></span></p>
                                        </div>
                                        <span class="badge <?= $badgeBg ?>"><?= $percent ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar <?= htmlspecialchars(getProgressBarColor($classe['niveau'])) ?>" style="width: <?= min(100, $percent) ?>%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border-top bg-light d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary flex-fill btn-sm">Voir Détails</button>
                                <button type="button" class="btn btn-outline-secondary flex-fill btn-sm">Emploi du temps</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modale d'ajout de classe -->
<div class="modal fade" id="addClasseModal" tabindex="-1" aria-labelledby="addClasseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.75rem;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                <h5 class="modal-title d-flex align-items-center gap-2" id="addClasseModalLabel">
                    <span class="material-symbols-outlined">domain_add</span>
                    Ajouter une nouvelle classe
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="<?= url('gestion_des_classes/ajouter_classe.php') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-semibold small">Nom de la classe</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Ex: Licence 3 - Dev Web" required>
                    </div>
                    <div class="mb-3">
                        <label for="filiere" class="form-label fw-semibold small">Filière</label>
                        <input type="text" class="form-control" id="filiere" name="filiere" placeholder="Ex: Informatique" required>
                    </div>
                    <div class="mb-3">
                        <label for="niveau" class="form-label fw-semibold small">Niveau</label>
                        <select class="form-select" id="niveau" name="niveau" required>
                            <option value="">Sélectionner un niveau</option>
                            <option value="Licence 1">Licence 1</option>
                            <option value="Licence 2">Licence 2</option>
                            <option value="Licence 3">Licence 3</option>
                            <option value="Master 1">Master 1</option>
                            <option value="Master 2">Master 2</option>
                            <option value="BTS 1">BTS 1</option>
                            <option value="BTS 2">BTS 2</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="effectif_max" class="form-label fw-semibold small">Effectif maximum</label>
                        <input type="number" class="form-control" id="effectif_max" name="effectif_max" value="40" min="1" max="100" required>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3" style="border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">save</span>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
</body>
