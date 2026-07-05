<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Gestion des Étudiants';
$activePage = 'etudiants';

$userName = $_SESSION['user_nom'] ?? 'Utilisateur';
$userRole = $_SESSION['user_role'] ?? 'Rôle';
$userInitials = strtoupper(substr($_SESSION['user_nom'] ?? 'U', 0, 1));

$db = Database::getInstance();

// Filtres
$search = $_GET['search'] ?? '';
$id_classe_filter = $_GET['classe'] ?? '';
$page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$limit = 10;
$offset = ($page - 1) * $limit;

// Construction de la requête principale
$queryStr = "SELECT e.*, c.nom as classe_nom, c.niveau as classe_niveau FROM etudiants e LEFT JOIN classes c ON e.id_classe = c.id_classe";
$countQueryStr = "SELECT COUNT(*) FROM etudiants e";

$whereClauses = [];
$params = [];

if (!empty($search)) {
    $whereClauses[] = "(e.nom LIKE ? OR e.prenom LIKE ? OR e.matricule LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

if (!empty($id_classe_filter)) {
    $whereClauses[] = "e.id_classe = ?";
    $params[] = $id_classe_filter;
}

if (!empty($whereClauses)) {
    $whereSql = " WHERE " . implode(" AND ", $whereClauses);
    $queryStr .= $whereSql;
    $countQueryStr .= $whereSql;
}

$queryStr .= " ORDER BY e.nom, e.prenom LIMIT $limit OFFSET $offset";

// Exécution
$stmt = $db->prepare($queryStr);
$stmt->execute($params);
$etudiants = $stmt->fetchAll();

$stmtCount = $db->prepare($countQueryStr);
$stmtCount->execute($params);
$totalEtudiants = $stmtCount->fetchColumn();
$totalPages = ceil($totalEtudiants / $limit);

// Récupération des classes pour les filtres et le formulaire d'ajout
$classes = $db->query("SELECT id_classe, nom, niveau, filiere FROM classes ORDER BY niveau, nom")->fetchAll();

// Définir une image par défaut si la photo est vide
$defaultPhoto = 'https://ui-avatars.com/api/?background=random&color=fff&name=';

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <!-- En-tête -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="page-title" style="font-size: 1.5rem;">Gestion des Étudiants</h2>
                <p class="page-subtitle mb-0">Gérez la liste complète des étudiants inscrits pour l'année académique en cours.</p>
            </div>
            <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addEtudiantModal">
                <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
                Ajouter un étudiant
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

        <!-- Barre de recherche / filtres -->
        <form method="GET" action="gestiondesetudiant.php" class="filter-bar d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between mb-4">
            <div class="position-relative flex-grow-1" style="max-width: 400px;">
                <span class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 20px;">search</span>
                <input type="text" name="search" class="form-control ps-5" placeholder="Rechercher par nom, prénom, matricule..." value="<?= htmlspecialchars($search) ?>" onchange="this.form.submit()">
            </div>
            <div class="d-flex gap-2 w-100 w-md-auto">
                <select name="classe" class="form-select form-select-sm" style="min-width: 180px;" onchange="this.form.submit()">
                    <option value="">Toutes les classes</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id_classe'] ?>" <?= $id_classe_filter == $c['id_classe'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-outline-suptech d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 20px;">filter_list</span>
                    <span class="d-none d-sm-inline">Filtrer</span>
                </button>
                <?php if(!empty($search) || !empty($id_classe_filter)): ?>
                    <a href="gestiondesetudiant.php" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">clear</span>
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Tableau des étudiants -->
        <div class="surface-card">
            <div class="table-responsive">
                <table class="table table-suptech mb-0">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Classe</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($etudiants)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <span class="material-symbols-outlined d-block mb-2" style="font-size: 48px; opacity: 0.5;">person_off</span>
                                    Aucun étudiant trouvé.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($etudiants as $etudiant): ?>
                            <tr>
                                <td>
                                    <?php
                                    $photo = $etudiant['photo'];
                                    if(empty($photo)) {
                                        $photoSrc = $defaultPhoto . urlencode($etudiant['prenom'] . ' ' . $etudiant['nom']);
                                    } else if (strpos($photo, 'http') === 0) {
                                        $photoSrc = $photo;
                                    } else {
                                        $photoSrc = url($photo);
                                    }
                                    ?>
                                    <img src="<?= htmlspecialchars($photoSrc) ?>"
                                         alt="Photo <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?>"
                                         class="student-photo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                </td>
                                <td class="fw-medium"><?= htmlspecialchars($etudiant['matricule']) ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($etudiant['nom']) ?></td>
                                <td class="text-muted"><?= htmlspecialchars($etudiant['prenom']) ?></td>
                                <td>
                                    <?php if($etudiant['classe_nom']): ?>
                                        <span class="badge-classe" style="background: rgba(0, 35, 111, 0.1); color: var(--suptech-primary); border: 1px solid rgba(0, 35, 111, 0.2); padding: 0.25rem 0.625rem; border-radius: 50rem; font-size: 0.75rem; font-weight: 600;">
                                            <?= htmlspecialchars($etudiant['classe_nom']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Non assigné</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="table-actions d-flex justify-content-end gap-1">
                                        <button type="button" class="btn btn-sm btn-link text-muted p-1" title="Voir" onclick="alert('Détails à venir')">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-link text-muted p-1" title="Modifier" onclick="alert('Modification à venir')">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-link text-danger p-1" title="Supprimer" onclick="alert('Suppression à venir')">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="border-top p-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 bg-light">
                <span class="text-muted small">
                    Affichage <?= $offset + 1 ?> à <?= min($offset + $limit, $totalEtudiants) ?> sur <?= $totalEtudiants ?> étudiants
                </span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&classe=<?= urlencode($id_classe_filter) ?>">Précédent</a>
                        </li>
                        
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&classe=<?= urlencode($id_classe_filter) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&classe=<?= urlencode($id_classe_filter) ?>">Suivant</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modale d'ajout d'étudiant -->
<div class="modal fade" id="addEtudiantModal" tabindex="-1" aria-labelledby="addEtudiantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 0.75rem;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                <h5 class="modal-title d-flex align-items-center gap-2" id="addEtudiantModalLabel">
                    <span class="material-symbols-outlined">person_add</span>
                    Ajouter un nouvel étudiant
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="<?= url('gestion_des_etudiants/ajouter_etudiant.php') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label fw-semibold small">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label fw-semibold small">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="date_naissance" class="form-label fw-semibold small">Date de naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                        </div>
                        <div class="col-md-6">
                            <label for="sexe" class="form-label fw-semibold small">Sexe</label>
                            <select class="form-select" id="sexe" name="sexe" required>
                                <option value="">Sélectionner</option>
                                <option value="M">Masculin (M)</option>
                                <option value="F">Féminin (F)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="telephone" class="form-label fw-semibold small">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold small">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="adresse" class="form-label fw-semibold small">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="id_classe" class="form-label fw-semibold small">Classe</label>
                            <select class="form-select" id="id_classe" name="id_classe" required>
                                <option value="">Sélectionner une classe</option>
                                <?php foreach ($classes as $c): ?>
                                    <option value="<?= $c['id_classe'] ?>">
                                        <?= htmlspecialchars($c['nom'] . ' (' . $c['filiere'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Le matricule (SUP-YYYY-XXX) sera généré automatiquement.</div>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="photo" class="form-label fw-semibold small">Photo (Optionnelle)</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <div class="form-text">Formats acceptés : JPG, PNG, GIF. Taille max : 2Mo.</div>
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
