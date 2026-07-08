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

// --------------------------------------------------------------------
// 1. Année universitaire courante (la plus récente de la table)
// --------------------------------------------------------------------
$annee = $pdo->query("SELECT id_annee, libelle FROM annees_universitaires ORDER BY id_annee DESC LIMIT 1")->fetch();
if (!$annee) {
    die("Aucune année universitaire n'est configurée dans la table annees_universitaires.");
}

// --------------------------------------------------------------------
// 2. Liste des étudiants -> alimente le sélecteur "Étudiant"
// --------------------------------------------------------------------
$user_role = strtolower($_SESSION['user_role'] ?? '');
$user_id = $_SESSION['user_id'] ?? 0;
$id_enseignant = 0;
if ($user_role === 'enseignant') {
    $stmt = $pdo->prepare("SELECT id_enseignant FROM enseignants WHERE id_utilisateur = ?");
    $stmt->execute([$user_id]);
    $id_enseignant = $stmt->fetchColumn() ?: 0;
}

$sqlEtu = "
    SELECT e.id_etudiant, e.matricule, e.nom, e.prenom, c.nom AS classe_nom
    FROM etudiants e
    JOIN classes c ON c.id_classe = e.id_classe
";
$paramsEtu = [];

if ($user_role === 'enseignant') {
    $sqlEtu .= " WHERE e.id_classe IN (SELECT id_classe FROM assignation_modules WHERE id_enseignant = ?)";
    $paramsEtu[] = $id_enseignant;
}
$sqlEtu .= " ORDER BY e.nom, e.prenom";

$stmtEtu = $pdo->prepare($sqlEtu);
$stmtEtu->execute($paramsEtu);
$etudiantsListe = $stmtEtu->fetchAll();

if (empty($etudiantsListe)) {
    die("Aucun étudiant n'est enregistré dans la table etudiants.");
}

$id_etudiant = isset($_GET['id_etudiant']) ? (int) $_GET['id_etudiant'] : (int) $etudiantsListe[0]['id_etudiant'];
$etudiantCourant = null;
foreach ($etudiantsListe as $e) {
    if ((int) $e['id_etudiant'] === $id_etudiant) {
        $etudiantCourant = $e;
        break;
    }
}
if ($etudiantCourant === null) {
    $etudiantCourant = $etudiantsListe[0];
    $id_etudiant = (int) $etudiantCourant['id_etudiant'];
}

// --------------------------------------------------------------------
// 3. Classe de l'étudiant pour l'année en cours (via `inscriptions`,
//    avec repli sur etudiants.id_classe si aucune inscription trouvée)
// --------------------------------------------------------------------
$stmtInsc = $pdo->prepare("SELECT id_classe FROM inscriptions WHERE id_etudiant = :id AND id_annee = :annee");
$stmtInsc->execute([':id' => $id_etudiant, ':annee' => $annee['id_annee']]);
$insc = $stmtInsc->fetch();

if ($insc) {
    $id_classe = (int) $insc['id_classe'];
} else {
    $stmtFallback = $pdo->prepare("SELECT id_classe FROM etudiants WHERE id_etudiant = :id");
    $stmtFallback->execute([':id' => $id_etudiant]);
    $id_classe = (int) $stmtFallback->fetchColumn();
}

// --------------------------------------------------------------------
// 4. Enregistrement des notes (POST)
// --------------------------------------------------------------------
$message = null;
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_notes') {
    $posted_id_etudiant = (int) ($_POST['id_etudiant'] ?? 0);
    $rows = $_POST['notes'] ?? []; // indexé par id_assignation
    $saved = 0;
    $incomplete = 0;
    $invalid = 0;

    // cc/tp/examen sont NOT NULL dans la table `notes` : les 3 doivent être
    // fournies ensemble pour pouvoir enregistrer la ligne d'un module.
    $sql = "INSERT INTO notes (cc, tp, examen, id_etudiant, id_assignation)
            VALUES (:cc, :tp, :examen, :id_etudiant, :id_assignation)
            ON DUPLICATE KEY UPDATE cc = :cc_u, tp = :tp_u, examen = :examen_u";
    $stmt = $pdo->prepare($sql);

    foreach ($rows as $id_assignation => $vals) {
        $id_assignation = (int) $id_assignation;
        $cc = trim((string) ($vals['cc'] ?? ''));
        $tp = trim((string) ($vals['tp'] ?? ''));
        $ex = trim((string) ($vals['examen'] ?? ''));

        if ($cc === '' && $tp === '' && $ex === '') {
            continue;
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
            ':id_etudiant' => $posted_id_etudiant,
            ':id_assignation' => $id_assignation,
            ':cc_u' => $cc,
            ':tp_u' => $tp,
            ':examen_u' => $ex,
        ]);
        $saved++;
    }

    $params = ['id_etudiant' => $posted_id_etudiant, 'saved' => $saved];
    if ($incomplete > 0) {
        $params['incomplete'] = $incomplete;
    }
    if ($invalid > 0) {
        $params['invalid'] = $invalid;
    }
    header('Location: saisie_des_notes.php?' . http_build_query($params));
    exit;
}

