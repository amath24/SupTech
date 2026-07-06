<?php
$page_required_role = ['Administrateur', 'Scolarité', 'Enseignant'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Gestion des Modules';
$activePage = 'modules';

$db = Database::getInstance();

// Variables for Add Module Modal
$all_enseignants = $db->query("SELECT id_enseignant, nom, prenom FROM enseignants ORDER BY nom, prenom")->fetchAll();
$all_classes = $db->query("SELECT id_classe, nom, niveau FROM classes ORDER BY niveau, nom")->fetchAll();
$all_annees = $db->query("SELECT id_annee, libelle FROM annees_universitaires ORDER BY libelle DESC")->fetchAll();

// Filter
$filiere_filter = $_GET['filiere'] ?? '';

// Fetch filters
$filieres = $db->query("SELECT DISTINCT filiere FROM classes WHERE filiere IS NOT NULL AND filiere != '' ORDER BY filiere")->fetchAll(PDO::FETCH_COLUMN);

$queryStr = "
    SELECT 
        m.id_module, m.nom, m.coefficient, m.nombre_heures,
        GROUP_CONCAT(DISTINCT c.niveau SEPARATOR ', ') as classes_niveaux,
        GROUP_CONCAT(DISTINCT c.filiere SEPARATOR ', ') as classes_filieres,
        MAX(e.nom) as enseignant_nom, MAX(e.prenom) as enseignant_prenom
    FROM modules m
    LEFT JOIN assignation_modules am ON m.id_module = am.id_module
    LEFT JOIN classes c ON am.id_classe = c.id_classe
    LEFT JOIN enseignants e ON am.id_enseignant = e.id_enseignant
";

$whereClauses = [];
$params = [];

if (!empty($filiere_filter)) {
    $whereClauses[] = "c.filiere = ?";
    $params[] = $filiere_filter;
}

if (!empty($whereClauses)) {
    $queryStr .= " WHERE " . implode(" AND ", $whereClauses);
}

$queryStr .= " GROUP BY m.id_module ORDER BY m.nom";

$stmt = $db->prepare($queryStr);
$stmt->execute($params);
$modules = $stmt->fetchAll();

// Helpers for badges/colors
function getModuleGradient($index) {
    $gradients = ['primary-gradient', 'secondary-gradient', 'tertiary-gradient'];
    return $gradients[$index % count($gradients)];
}
function getModuleIconColor($index) {
    $colors = ['text-primary bg-primary-subtle', 'text-secondary bg-secondary-subtle', 'text-success bg-success-subtle'];
    return $colors[$index % count($colors)];
}

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <!-- En-tête -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="page-title">Gestion des Modules</h1>
                <p class="page-subtitle mb-0">Gérez le catalogue des cours, les affectations d'enseignants et les volumes horaires.</p>
            </div>
            <?php if (in_array(strtolower($_SESSION['user_role'] ?? ''), ['administrateur', 'scolarité'])): ?>
            <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                <span class="material-symbols-outlined" style="font-size: 20px;">add</span>
                Ajouter un module
            </button>
            <?php endif; ?>
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

        <!-- Filtres -->
        <form method="GET" action="gestiondesmodule.php" class="filter-bar d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between mb-4">
            <div class="filter-pills d-flex gap-2 overflow-auto pb-1 pb-md-0">
                <a href="gestiondesmodule.php" class="btn btn-filter <?= empty($filiere_filter) ? 'active' : 'btn-outline-secondary' ?> btn-sm">Tous les modules</a>
            </div>
            <div style="min-width: 250px;">
                <div class="input-group">
                    <span class="input-group-text"><span class="material-symbols-outlined" style="font-size: 18px;">filter_list</span></span>
                    <select class="form-select form-select-sm" name="filiere" onchange="this.form.submit()">
                        <option value="">Toutes les filières</option>
                        <?php foreach ($filieres as $f): ?>
                            <option value="<?= htmlspecialchars($f) ?>" <?= $filiere_filter === $f ? 'selected' : '' ?>>
                                <?= htmlspecialchars($f) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <!-- Grille des modules -->
        <div class="row g-4">
            <?php if (empty($modules)): ?>
                <div class="col-12 text-center py-5">
                    <div class="surface-card p-5 border rounded bg-white text-muted shadow-sm">
                        <span class="material-symbols-outlined" style="font-size: 64px; color: var(--suptech-outline-variant);">menu_book</span>
                        <h3 class="h5 mt-3 fw-semibold text-dark">Aucun module trouvé</h3>
                        <p class="mb-4">Il n'y a pas de module correspondant à vos critères de recherche ou la base de données ne contient aucun module.</p>
                        <?php if (in_array(strtolower($_SESSION['user_role'] ?? ''), ['administrateur', 'scolarité'])): ?>
                        <button type="button" class="btn btn-primary-suptech d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                            <span class="material-symbols-outlined">add_circle</span>
                            Créer le premier module
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($modules as $index => $module): ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="module-card h-100 d-flex flex-column bg-white border rounded shadow-sm position-relative overflow-hidden">
                            <div class="module-card-bar <?= getModuleGradient($index) ?>" style="height: 8px;"></div>
                            <div class="p-4 flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="p-2 rounded <?= getModuleIconColor($index) ?>">
                                        <span class="material-symbols-outlined">menu_book</span>
                                    </div>
                                    <?php if (!empty($module['classes_niveaux'])): ?>
                                        <span class="badge bg-secondary-subtle text-secondary border" style="font-size: 0.7rem;">
                                            <?= htmlspecialchars(explode(',', $module['classes_niveaux'])[0]) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">Non assigné</span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="h6 fw-semibold mb-1"><?= htmlspecialchars($module['nom']) ?></h3>
                                <p class="text-muted small mb-4">
                                    <?= !empty($module['classes_filieres']) ? 'Filières: ' . htmlspecialchars($module['classes_filieres']) : 'Aucune filière assignée.' ?>
                                </p>
                                <div class="mt-auto">
                                    <div class="d-flex align-items-center gap-2 small mb-3">
                                        <span class="material-symbols-outlined text-muted" style="font-size: 18px;">person</span>
                                        <span>
                                            <?= !empty($module['enseignant_nom']) ? htmlspecialchars($module['enseignant_nom'] . ' ' . $module['enseignant_prenom']) : 'Non assigné' ?>
                                        </span>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <span class="badge bg-light text-muted d-flex align-items-center gap-1">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span> <?= htmlspecialchars($module['nombre_heures']) ?>h
                                        </span>
                                        <span class="badge bg-light text-muted d-flex align-items-center gap-1">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">functions</span> Coeff: <?= htmlspecialchars($module['coefficient']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php if (in_array(strtolower($_SESSION['user_role'] ?? ''), ['administrateur', 'scolarité'])): ?>
                            <div class="module-card-actions p-3 border-top bg-light d-flex justify-content-end gap-2">
                                <form action="supprimer_module.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce module ?');" class="d-inline">
                                    <input type="hidden" name="id_module" value="<?= $module['id_module'] ?>">
                                    <button type="submit" class="btn btn-sm btn-link text-danger" title="Supprimer">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (in_array(strtolower($_SESSION['user_role'] ?? ''), ['administrateur', 'scolarité'])): ?>
                <!-- Ajouter un module -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="module-add-card h-100 d-flex flex-column align-items-center justify-content-center bg-light border rounded border-dashed text-center p-4" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                        <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-primary" style="font-size: 28px;">add</span>
                        </div>
                        <h3 class="h6 fw-semibold mb-2">Nouveau Module</h3>
                        <p class="text-muted small mb-0">Cliquez ici pour créer et configurer un nouveau cours.</p>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php if (in_array(strtolower($_SESSION['user_role'] ?? ''), ['administrateur', 'scolarité'])): ?>
<!-- Modale d'ajout de module -->
<div class="modal fade" id="addModuleModal" tabindex="-1" aria-labelledby="addModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.75rem;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                <h5 class="modal-title d-flex align-items-center gap-2" id="addModuleModalLabel">
                    <span class="material-symbols-outlined">library_add</span>
                    Ajouter un nouveau module
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="<?= url('gestion_des_modules/ajouter_module.php') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-semibold small">Nom du module</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Ex: Développement Web PHP" required>
                    </div>
                    <div class="mb-3">
                        <label for="coefficient" class="form-label fw-semibold small">Coefficient</label>
                        <input type="number" step="0.5" class="form-control" id="coefficient" name="coefficient" value="1.0" min="0.5" max="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_heures" class="form-label fw-semibold small">Nombre d'heures</label>
                        <input type="number" class="form-control" id="nombre_heures" name="nombre_heures" value="30" min="1" max="200" required>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="fw-bold mb-3">Assignation Initiale</h6>
                    
                    <div class="mb-3">
                        <label for="id_enseignant" class="form-label fw-semibold small">Enseignant</label>
                        <select class="form-select" id="id_enseignant" name="id_enseignant" required>
                            <option value="">Sélectionner un enseignant</option>
                            <?php foreach ($all_enseignants as $enseignant): ?>
                                <option value="<?= $enseignant['id_enseignant'] ?>"><?= htmlspecialchars($enseignant['nom'] . ' ' . $enseignant['prenom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_classe" class="form-label fw-semibold small">Classe</label>
                        <select class="form-select" id="id_classe" name="id_classe" required>
                            <option value="">Sélectionner une classe</option>
                            <?php foreach ($all_classes as $classe): ?>
                                <option value="<?= $classe['id_classe'] ?>"><?= htmlspecialchars($classe['niveau'] . ' - ' . $classe['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_annee" class="form-label fw-semibold small">Année Universitaire</label>
                        <select class="form-select" id="id_annee" name="id_annee" required>
                            <?php foreach ($all_annees as $annee): ?>
                                <option value="<?= $annee['id_annee'] ?>"><?= htmlspecialchars($annee['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
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
<?php endif; ?>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
</body>
</html>
