<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Gestion des Paiements';
$activePage = 'paiements';

$db = Database::getInstance();

// Frais de scolarité fixes (comme demandé, en dur dans le script)
$frais_scolarite = 500000;

// Filtres
$id_inscription_filter = $_GET['inscription'] ?? '';
$id_classe_filter = $_GET['classe'] ?? '';

// Pagination
$page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$limit = 15;
$offset = ($page - 1) * $limit;

// Récupération de toutes les classes pour le filtre
$classesList = $db->query("SELECT id_classe, nom, niveau FROM classes ORDER BY niveau, nom")->fetchAll();

// 1. Récupérer toutes les inscriptions pour le menu déroulant (Filtre et Modale)
$inscriptionsListQuery = "
    SELECT i.id_inscription, e.nom, e.prenom, e.matricule, c.nom as classe_nom, a.libelle as annee_libelle 
    FROM inscriptions i
    JOIN etudiants e ON i.id_etudiant = e.id_etudiant
    JOIN classes c ON i.id_classe = c.id_classe
    JOIN annees_universitaires a ON i.id_annee = a.id_annee
";
$inscriptionsParams = [];
if (!empty($id_classe_filter)) {
    $inscriptionsListQuery .= " WHERE i.id_classe = ?";
    $inscriptionsParams[] = $id_classe_filter;
}
$inscriptionsListQuery .= " ORDER BY a.date_debut DESC, e.nom ASC";

$stmtInsc = $db->prepare($inscriptionsListQuery);
$stmtInsc->execute($inscriptionsParams);
$inscriptionsList = $stmtInsc->fetchAll();

// 2. Statistiques et données selon qu'un étudiant (inscription) est sélectionné ou non
$total_paye_global = 0;
$stats_etudiant = null;