if (isset($_GET['saved'])) {
    $message = (int) $_GET['saved'] . ' matière(s) enregistrée(s) avec succès.';
    if (!empty($_GET['incomplete'])) {
        $message .= ' ' . (int) $_GET['incomplete'] . ' ligne(s) ignorée(s) : CC, TP et Examen doivent être tous renseignés.';
        $messageType = 'warning';
    }
    if (!empty($_GET['invalid'])) {
        $message .= ' ' . (int) $_GET['invalid'] . " valeur(s) hors de l'intervalle 0-20.";
        $messageType = 'warning';
    }
}

// --------------------------------------------------------------------
// 5. Modules assignés à la classe de l'étudiant pour l'année en cours,
//    avec la note déjà saisie le cas échéant
// --------------------------------------------------------------------
$sqlMod = "
    SELECT a.id_assignation, m.id_module, m.nom AS module_nom, m.coefficient,
           n.cc, n.tp, n.examen, n.moyenne, n.mention
    FROM assignation_modules a
    JOIN modules m ON m.id_module = a.id_module
    LEFT JOIN notes n ON n.id_assignation = a.id_assignation AND n.id_etudiant = :id_etudiant
    WHERE a.id_classe = :id_classe AND a.id_annee = :id_annee
";
$paramsMod = [':id_etudiant' => $id_etudiant, ':id_classe' => $id_classe, ':id_annee' => $annee['id_annee']];

if ($user_role === 'enseignant') {
    $sqlMod .= " AND a.id_enseignant = :id_enseignant";
    $paramsMod[':id_enseignant'] = $id_enseignant;
}
$sqlMod .= " ORDER BY m.nom";

$stmtMod = $pdo->prepare($sqlMod);
$stmtMod->execute($paramsMod);
$modules = $stmtMod->fetchAll();

// --------------------------------------------------------------------
// 6. Moyenne générale pondérée par le coefficient de chaque module
// --------------------------------------------------------------------
$sommePonderee = 0;
$sommeCoef = 0;
$auMoinsUneNote = false;
foreach ($modules as $m) {
    if ($m['moyenne'] !== null) {
        $sommePonderee += $m['moyenne'] * $m['coefficient'];
        $sommeCoef += $m['coefficient'];
        $auMoinsUneNote = true;
    }
}
$moyenneGenerale = ($auMoinsUneNote && $sommeCoef > 0) ? $sommePonderee / $sommeCoef : null;

$pageTitle = "Saisie des Notes";
$activePage = "notes";

require_once __DIR__ . '/../include/head.php';
?>

