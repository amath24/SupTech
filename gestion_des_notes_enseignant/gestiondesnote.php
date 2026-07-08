<?php
require_once __DIR__ . '/../include/config.php';

/*
 * Connexion PDO.
 * On réutilise $pdo si config.php le définit déjà (cas le plus courant).
 * Sinon on se connecte directement ici : adaptez host/dbname/user/pass
 * si besoin pour correspondre à votre environnement.
 */
if (!isset($pdo) || !($pdo instanceof PDO)) {
    $db_host = 'localhost';
    $db_name = 'gestion_academique';
    $db_user = 'root';
    $db_pass = '';
    try {
        $pdo = new PDO(
            "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
            $db_user,
            $db_pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (PDOException $e) {
        die('Connexion à la base de données impossible : ' . $e->getMessage());
    }
}

/**
 * Traduit la mention (calculée par la base) en classe CSS.
 */
function slug_mention(?string $mention): string
{
    if ($mention === null) {
        return 'incomplet';
    }
    $map = [
        'Tres bien'   => 'tres-bien',
        'Très bien'   => 'tres-bien',
        'Bien'        => 'bien',
        'Assez bien'  => 'assez-bien',
        'Passable'    => 'passable',
        'Insuffisant' => 'insuffisant',
    ];
    return $map[$mention] ?? 'passable';
}

/** Initiales Nom+Prénom pour l'avatar. */
function initiales(string $nom, string $prenom): string
{
    return mb_strtoupper(mb_substr($nom, 0, 1) . mb_substr($prenom, 0, 1));
}

// --------------------------------------------------------------------
// 1. Liste des assignations (module + classe + enseignant + année)
//    -> alimente le sélecteur permettant de choisir le cours à gérer
// --------------------------------------------------------------------
$user_role = strtolower($_SESSION['user_role'] ?? '');
$user_id = $_SESSION['user_id'] ?? 0;
$id_enseignant = 0;
if ($user_role === 'enseignant') {
    $stmt = $pdo->prepare("SELECT id_enseignant FROM enseignants WHERE id_utilisateur = ?");
    $stmt->execute([$user_id]);
    $id_enseignant = $stmt->fetchColumn() ?: 0;
}

$sql_assign = "
    SELECT a.id_assignation, m.nom AS module_nom, m.coefficient,
           c.id_classe, c.nom AS classe_nom, c.niveau,
           en.nom AS ens_nom, en.prenom AS ens_prenom,
           an.id_annee, an.libelle AS annee_libelle
    FROM assignation_modules a
    JOIN modules m            ON m.id_module = a.id_module
    JOIN classes c            ON c.id_classe = a.id_classe
    JOIN enseignants en       ON en.id_enseignant = a.id_enseignant
    JOIN annees_universitaires an ON an.id_annee = a.id_annee
";
$params_assign = [];
if ($user_role === 'enseignant') {
    $sql_assign .= " WHERE a.id_enseignant = ?";
    $params_assign[] = $id_enseignant;
}
$sql_assign .= " ORDER BY c.nom, m.nom";

$stmt_assign = $pdo->prepare($sql_assign);
$stmt_assign->execute($params_assign);
$assignations = $stmt_assign->fetchAll();

if (empty($assignations)) {
    die("Aucune assignation de module n'a été trouvée (table assignation_modules vide). Créez d'abord une assignation module/classe/enseignant/année.");
}

// Assignation actuellement sélectionnée (GET id_assignation, sinon la première)
$id_assignation = isset($_GET['id_assignation']) ? (int) $_GET['id_assignation'] : (int) $assignations[0]['id_assignation'];
$current = null;
foreach ($assignations as $a) {
    if ((int) $a['id_assignation'] === $id_assignation) {
        $current = $a;
        break;
    }
}
if ($current === null) {
    $current = $assignations[0];
    $id_assignation = (int) $current['id_assignation'];
}

// --------------------------------------------------------------------
// 2. Enregistrement des notes (POST)
// --------------------------------------------------------------------
$message = null;
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_notes') {
    $posted_id_assignation = (int) ($_POST['id_assignation'] ?? 0);
    $rows = $_POST['notes'] ?? [];
    $saved = 0;
    $incomplete = 0;
    $invalid = 0;

    // cc/tp/examen sont NOT NULL dans la table `notes` : les 3 doivent être
    // fournies ensemble pour pouvoir enregistrer la ligne d'un étudiant.
    $sql = "INSERT INTO notes (cc, tp, examen, id_etudiant, id_assignation)
            VALUES (:cc, :tp, :examen, :id_etudiant, :id_assignation)
            ON DUPLICATE KEY UPDATE cc = :cc_u, tp = :tp_u, examen = :examen_u";
    $stmt = $pdo->prepare($sql);

    foreach ($rows as $id_etudiant => $vals) {
        $id_etudiant = (int) $id_etudiant;
        $cc = trim((string) ($vals['cc'] ?? ''));
        $tp = trim((string) ($vals['tp'] ?? ''));
        $ex = trim((string) ($vals['examen'] ?? ''));

        if ($cc === '' && $tp === '' && $ex === '') {
            continue; // ligne non modifiée, on l'ignore
        }
        if ($cc === '' || $tp === '' || $ex === '') {
            $incomplete++;
            continue;
        }
        if (
            !is_numeric($cc) || !is_numeric($tp) || !is_numeric($ex)
            || $cc < 0 || $cc > 20 || $tp < 0 || $tp > 20 || $ex < 0 || $ex > 20
        ) {
            $invalid++;
            continue;
        }

        $stmt->execute([
            ':cc' => $cc,
            ':tp' => $tp,
            ':examen' => $ex,
            ':id_etudiant' => $id_etudiant,
            ':id_assignation' => $posted_id_assignation,
            ':cc_u' => $cc,
            ':tp_u' => $tp,
            ':examen_u' => $ex,
        ]);
        $saved++;
    }

    $params = ['id_assignation' => $posted_id_assignation, 'saved' => $saved];
    if ($incomplete > 0) {
        $params['incomplete'] = $incomplete;
    }
    if ($invalid > 0) {
        $params['invalid'] = $invalid;
    }
    header('Location: gestiondesnote.php?' . http_build_query($params));
    exit;
}

if (isset($_GET['saved'])) {
    $message = (int) $_GET['saved'] . ' note(s) enregistrée(s) avec succès.';
    if (!empty($_GET['incomplete'])) {
        $message .= ' ' . (int) $_GET['incomplete'] . ' ligne(s) ignorée(s) : CC, TP et Examen doivent être tous renseignés.';
        $messageType = 'warning';
    }
    if (!empty($_GET['invalid'])) {
        $message .= ' ' . (int) $_GET['invalid'] . ' valeur(s) invalide(s) (doit être comprise entre 0 et 20).';
        $messageType = 'warning';
    }
}

// --------------------------------------------------------------------
// 3. Étudiants inscrits (via `inscriptions`) dans la classe de cette
//    assignation pour cette année, avec leur note pour ce module
// --------------------------------------------------------------------
$stmtEtu = $pdo->prepare("
    SELECT e.id_etudiant, e.matricule, e.nom, e.prenom,
           n.id_note, n.cc, n.tp, n.examen, n.moyenne, n.mention
    FROM inscriptions i
    JOIN etudiants e ON e.id_etudiant = i.id_etudiant
    LEFT JOIN notes n ON n.id_etudiant = e.id_etudiant AND n.id_assignation = :id_assignation
    WHERE i.id_classe = :id_classe AND i.id_annee = :id_annee
    ORDER BY e.nom, e.prenom
");
$stmtEtu->execute([
    ':id_assignation' => $id_assignation,
    ':id_classe' => $current['id_classe'],
    ':id_annee' => $current['id_annee'],
]);
$etudiants = $stmtEtu->fetchAll();

// --------------------------------------------------------------------
// 4. Classement par moyenne décroissante (seulement les copies complètes)
// --------------------------------------------------------------------
$ranked = array_values(array_filter($etudiants, fn($e) => $e['moyenne'] !== null));
usort($ranked, fn($a, $b) => $b['moyenne'] <=> $a['moyenne']);
$rangParEtudiant = [];
$rangCourant = 0;
$moyennePrecedente = null;
foreach ($ranked as $i => $e) {
    if ($moyennePrecedente === null || (float) $e['moyenne'] !== (float) $moyennePrecedente) {
        $rangCourant = $i + 1;
    }
    $rangParEtudiant[$e['id_etudiant']] = $rangCourant;
    $moyennePrecedente = $e['moyenne'];
}

// --------------------------------------------------------------------
// 5. Statistiques pour les cartes résumé
// --------------------------------------------------------------------
$effectif = count($etudiants);
$saisies = count($ranked);
$moyenneClasse = $saisies > 0 ? array_sum(array_column($ranked, 'moyenne')) / $saisies : null;
$tauxSaisie = $effectif > 0 ? round($saisies / $effectif * 100) : 0;

// --------------------------------------------------------------------
// 6. Export CSV (bouton "Exporter")
// --------------------------------------------------------------------
if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="notes_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $current['module_nom']) . '.csv"');
    $out = fopen('php://output', 'w');
    fputs($out, "\xEF\xBB\xBF"); // BOM pour un affichage correct des accents dans Excel
    fputcsv($out, ['Matricule', 'Nom', 'Prenom', 'CC', 'TP', 'Examen', 'Moyenne', 'Mention']);
    foreach ($etudiants as $e) {
        fputcsv($out, [
            $e['matricule'],
            $e['nom'],
            $e['prenom'],
            $e['cc'] ?? '',
            $e['tp'] ?? '',
            $e['examen'] ?? '',
            $e['moyenne'] ?? '',
            $e['mention'] ?? 'Incomplet',
        ]);
    }
    fclose($out);
    exit;
}

// --------------------------------------------------------------------
// 7. Pagination
// --------------------------------------------------------------------
$parPage = 15;
$totalPages = max(1, (int) ceil($effectif / $parPage));
$page = isset($_GET['page']) ? max(1, min($totalPages, (int) $_GET['page'])) : 1;
$etudiantsPage = array_slice($etudiants, ($page - 1) * $parPage, $parPage);

$pageTitle = 'Gestion des notes';
$activePage = 'notes';
$userName = 'Profil Utilisateur';
$userRole = 'Admin';
$userInitials = 'AD';

require_once __DIR__ . '/../include/head.php';
?>

<body>
    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../include/header.php'; ?>

        <main class="main-content">
            <!-- En-tête -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
                <div>
                    <nav class="breadcrumb-custom">
                        <span class="material-symbols-outlined" style="font-size: 14px;">home</span>
                        <span>/</span>
                        <span>Notes</span>
                        <span>/</span>
                        <span class="active">Saisie</span>
                    </nav>
                    <h2 class="page-title" style="font-size: 1.5rem;">Saisie des notes : <?= htmlspecialchars($current['module_nom']) ?></h2>
                    <p class="page-subtitle mb-0"><?= htmlspecialchars($current['classe_nom']) ?> (<?= htmlspecialchars($current['niveau']) ?>) - <?= htmlspecialchars($current['annee_libelle']) ?> | Prof. <?= htmlspecialchars($current['ens_prenom'] . ' ' . $current['ens_nom']) ?></p>
                </div>
                <div class="d-flex gap-2 flex-wrap align-items-end">
                    <form method="get" class="mb-0">
                        <select name="id_assignation" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 260px;">
                            <?php foreach ($assignations as $a): ?>
                                <option value="<?= (int) $a['id_assignation'] ?>" <?= (int) $a['id_assignation'] === $id_assignation ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($a['module_nom'] . ' - ' . $a['classe_nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <a href="../relev_de_notes/relvedenote.php" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 18px;">description</span>
                        Bulletins de notes
                    </a>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'warning' ?> py-2"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <!-- Cartes résumé -->
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="summary-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h3 class="h6 fw-semibold mb-0">Effectif</h3>
                            <span class="summary-card-icon bg-primary-subtle text-primary">
                                <span class="material-symbols-outlined">groups</span>
                            </span>
                        </div>
                        <div class="stat-value"><?= $effectif ?></div>
                        <p class="text-muted small mb-0">Étudiants inscrits</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="summary-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h3 class="h6 fw-semibold mb-0">Saisies</h3>
                            <span class="summary-card-icon bg-success-subtle text-success">
                                <span class="material-symbols-outlined">check_circle</span>
                            </span>
                        </div>
                        <div class="stat-value"><?= $saisies ?><span class="fs-5 text-muted">/<?= $effectif ?></span></div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: <?= $tauxSaisie ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="summary-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h3 class="h6 fw-semibold mb-0">Moyenne Classe</h3>
                            <span class="summary-card-icon" style="background: var(--suptech-tertiary-fixed); color: #ff8f4f;">
                                <span class="material-symbols-outlined">analytics</span>
                            </span>
                        </div>
                        <div class="stat-value"><?= $moyenneClasse !== null ? number_format((float) $moyenneClasse, 2) : '--' ?></div>
                        <p class="text-muted small mb-0">Calculée sur les copies déjà saisies</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="summary-card info-card">
                        <h3 class="h6 fw-semibold mb-2">Pondération</h3>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="d-flex justify-content-between py-1"><span>Contrôle Continu (CC):</span> <span class="fw-medium text-dark">20%</span></li>
                            <li class="d-flex justify-content-between py-1"><span>Travaux Pratiques (TP):</span> <span class="fw-medium text-dark">30%</span></li>
                            <li class="d-flex justify-content-between py-1"><span>Examen Final:</span> <span class="fw-medium text-dark">50%</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <form id="notesForm" method="post">
                <input type="hidden" name="action" value="save_notes">
                <input type="hidden" name="id_assignation" value="<?= $id_assignation ?>">

                <!-- Tableau des notes -->
                <div class="surface-card d-flex flex-column" style="height: 500px;">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="position-relative" style="width: 280px;">
                            <span class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y ms-2 text-muted" style="font-size: 18px;">search</span>
                            <input type="text" id="searchInput" class="form-control form-control-sm ps-5" placeholder="Rechercher un étudiant...">
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted">Statut:</span>
                            <span class="badge rounded-pill bg-success-subtle text-success" style="font-size: 10px;">Brouillon</span>
                        </div>
                    </div>

                    <div class="table-responsive flex-grow-1 overflow-auto">
                        <table class="table table-suptech notes-table mb-0">
                            <thead class="sticky-top">
                                <tr>
                                    <th>Matricule</th>
                                    <th style="width: 25%;">Nom Étudiant</th>
                                    <th class="text-center">Note CC<br><small class="fw-normal text-muted">/20 (20%)</small></th>
                                    <th class="text-center">Note TP<br><small class="fw-normal text-muted">/20 (30%)</small></th>
                                    <th class="text-center">Examen<br><small class="fw-normal text-muted">/20 (50%)</small></th>
                                    <th class="text-center bg-light">Moyenne</th>
                                    <th class="text-center">Mention</th>
                                    <th class="text-center">Classement</th>
                                </tr>
                            </thead>
                            <tbody id="notesTableBody">
                                <?php if (empty($etudiantsPage)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Aucun étudiant inscrit dans cette classe pour cette année.</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($etudiantsPage as $e):
                                    $incomplet = $e['moyenne'] === null;
                                    $rang = $rangParEtudiant[$e['id_etudiant']] ?? null;
                                    $mClass = slug_mention($e['mention']);
                                    $mLabel = $e['mention'] ?? 'Incomplet';
                                ?>
                                    <tr class="<?= $incomplet ? 'row-incomplete' : '' ?>" data-id-etudiant="<?= (int) $e['id_etudiant'] ?>">
                                        <td class="text-muted"><?= htmlspecialchars($e['matricule']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="avatar-sm <?= $incomplet ? 'bg-light' : '' ?>"><?= htmlspecialchars(initiales($e['nom'], $e['prenom'])) ?></span>
                                                <span class="fw-medium"><?= htmlspecialchars($e['nom'] . ', ' . $e['prenom']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="notes[<?= (int) $e['id_etudiant'] ?>][cc]"
                                                class="form-control form-control-sm note-input mx-auto js-note js-cc <?= ($e['cc'] !== null && $e['cc'] < 10) ? 'warning' : '' ?>"
                                                min="0" max="20" step="0.5"
                                                value="<?= $e['cc'] !== null ? htmlspecialchars($e['cc']) : '' ?>"
                                                placeholder="<?= $incomplet ? '--' : '' ?>">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="notes[<?= (int) $e['id_etudiant'] ?>][tp]"
                                                class="form-control form-control-sm note-input mx-auto js-note js-tp <?= ($e['tp'] !== null && $e['tp'] < 10) ? 'warning' : '' ?>"
                                                min="0" max="20" step="0.5"
                                                value="<?= $e['tp'] !== null ? htmlspecialchars($e['tp']) : '' ?>"
                                                placeholder="<?= $incomplet ? '--' : '' ?>">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="notes[<?= (int) $e['id_etudiant'] ?>][examen]"
                                                class="form-control form-control-sm note-input mx-auto bg-light js-note js-examen <?= $incomplet ? 'error' : (($e['examen'] < 10) ? 'warning' : '') ?>"
                                                min="0" max="20" step="0.5"
                                                value="<?= $e['examen'] !== null ? htmlspecialchars($e['examen']) : '' ?>"
                                                placeholder="<?= $incomplet ? '--' : '' ?>">
                                        </td>
                                        <td class="text-center fw-bold bg-light moyenne-cell js-moyenne <?= $incomplet ? 'text-muted' : ((float) $e['moyenne'] >= 10 ? 'text-success' : 'text-danger') ?>">
                                            <?= $incomplet ? '--' : number_format((float) $e['moyenne'], 2) ?>
                                        </td>
                                        <td class="text-center mention-cell js-mention"><span class="badge-mention <?= $mClass ?>"><?= htmlspecialchars($mLabel) ?></span></td>
                                        <td class="text-center <?= ($rang !== null && $rang <= 3) ? 'fw-bold text-success' : 'text-muted' ?>"><?= $rang !== null ? $rang . '/' . $saisies : '--' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <span class="small text-muted">
                            Affichage <?= $effectif > 0 ? (($page - 1) * $parPage + 1) : 0 ?>-<?= min($page * $parPage, $effectif) ?> sur <?= $effectif ?> étudiants
                        </span>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?id_assignation=<?= $id_assignation ?>&page=<?= max(1, $page - 1) ?>"><span class="material-symbols-outlined" style="font-size: 16px;">chevron_left</span></a>
                                </li>
                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <li class="page-item <?= $p === $page ? 'active' : '' ?>"><a class="page-link" href="?id_assignation=<?= $id_assignation ?>&page=<?= $p ?>"><?= $p ?></a></li>
                                <?php endfor; ?>
                                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?id_assignation=<?= $id_assignation ?>&page=<?= min($totalPages, $page + 1) ?>"><span class="material-symbols-outlined" style="font-size: 16px;">chevron_right</span></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Actions finales -->
                <div class="d-flex justify-content-end gap-3 mt-4 pb-4">
                    <button type="button" class="btn btn-outline-suptech px-4" onclick="window.location='gestiondesnote.php?id_assignation=<?= $id_assignation ?>'">Annuler</button>
                    <button type="submit" class="btn btn-secondary-suptech d-flex align-items-center gap-2 px-4">
                        <span class="material-symbols-outlined">done_all</span>
                        Valider et publier les notes
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        document.getElementById('searchInput')?.addEventListener('input', function(ev) {
            var term = ev.target.value.trim().toLowerCase();
            document.querySelectorAll('#notesTableBody tr[data-id-etudiant]').forEach(function(tr) {
                tr.style.display = tr.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        function recalcRow(tr) {
            var cc = parseFloat(tr.querySelector('.js-cc').value);
            var tp = parseFloat(tr.querySelector('.js-tp').value);
            var ex = parseFloat(tr.querySelector('.js-examen').value);
            var cell = tr.querySelector('.js-moyenne');
            var mentionCell = tr.querySelector('.js-mention');
            if (isNaN(cc) || isNaN(tp) || isNaN(ex)) {
                cell.textContent = '--';
                return;
            }
            var moy = Math.round((cc * 0.2 + tp * 0.3 + ex * 0.5) * 100) / 100;
            cell.textContent = moy.toFixed(2);
            var label, cls;
            if (moy >= 16) {
                label = 'Tres bien';
                cls = 'tres-bien';
            } else if (moy >= 14) {
                label = 'Bien';
                cls = 'bien';
            } else if (moy >= 12) {
                label = 'Assez bien';
                cls = 'assez-bien';
            } else if (moy >= 10) {
                label = 'Passable';
                cls = 'passable';
            } else {
                label = 'Insuffisant';
                cls = 'insuffisant';
            }
            mentionCell.innerHTML = '<span class="badge-mention ' + cls + '">' + label + '</span>';
        }

        document.querySelectorAll('.js-note').forEach(function(input) {
            input.addEventListener('input', function() {
                recalcRow(input.closest('tr'));
            });
        });
    </script>

    <?php require_once __DIR__ . '/../include/footer.php'; ?>