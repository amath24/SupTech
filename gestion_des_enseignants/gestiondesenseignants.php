<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Gestion des Enseignants';
$activePage = 'enseignants';

$db = Database::getInstance();

// Filters
$search = $_GET['search'] ?? '';
$specialite_filter = $_GET['specialite'] ?? '';
$module_filter = $_GET['module'] ?? '';

// Fetch options for filters
$specialites = $db->query("SELECT DISTINCT specialite FROM enseignants WHERE specialite IS NOT NULL AND specialite != '' ORDER BY specialite")->fetchAll(PDO::FETCH_COLUMN);
$all_modules = $db->query("SELECT id_module, nom FROM modules ORDER BY nom")->fetchAll();

// Build query
$queryStr = "
    SELECT e.*, 
        GROUP_CONCAT(DISTINCT m.nom SEPARATOR ', ') as matieres
    FROM enseignants e
    LEFT JOIN assignation_modules am ON e.id_enseignant = am.id_enseignant
    LEFT JOIN modules m ON am.id_module = m.id_module
";

$whereClauses = [];
$params = [];

if (!empty($search)) {
    $whereClauses[] = "(e.nom LIKE ? OR e.prenom LIKE ? OR e.matricule LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if (!empty($specialite_filter)) {
    $whereClauses[] = "e.specialite = ?";
    $params[] = $specialite_filter;
}

if (!empty($module_filter)) {
    $whereClauses[] = "m.id_module = ?";
    $params[] = $module_filter;
}

if (!empty($whereClauses)) {
    $queryStr .= " WHERE " . implode(" AND ", $whereClauses);
}

$queryStr .= " GROUP BY e.id_enseignant ORDER BY e.nom, e.prenom";

$stmt = $db->prepare($queryStr);
$stmt->execute($params);
$enseignants = $stmt->fetchAll();

// Get next matricule for Add Modal (informational only, backend will generate the final one)
$year = date('Y');
$stmt_last = $db->query("SELECT matricule FROM enseignants WHERE matricule LIKE 'ENS-$year-%' ORDER BY matricule DESC LIMIT 1");
$last_mat = $stmt_last->fetchColumn();
$next_num = 1;
if ($last_mat) {
    $parts = explode('-', $last_mat);
    $next_num = (int)end($parts) + 1;
}
$next_matricule_preview = "ENS-$year-" . str_pad($next_num, 3, '0', STR_PAD_LEFT);

function getInitials($prenom, $nom) {
    return mb_strtoupper(mb_substr($prenom, 0, 1) . mb_substr($nom, 0, 1));
}

