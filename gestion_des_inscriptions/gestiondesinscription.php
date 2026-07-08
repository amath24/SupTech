<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Gestion des Inscriptions';
$activePage = 'inscriptions';

$db = Database::getInstance();

// Filtres
$id_annee_filter = $_GET['annee'] ?? '';
$id_classe_filter = $_GET['classe'] ?? '';
$search = $_GET['search'] ?? '';

$page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$limit = 10;
$offset = ($page - 1) * $limit;

// Construction de la requête principale
$queryStr = "SELECT i.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.matricule as etudiant_matricule, e.photo, 
             c.nom as classe_nom, c.niveau as classe_niveau, 
             a.libelle as annee_libelle 
             FROM inscriptions i 
             JOIN etudiants e ON i.id_etudiant = e.id_etudiant 
             JOIN classes c ON i.id_classe = c.id_classe 
             JOIN annees_universitaires a ON i.id_annee = a.id_annee";

$countQueryStr = "SELECT COUNT(*) 
                  FROM inscriptions i 
                  JOIN etudiants e ON i.id_etudiant = e.id_etudiant 
                  JOIN classes c ON i.id_classe = c.id_classe 
                  JOIN annees_universitaires a ON i.id_annee = a.id_annee";

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
    $whereClauses[] = "i.id_classe = ?";
    $params[] = $id_classe_filter;
}

if (!empty($id_annee_filter)) {
    $whereClauses[] = "i.id_annee = ?";
    $params[] = $id_annee_filter;
}

if (!empty($whereClauses)) {
    $whereSql = " WHERE " . implode(" AND ", $whereClauses);
    $queryStr .= $whereSql;
    $countQueryStr .= $whereSql;
}

$queryStr .= " ORDER BY i.date_inscription DESC, e.nom ASC LIMIT $limit OFFSET $offset";

// Exécution
$stmt = $db->prepare($queryStr);
$stmt->execute($params);
$inscriptions = $stmt->fetchAll();

$stmtCount = $db->prepare($countQueryStr);
$stmtCount->execute($params);
$totalInscriptions = $stmtCount->fetchColumn();
$totalPages = ceil($totalInscriptions / $limit);

// Récupération des données pour les filtres et le formulaire
$classes = $db->query("SELECT id_classe, nom, niveau, filiere FROM classes ORDER BY niveau, nom")->fetchAll();
$annees = $db->query("SELECT id_annee, libelle FROM annees_universitaires ORDER BY date_debut DESC")->fetchAll();
$etudiants = $db->query("SELECT id_etudiant, nom, prenom, matricule FROM etudiants ORDER BY nom, prenom")->fetchAll();

$defaultPhoto = 'https://ui-avatars.com/api/?background=random&color=fff&name=';