<body class="admin-layout">

    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>

    <div class="main-wrapper">

        <?php require_once __DIR__ . '/../include/header.php'; ?>

        <main class="main-content">

            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="page-title">Saisie des Notes</h2>
                        <p class="page-subtitle">
                            Enregistrer les notes d'un étudiant.
                        </p>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'warning' ?>"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <div class="card shadow-sm">

                    <div class="card-body">

                        <form method="get" id="selectStudentForm">
                            <div class="row mb-4">

                                <div class="col-md-6">
                                    <label class="form-label">Etudiant</label>
                                    <select class="form-select" name="id_etudiant" onchange="this.form.submit()">
                                        <?php foreach ($etudiantsListe as $e): ?>
                                            <option value="<?= (int) $e['id_etudiant'] ?>" <?= (int) $e['id_etudiant'] === $id_etudiant ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($e['matricule'] . ' - ' . $e['nom'] . ' ' . $e['prenom'] . ' (' . $e['classe_nom'] . ')') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Année universitaire</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($annee['libelle']) ?>" readonly>
                                </div>

                            </div>
                        </form>

                        <form method="post" id="notesForm">
                            <input type="hidden" name="action" value="save_notes">
                            <input type="hidden" name="id_etudiant" value="<?= $id_etudiant ?>">

                            <div class="table-responsive">

                                <table class="table table-bordered align-middle">

                                    <thead class="table-light">
                                        <tr>
                                            <th>Matière</th>
                                            <th>Coefficient</th>
                                            <th class="text-center">CC /20</th>
                                            <th class="text-center">TP /20</th>
                                            <th class="text-center">Examen /20</th>
                                            <th class="text-center">Moyenne module</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (empty($modules)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">Aucun module assigné à la classe de cet étudiant pour <?= htmlspecialchars($annee['libelle']) ?>.</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php foreach ($modules as $m): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($m['module_nom']) ?></td>
                                                <td class="coef"><?= htmlspecialchars($m['coefficient']) ?></td>
                                                <td>
                                                    <input type="number"
                                                        class="form-control note js-cc"
                                                        min="0" max="20" step="0.5"
                                                        name="notes[<?= (int) $m['id_assignation'] ?>][cc]"
                                                        value="<?= $m['cc'] !== null ? htmlspecialchars($m['cc']) : '' ?>">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        class="form-control note js-tp"
                                                        min="0" max="20" step="0.5"
                                                        name="notes[<?= (int) $m['id_assignation'] ?>][tp]"
                                                        value="<?= $m['tp'] !== null ? htmlspecialchars($m['tp']) : '' ?>">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        class="form-control note js-examen"
                                                        min="0" max="20" step="0.5"
                                                        name="notes[<?= (int) $m['id_assignation'] ?>][examen]"
                                                        value="<?= $m['examen'] !== null ? htmlspecialchars($m['examen']) : '' ?>">
                                                </td>
                                                <td class="text-center fw-bold js-moyenne-module" data-coef="<?= htmlspecialchars($m['coefficient']) ?>">
                                                    <?= $m['moyenne'] !== null ? number_format((float) $m['moyenne'], 2) : '--' ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>

                                    <tfoot>
                                        <tr class="table-primary">
                                            <th colspan="5">
                                                Moyenne Générale (pondérée par coefficient)
                                            </th>
                                            <th>
                                                <input
                                                    type="text"
                                                    id="moyenne"
                                                    class="form-control fw-bold"
                                                    readonly
                                                    value="<?= $moyenneGenerale !== null ? number_format((float) $moyenneGenerale, 2) : '--' ?>">
                                            </th>
                                        </tr>
                                    </tfoot>

                                </table>

                            </div>

                            <div class="text-end">
                                <a href="../relev_de_notes/relvedenote.php" class="btn btn-primary-suptech d-flex align-items-center gap-2 d-inline-flex">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">description</span>
                                    Bulletins de notes
                                </a>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </main>

    </div>

    <script>
        function recalcGenerale() {
            var total = 0,
                coefTotal = 0;
            document.querySelectorAll('#notesForm tbody tr').forEach(function(tr) {
                var ccInput = tr.querySelector('.js-cc');
                var tpInput = tr.querySelector('.js-tp');
                var exInput = tr.querySelector('.js-examen');
                var coefCell = tr.querySelector('.js-moyenne-module');
                if (!coefCell || !ccInput) {
                    return;
                }
                var cc = parseFloat(ccInput.value);
                var tp = parseFloat(tpInput.value);
                var ex = parseFloat(exInput.value);
                var coef = parseFloat(coefCell.dataset.coef);
                if (isNaN(cc) || isNaN(tp) || isNaN(ex)) {
                    coefCell.textContent = '--';
                    return;
                }
                var moy = Math.round((cc * 0.2 + tp * 0.3 + ex * 0.5) * 100) / 100;
                coefCell.textContent = moy.toFixed(2);
                total += moy * coef;
                coefTotal += coef;
            });
            document.getElementById('moyenne').value = coefTotal > 0 ? (total / coefTotal).toFixed(2) : '--';
        }
        document.querySelectorAll('#notesForm .note').forEach(function(input) {
            input.addEventListener('input', recalcGenerale);
        });
    </script>

    <?php require_once __DIR__ . '/../include/footer.php'; ?>