if (!empty($id_inscription_filter)) {
    // Calculer les stats de l'étudiant sélectionné
    $stmtStats = $db->prepare("SELECT SUM(montant_paye) as total_paye FROM paiements WHERE id_inscription = ?");
    $stmtStats->execute([$id_inscription_filter]);
    $total_paye_etudiant = (float)$stmtStats->fetchColumn();
    
    // Récupérer les infos de l'étudiant pour l'en-tête
    $stmtInfo = $db->prepare("
        SELECT e.nom, e.prenom, e.matricule, c.nom as classe_nom, a.libelle as annee_libelle 
        FROM inscriptions i
        JOIN etudiants e ON i.id_etudiant = e.id_etudiant
        JOIN classes c ON i.id_classe = c.id_classe
        JOIN annees_universitaires a ON i.id_annee = a.id_annee
        WHERE i.id_inscription = ?
    ");
    $stmtInfo->execute([$id_inscription_filter]);
    $info = $stmtInfo->fetch();

    $reste_a_payer = max(0, $frais_scolarite - $total_paye_etudiant);
    $pourcentage = ($total_paye_etudiant / $frais_scolarite) * 100;
    if ($pourcentage > 100) $pourcentage = 100;

    $stats_etudiant = [
        'info' => $info,
        'scolarite' => $frais_scolarite,
        'paye' => $total_paye_etudiant,
        'reste' => $reste_a_payer,
        'pourcentage' => $pourcentage
    ];

    // Requête pour la liste (seulement l'étudiant)
    $queryStr = "
        SELECT p.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.matricule, c.nom as classe_nom 
        FROM paiements p
        JOIN inscriptions i ON p.id_inscription = i.id_inscription
        JOIN etudiants e ON i.id_etudiant = e.id_etudiant
        JOIN classes c ON i.id_classe = c.id_classe
        WHERE p.id_inscription = ?
        ORDER BY p.date_paiement DESC, p.id_paiement DESC
        LIMIT $limit OFFSET $offset
    ";
    $stmtList = $db->prepare($queryStr);
    $stmtList->execute([$id_inscription_filter]);
    $paiements = $stmtList->fetchAll();

    $stmtCount = $db->prepare("SELECT COUNT(*) FROM paiements WHERE id_inscription = ?");
    $stmtCount->execute([$id_inscription_filter]);
    $totalPaiements = $stmtCount->fetchColumn();

} else {
    // Vue globale
    $globalParams = [];
    $whereClause = "";
    $joinClause = "";
    
    if (!empty($id_classe_filter)) {
        $joinClause = "JOIN inscriptions i2 ON paiements.id_inscription = i2.id_inscription";
        $whereClause = "WHERE i2.id_classe = ?";
        $globalParams[] = $id_classe_filter;
    }
    
    // Calculer le total global encaissé
    $stmtTotal = $db->prepare("SELECT SUM(montant_paye) FROM paiements $joinClause $whereClause");
    $stmtTotal->execute($globalParams);
    $total_paye_global = (float)$stmtTotal->fetchColumn();

    // Requête pour la liste globale
    $queryStr = "
        SELECT p.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.matricule, c.nom as classe_nom 
        FROM paiements p
        JOIN inscriptions i ON p.id_inscription = i.id_inscription
        JOIN etudiants e ON i.id_etudiant = e.id_etudiant
        JOIN classes c ON i.id_classe = c.id_classe
    ";
    
    if (!empty($id_classe_filter)) {
        $queryStr .= " WHERE i.id_classe = ?";
    }
    
    $queryStr .= " ORDER BY p.date_paiement DESC, p.id_paiement DESC LIMIT $limit OFFSET $offset";
    
    $stmtGlobalList = $db->prepare($queryStr);
    $stmtGlobalList->execute($globalParams);
    $paiements = $stmtGlobalList->fetchAll();
    
    $stmtGlobalCount = $db->prepare("SELECT COUNT(*) FROM paiements p JOIN inscriptions i ON p.id_inscription = i.id_inscription" . (!empty($id_classe_filter) ? " WHERE i.id_classe = ?" : ""));
    $stmtGlobalCount->execute($globalParams);
    $totalPaiements = $stmtGlobalCount->fetchColumn();
}

$totalPages = ceil($totalPaiements / $limit);

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
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

        <!-- En-tête et Filtre -->
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <?php if ($stats_etudiant): ?>
                    <h2 class="page-title"><?= htmlspecialchars($stats_etudiant['info']['prenom'] . ' ' . $stats_etudiant['info']['nom']) ?></h2>
                    <p class="page-subtitle mb-0 d-flex flex-wrap align-items-center gap-2">
                        <span class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 16px;">badge</span>
                            Matricule: <?= htmlspecialchars($stats_etudiant['info']['matricule']) ?>
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 16px;">school</span>
                            <?= htmlspecialchars($stats_etudiant['info']['classe_nom'] . ' (' . $stats_etudiant['info']['annee_libelle'] . ')') ?>
                        </span>
                    </p>
                <?php else: ?>
                    <h2 class="page-title" style="font-size: 1.5rem;">Gestion des Paiements</h2>
                    <p class="page-subtitle mb-0">Consultez l'historique global ou recherchez un étudiant.</p>
                <?php endif; ?>
            </div>
            
            <div class="d-flex flex-column flex-sm-row gap-2">
                <form method="GET" action="gestiondespaiements.php" class="d-flex flex-column flex-md-row gap-2">
                    <select name="classe" class="form-select" style="min-width: 180px;" onchange="this.form.submit()">
                        <option value="">-- Toutes les classes --</option>
                        <?php foreach ($classesList as $c): ?>
                            <option value="<?= $c['id_classe'] ?>" <?= $id_classe_filter == $c['id_classe'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="inscription" class="form-select" style="min-width: 250px;" onchange="this.form.submit()">
                        <option value="">-- Tous les étudiants --</option>
                        <?php foreach ($inscriptionsList as $insc): ?>
                            <option value="<?= $insc['id_inscription'] ?>" <?= $id_inscription_filter == $insc['id_inscription'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($insc['matricule'] . ' - ' . $insc['prenom'] . ' ' . $insc['nom'] . ' (' . $insc['annee_libelle'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if(!empty($id_inscription_filter) || !empty($id_classe_filter)): ?>
                        <a href="gestiondespaiements.php" class="btn btn-outline-secondary d-flex align-items-center" title="Réinitialiser">
                            <span class="material-symbols-outlined" style="font-size: 20px;">clear</span>
                        </a>
                    <?php endif; ?>
                </form>
                <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2 text-nowrap" data-bs-toggle="modal" data-bs-target="#addPaiementModal">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span>
                    <span class="d-none d-md-inline">Nouveau paiement</span>
                    <span class="d-inline d-md-none">Nouveau</span>
                </button>
            </div>
        </div>

        <!-- Résumé financier -->
        <?php if ($stats_etudiant): ?>
            <!-- Vue Étudiant -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="payment-card p-4 rounded-3 shadow-sm bg-white border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 text-muted mb-0">Scolarité annuelle</h3>
                            <span class="material-symbols-outlined text-primary bg-primary-subtle p-2 rounded-circle">account_balance</span>
                        </div>
                        <p class="amount mb-0 fw-bold fs-4"><?= number_format($stats_etudiant['scolarite'], 0, ',', ' ') ?> <span class="fs-6 text-muted">FCFA</span></p>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="payment-card p-4 rounded-3 shadow-sm bg-white border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 text-muted mb-0">Montant payé</h3>
                            <span class="material-symbols-outlined text-success bg-success-subtle p-2 rounded-circle">check_circle</span>
                        </div>
                        <p class="amount text-success mb-0 fw-bold fs-4"><?= number_format($stats_etudiant['paye'], 0, ',', ' ') ?> <span class="fs-6">FCFA</span></p>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?= $stats_etudiant['pourcentage'] ?>%"></div>
                        </div>
                        <p class="text-muted small mt-2 mb-0"><?= round($stats_etudiant['pourcentage'], 1) ?>% réglé</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="payment-card p-4 rounded-3 shadow-sm bg-white border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 text-muted mb-0">Reste à payer</h3>
                            <span class="material-symbols-outlined p-2 rounded-circle" style="color: #ff8f4f; background: rgba(255, 143, 79, 0.1);">pending_actions</span>
                        </div>
                        <p class="amount mb-0 fw-bold fs-4" style="color: #ff8f4f;"><?= number_format($stats_etudiant['reste'], 0, ',', ' ') ?> <span class="fs-6">FCFA</span></p>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar" style="width: <?= 100 - $stats_etudiant['pourcentage'] ?>%; background: #ff8f4f;"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Vue Globale -->
            <div class="row mb-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="payment-card p-4 rounded-3 shadow-sm bg-white border position-relative overflow-hidden">
                        <div class="position-absolute top-0 end-0 p-3 opacity-25">
                            <span class="material-symbols-outlined" style="font-size: 80px;">payments</span>
                        </div>
                        <h3 class="h6 text-muted mb-3 position-relative z-1">Total des encaissements (Global)</h3>
                        <p class="amount text-success mb-0 fw-bold display-6 position-relative z-1">
                            <?= number_format($total_paye_global, 0, ',', ' ') ?> <span class="fs-5">FCFA</span>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Historique des paiements -->
        <div class="surface-card">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                <h3 class="h5 fw-semibold mb-0">Historique des paiements</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-suptech mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <?php if(empty($id_inscription_filter)): ?>
                            <th>Étudiant</th>
                            <th>Classe</th>
                            <?php endif; ?>
                            <th>Référence / ID</th>
                            <th>Mode</th>
                            <th class="text-end">Montant</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($paiements)): ?>
                            <tr>
                                <td colspan="<?= empty($id_inscription_filter) ? 8 : 6 ?>" class="text-center py-5 text-muted">
                                    <span class="material-symbols-outlined d-block mb-2" style="font-size: 48px; opacity: 0.5;">receipt_long</span>
                                    Aucun paiement trouvé.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($paiements as $p): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($p['date_paiement'])) ?></td>
                                    
                                    <?php if(empty($id_inscription_filter)): ?>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($p['etudiant_prenom'] . ' ' . $p['etudiant_nom']) ?></div>
                                        <div class="small text-muted"><?= htmlspecialchars($p['matricule']) ?></div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['classe_nom']) ?></span></td>
                                    <?php endif; ?>
                                    
                                    <td class="text-primary fw-medium">PAY-<?= str_pad($p['id_paiement'], 5, '0', STR_PAD_LEFT) ?></td>
                                    <td>
                                        <span class="d-flex align-items-center gap-1">
                                            <?php
                                            $icon = 'payments';
                                            if ($p['mode_paiement'] == 'Especes') $icon = 'account_balance_wallet';
                                            if ($p['mode_paiement'] == 'Cheque') $icon = 'credit_card';
                                            if ($p['mode_paiement'] == 'Virement bancaire') $icon = 'account_balance';
                                            if ($p['mode_paiement'] == 'Orange Money' || $p['mode_paiement'] == 'Wave') $icon = 'phone_iphone';
                                            ?>
                                            <span class="material-symbols-outlined text-muted" style="font-size: 16px;"><?= $icon ?></span>
                                            <?= htmlspecialchars($p['mode_paiement']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold"><?= number_format($p['montant_paye'], 0, ',', ' ') ?> FCFA</td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1">Validé</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="imprimer_recu_paiement.php?id=<?= $p['id_paiement'] ?>" target="_blank" class="btn btn-sm btn-link text-primary p-1" title="Imprimer le reçu">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">print</span>
                                        </a>
                                        <a href="imprimer_recu_paiement.php?id=<?= $p['id_paiement'] ?>&download=1" class="btn btn-sm btn-link text-success p-1" title="Télécharger le reçu">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">download</span>
                                        </a>
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
                    Affichage <?= $offset + 1 ?> à <?= min($offset + $limit, $totalPaiements) ?> sur <?= $totalPaiements ?> paiements
                </span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&inscription=<?= urlencode($id_inscription_filter) ?>">Précédent</a>
                        </li>
                        
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&inscription=<?= urlencode($id_inscription_filter) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&inscription=<?= urlencode($id_inscription_filter) ?>">Suivant</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modale d'ajout de paiement -->
<div class="modal fade" id="addPaiementModal" tabindex="-1" aria-labelledby="addPaiementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 0.75rem;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                <h5 class="modal-title d-flex align-items-center gap-2" id="addPaiementModalLabel">
                    <span class="material-symbols-outlined">payments</span>
                    Enregistrer un paiement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="<?= url('gestion_des_paiements/ajouter_paiement.php') ?>" method="POST">
                <div class="modal-body p-4">
                    
                    <div class="mb-3">
                        <label for="id_inscription" class="form-label fw-semibold small">Étudiant (Inscription)</label>
                        <select class="form-select" id="id_inscription" name="id_inscription" required>
                            <option value="">Rechercher ou sélectionner...</option>
                            <?php foreach ($inscriptionsList as $insc): ?>
                                <option value="<?= $insc['id_inscription'] ?>" <?= $id_inscription_filter == $insc['id_inscription'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($insc['matricule'] . ' - ' . $insc['prenom'] . ' ' . $insc['nom'] . ' (' . $insc['annee_libelle'] . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="montant_paye" class="form-label fw-semibold small">Montant (FCFA)</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="montant_paye" name="montant_paye" min="1" step="1" required placeholder="Ex: 50000">
                            <span class="input-group-text bg-light text-muted">FCFA</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mode_paiement" class="form-label fw-semibold small">Mode de paiement</label>
                        <select class="form-select" id="mode_paiement" name="mode_paiement" required>
                            <option value="">Sélectionner...</option>
                            <option value="Especes">Espèces</option>
                            <option value="Orange Money">Orange Money</option>
                            <option value="Wave">Wave</option>
                            <option value="Virement bancaire">Virement bancaire</option>
                            <option value="Cheque">Chèque</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date_paiement" class="form-label fw-semibold small">Date du paiement</label>
                        <input type="date" class="form-control" id="date_paiement" name="date_paiement" value="<?= date('Y-m-d') ?>" required>
                    </div>

                </div>
                <div class="modal-footer bg-light p-3" style="border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">check_circle</span>
                        Valider le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
</body>