// Sélectionner par défaut la première année scolaire (la plus récente)
$defaultAnnee = !empty($annees) ? $annees[0]['id_annee'] : '';

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
                <h2 class="page-title" style="font-size: 1.5rem;">Gestion des Inscriptions</h2>
                <p class="page-subtitle mb-0">Consultez et gérez les inscriptions académiques des étudiants.</p>
            </div>
            <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addInscriptionModal">
                <span class="material-symbols-outlined" style="font-size: 18px;">how_to_reg</span>
                Nouvelle Inscription
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
        <form method="GET" action="gestiondesinscription.php" class="filter-bar d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between mb-4">
            <div class="position-relative flex-grow-1" style="max-width: 350px;">
                <span class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 20px;">search</span>
                <input type="text" name="search" class="form-control ps-5" placeholder="Rechercher étudiant..." value="<?= htmlspecialchars($search) ?>" onchange="this.form.submit()">
            </div>
            <div class="d-flex gap-2 w-100 w-lg-auto flex-wrap">
                <select name="annee" class="form-select form-select-sm" style="min-width: 150px;" onchange="this.form.submit()">
                    <option value="">Toutes les années</option>
                    <?php foreach ($annees as $a): ?>
                        <option value="<?= $a['id_annee'] ?>" <?= $id_annee_filter == $a['id_annee'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
                <?php if(!empty($search) || !empty($id_classe_filter) || !empty($id_annee_filter)): ?>
                    <a href="gestiondesinscription.php" class="btn btn-outline-secondary d-flex align-items-center gap-2" title="Réinitialiser">
                        <span class="material-symbols-outlined" style="font-size: 20px;">clear</span>
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Tableau des inscriptions -->
        <div class="surface-card">
            <div class="table-responsive">
                <table class="table table-suptech mb-0">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Matricule</th>
                            <th>Classe</th>
                            <th>Année</th>
                            <th>Date Inscription</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inscriptions)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <span class="material-symbols-outlined d-block mb-2" style="font-size: 48px; opacity: 0.5;">assignment_late</span>
                                    Aucune inscription trouvée.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inscriptions as $insc): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php
                                        $photo = $insc['photo'];
                                        if(empty($photo)) {
                                            $photoSrc = $defaultPhoto . urlencode($insc['etudiant_prenom'] . ' ' . $insc['etudiant_nom']);
                                        } else if (strpos($photo, 'http') === 0) {
                                            $photoSrc = $photo;
                                        } else {
                                            $photoSrc = url($photo);
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($photoSrc) ?>"
                                             alt="Photo"
                                             style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                        <span class="fw-semibold"><?= htmlspecialchars($insc['etudiant_prenom'] . ' ' . $insc['etudiant_nom']) ?></span>
                                    </div>
                                </td>
                                <td><span class="text-muted"><?= htmlspecialchars($insc['etudiant_matricule']) ?></span></td>
                                <td>
                                    <span class="badge-classe" style="background: rgba(0, 35, 111, 0.1); color: var(--suptech-primary); border: 1px solid rgba(0, 35, 111, 0.2); padding: 0.25rem 0.625rem; border-radius: 50rem; font-size: 0.75rem; font-weight: 600;">
                                        <?= htmlspecialchars($insc['classe_nom']) ?>
                                    </span>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($insc['annee_libelle']) ?></span></td>
                                <td><?= date('d/m/Y', strtotime($insc['date_inscription'])) ?></td>
                                <td class="text-end">
                                    <div class="table-actions d-flex justify-content-end gap-1">
                                        <a href="imprimer_recu_inscription.php?id=<?= $insc['id_inscription'] ?>" target="_blank" class="btn btn-sm btn-link text-primary p-1" title="Imprimer l'attestation">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">print</span>
                                        </a>
                                        <a href="imprimer_recu_inscription.php?id=<?= $insc['id_inscription'] ?>&download=1" class="btn btn-sm btn-link text-success p-1" title="Télécharger l'attestation">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">download</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-link text-muted p-1" title="Voir le dossier" onclick="alert('Détails du dossier')">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">folder_open</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-link text-danger p-1" title="Annuler l'inscription" onclick="alert('Annulation à venir')">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">cancel</span>
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
                    Affichage <?= $offset + 1 ?> à <?= min($offset + $limit, $totalInscriptions) ?> sur <?= $totalInscriptions ?> inscriptions
                </span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&classe=<?= urlencode($id_classe_filter) ?>&annee=<?= urlencode($id_annee_filter) ?>">Précédent</a>
                        </li>
                        
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&classe=<?= urlencode($id_classe_filter) ?>&annee=<?= urlencode($id_annee_filter) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&classe=<?= urlencode($id_classe_filter) ?>&annee=<?= urlencode($id_annee_filter) ?>">Suivant</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modale Nouvelle Inscription -->
<div class="modal fade" id="addInscriptionModal" tabindex="-1" aria-labelledby="addInscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.75rem;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                <h5 class="modal-title d-flex align-items-center gap-2" id="addInscriptionModalLabel">
                    <span class="material-symbols-outlined">how_to_reg</span>
                    Saisie d'une Inscription
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="<?= url('gestion_des_inscriptions/ajouter_inscription.php') ?>" method="POST">
                <div class="modal-body p-4">
                    
                    <div class="alert alert-info d-flex gap-2 mb-4" role="alert">
                        <span class="material-symbols-outlined">info</span>
                        <div class="small">
                            L'inscription mettra à jour la classe actuelle de l'étudiant dans sa fiche.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="id_etudiant" class="form-label fw-semibold small">Étudiant</label>
                        <select class="form-select" id="id_etudiant" name="id_etudiant" required>
                            <option value="">Sélectionner un étudiant...</option>
                            <?php foreach ($etudiants as $e): ?>
                                <option value="<?= $e['id_etudiant'] ?>">
                                    <?= htmlspecialchars($e['matricule'] . ' - ' . $e['prenom'] . ' ' . $e['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="id_classe" class="form-label fw-semibold small">Classe</label>
                        <select class="form-select" id="id_classe" name="id_classe" required>
                            <option value="">Sélectionner la classe...</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id_classe'] ?>">
                                    <?= htmlspecialchars($c['nom'] . ' (' . $c['filiere'] . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="id_annee" class="form-label fw-semibold small">Année Universitaire</label>
                        <select class="form-select" id="id_annee" name="id_annee" required>
                            <option value="">Sélectionner l'année...</option>
                            <?php foreach ($annees as $a): ?>
                                <option value="<?= $a['id_annee'] ?>" <?= $defaultAnnee == $a['id_annee'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($a['libelle']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer bg-light p-3" style="border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">save</span>
                        Enregistrer l'inscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
</body>