require_once __DIR__ . '/../include/head.php';
?>
<body class="admin-layout">
    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../include/header.php'; ?>

        <main class="main-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h1 class="page-title">Gestion des Enseignants</h1>
                    <p class="page-subtitle mb-0">Gérez la liste complète du personnel enseignant et leurs spécialités.</p>
                </div>
                <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                    <span class="material-symbols-outlined" style="font-size: 20px;">add</span>
                    Ajouter un enseignant
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

            <!-- Filtres -->
            <form method="GET" action="gestiondesenseignants.php" class="filter-bar d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between mb-4">
                <div class="position-relative flex-grow-1" style="max-width: 420px;">
                    <span class="material-symbols-outlined input-icon">search</span>
                    <input type="text" class="form-control ps-5" name="search" placeholder="Rechercher par nom, matricule..." value="<?= htmlspecialchars($search) ?>" onchange="this.form.submit()">
                </div>
                <div class="d-flex gap-2 w-100 w-md-auto">
                    <select class="form-select form-select-sm" name="specialite" style="min-width: 180px;" onchange="this.form.submit()">
                        <option value="">Toutes les spécialités</option>
                        <?php foreach ($specialites as $spec): ?>
                            <option value="<?= htmlspecialchars($spec) ?>" <?= $specialite_filter === $spec ? 'selected' : '' ?>>
                                <?= htmlspecialchars($spec) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <select class="form-select form-select-sm" name="module" style="min-width: 180px;" onchange="this.form.submit()">
                        <option value="">Toutes les matières</option>
                        <?php foreach ($all_modules as $mod): ?>
                            <option value="<?= $mod['id_module'] ?>" <?= $module_filter == $mod['id_module'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($mod['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <a href="gestiondesenseignants.php" class="btn btn-outline-secondary d-flex align-items-center gap-2 btn-sm" title="Réinitialiser les filtres">
                        <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
                    </a>
                </div>
            </form>

            <div class="surface-card">
                <div class="table-responsive">
                    <table class="table table-suptech mb-0">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Enseignant</th>
                                <th>Grade</th>
                                <th>Spécialité</th>
                                <th>Matières (Modules)</th>
                                <th>Contact</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($enseignants)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Aucun enseignant trouvé.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($enseignants as $enseignant): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-light border text-dark fw-normal"><?= htmlspecialchars($enseignant['matricule']) ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-sm bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center rounded-circle fw-bold"> 
                                                    <?= getInitials($enseignant['prenom'], $enseignant['nom']) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($enseignant['nom']) ?>, <?= htmlspecialchars($enseignant['prenom']) ?></div>
                                                    <div class="small text-muted"><?= htmlspecialchars($enseignant['email']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info border border-info-subtle text-uppercase px-2 py-1" style="font-size: 0.7rem;">
                                                <?= htmlspecialchars($enseignant['grade']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($enseignant['specialite']) ?></td>
                                        <td>
                                            <?php if (!empty($enseignant['matieres'])): ?>
                                                <div class="text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($enseignant['matieres']) ?>">
                                                    <?= htmlspecialchars($enseignant['matieres']) ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">Aucune</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined text-muted" style="font-size: 16px;">call</span>
                                                <span><?= htmlspecialchars($enseignant['telephone']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" title="Modifier" data-bs-toggle="modal" data-bs-target="#editTeacherModal<?= $enseignant['id_enseignant'] ?>">
                                                    <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                                </button>
                                                <form action="supprimer_enseignant.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ? Le compte utilisateur associé sera également supprimé.');" class="d-inline">
                                                    <input type="hidden" name="id_enseignant" value="<?= $enseignant['id_enseignant'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" title="Supprimer">
                                                        <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modale de modification pour cet enseignant -->
                                    <div class="modal fade" id="editTeacherModal<?= $enseignant['id_enseignant'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 shadow" style="border-radius: 0.75rem;">
                                                <div class="modal-header bg-light" style="border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                                                    <h5 class="modal-title d-flex align-items-center gap-2">
                                                        <span class="material-symbols-outlined">edit</span>
                                                        Modifier l'enseignant : <?= htmlspecialchars($enseignant['prenom'] . ' ' . $enseignant['nom']) ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <form action="modifier_enseignant.php" method="POST">
                                                    <input type="hidden" name="id_enseignant" value="<?= $enseignant['id_enseignant'] ?>">
                                                    <div class="modal-body p-4 text-start">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold small">Prénom</label>
                                                                <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($enseignant['prenom']) ?>" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold small">Nom</label>
                                                                <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($enseignant['nom']) ?>" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold small">Email</label>
                                                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($enseignant['email']) ?>" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold small">Téléphone</label>
                                                                <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($enseignant['telephone']) ?>" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold small">Grade</label>
                                                                <input type="text" class="form-control" name="grade" value="<?= htmlspecialchars($enseignant['grade']) ?>" placeholder="Ex: Docteur, Professeur..." required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold small">Spécialité</label>
                                                                <input type="text" class="form-control" name="specialite" value="<?= htmlspecialchars($enseignant['specialite']) ?>" placeholder="Ex: Informatique" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light p-3" style="border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem;">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                                                            <span class="material-symbols-outlined" style="font-size: 20px;">save</span>
                                                            Enregistrer les modifications
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="border-top p-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 bg-light">
                    <span class="text-muted small">Affichage de <?= count($enseignants) ?> enseignant(s)</span>
                </div>
            </div>
        </main>
    </div>

    <!-- Modale d'ajout d'enseignant -->
    <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius: 0.75rem;">
                <div class="modal-header bg-primary text-white" style="border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined">person_add</span>
                        Ajouter un nouvel enseignant
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="ajouter_enseignant.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="alert alert-info d-flex align-items-center gap-2 mb-4" role="alert">
                            <span class="material-symbols-outlined">info</span>
                            <div>
                                Le matricule sera généré automatiquement (Prochain prévu: <strong><?= htmlspecialchars($next_matricule_preview) ?></strong>).<br>
                                Un compte utilisateur avec le rôle "Enseignant" sera créé. Le mot de passe par défaut sera <strong>Pass123!</strong>.
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Prénom</label>
                                <input type="text" class="form-control" name="prenom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Nom</label>
                                <input type="text" class="form-control" name="nom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Email personnel ou académique</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Téléphone</label>
                                <input type="text" class="form-control" name="telephone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Grade</label>
                                <input type="text" class="form-control" name="grade" placeholder="Ex: Docteur, Professeur..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Spécialité</label>
                                <input type="text" class="form-control" name="specialite" placeholder="Ex: Génie Logiciel" required>
                            </div>
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
</html>
