<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Bulletins de Notes';
$activePage = 'notes';

$db = Database::getInstance();

// Filtres
$id_classe_filter = isset($_GET['classe']) ? (int)$_GET['classe'] : 0;
$search = $_GET['search'] ?? '';

// Récupération de l'année en cours (dernière)
$anneeQuery = $db->query("SELECT id_annee, libelle FROM annees_universitaires ORDER BY date_debut DESC LIMIT 1");
$annee = $anneeQuery->fetch();
$id_annee_courante = $annee ? (int)$annee['id_annee'] : 0;

// Récupération des classes pour le filtre
$classes = $db->query("SELECT id_classe, nom, niveau, filiere FROM classes ORDER BY niveau, nom")->fetchAll();

if ($id_classe_filter === 0 && !empty($classes)) {
    // Sélectionner la première classe par défaut
    $id_classe_filter = (int)$classes[0]['id_classe'];
}

// Construction de la requête pour les étudiants
$queryStr = "SELECT e.id_etudiant, e.nom, e.prenom, e.matricule, e.photo, c.nom as classe_nom 
             FROM inscriptions i 
             JOIN etudiants e ON i.id_etudiant = e.id_etudiant 
             JOIN classes c ON i.id_classe = c.id_classe 
             WHERE i.id_annee = :annee AND i.id_classe = :classe";

$params = [
    ':annee' => $id_annee_courante,
    ':classe' => $id_classe_filter
];

if (!empty($search)) {
    $queryStr .= " AND (e.nom LIKE :search OR e.prenom LIKE :search OR e.matricule LIKE :search)";
    $params[':search'] = "%$search%";
}

$queryStr .= " ORDER BY e.nom ASC";

$stmt = $db->prepare($queryStr);
$stmt->execute($params);
$etudiants = $stmt->fetchAll();

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
                <nav class="breadcrumb-custom">
                    <span class="material-symbols-outlined" style="font-size: 14px;">home</span>
                    <span>/</span>
                    <span>Notes</span>
                    <span>/</span>
                    <span class="active">Bulletins</span>
                </nav>
                <h2 class="page-title" style="font-size: 1.5rem;">Bulletins de Notes</h2>
                <p class="page-subtitle mb-0">Imprimez les relevés de notes des étudiants pour l'année <?= $annee ? htmlspecialchars($annee['libelle']) : '' ?>.</p>
            </div>
        </div>

        <!-- Barre de recherche / filtres -->
        <form method="GET" action="relvedenote.php" class="filter-bar d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between mb-4">
            <div class="position-relative flex-grow-1" style="max-width: 350px;">
                <span class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 20px;">search</span>
                <input type="text" name="search" class="form-control ps-5" placeholder="Rechercher étudiant..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="d-flex gap-2 w-100 w-lg-auto flex-wrap">
                <select name="classe" class="form-select" style="min-width: 250px;" onchange="this.form.submit()">
                    <option value="">-- Sélectionnez une classe --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id_classe'] ?>" <?= $id_classe_filter == $c['id_classe'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nom'] . ' (' . $c['filiere'] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-outline-suptech d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 20px;">filter_list</span>
                    <span class="d-none d-sm-inline">Filtrer</span>
                </button>
                <?php if ($id_classe_filter > 0): ?>
                <a href="generer_bulletins_classe.php?classe=<?= $id_classe_filter ?>&download=1" class="btn btn-primary-suptech d-flex align-items-center gap-2" title="Générer tous les bulletins de cette classe en un seul PDF">
                    <span class="material-symbols-outlined" style="font-size: 20px;">print</span>
                    <span class="d-none d-sm-inline">Tout imprimer (PDF)</span>
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
                            <th>Étudiant</th>
                            <th>Matricule</th>
                            <th>Classe</th>
                            <th class="text-end">Actions (Bulletin)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($etudiants)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <span class="material-symbols-outlined d-block mb-2" style="font-size: 48px; opacity: 0.5;">sentiment_dissatisfied</span>
                                    Aucun étudiant trouvé pour cette classe.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($etudiants as $e): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php
                                        $photo = $e['photo'];
                                        if(empty($photo)) {
                                            $photoSrc = $defaultPhoto . urlencode($e['prenom'] . ' ' . $e['nom']);
                                        } else if (strpos($photo, 'http') === 0) {
                                            $photoSrc = $photo;
                                        } else {
                                            $photoSrc = url($photo);
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($photoSrc) ?>"
                                             alt="Photo"
                                             style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                        <span class="fw-semibold"><?= htmlspecialchars($e['prenom'] . ' ' . $e['nom']) ?></span>
                                    </div>
                                </td>
                                <td><span class="text-muted"><?= htmlspecialchars($e['matricule']) ?></span></td>
                                <td>
                                    <span class="badge-classe" style="background: rgba(0, 35, 111, 0.1); color: var(--suptech-primary); border: 1px solid rgba(0, 35, 111, 0.2); padding: 0.25rem 0.625rem; border-radius: 50rem; font-size: 0.75rem; font-weight: 600;">
                                        <?= htmlspecialchars($e['classe_nom']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="table-actions d-flex justify-content-end gap-2">
                                        <a href="imprimer_releve_notes.php?id_etudiant=<?= $e['id_etudiant'] ?>" target="_blank" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" title="Voir le bulletin">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
                                            Voir
                                        </a>
                                        <a href="imprimer_releve_notes.php?id_etudiant=<?= $e['id_etudiant'] ?>&download=1" class="btn btn-sm btn-primary-suptech d-flex align-items-center gap-1" title="Télécharger le bulletin">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">download</span>
                                            Télécharger
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
</body>
</html>