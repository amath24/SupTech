<?php
ini_set('max_execution_time', 300); // 5 minutes
ini_set('memory_limit', '512M');
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';
require_once __DIR__ . '/../include/pdf_config.php';

$id_classe = isset($_GET['classe']) ? (int)$_GET['classe'] : 0;
if ($id_classe <= 0) {
    die("Classe invalide.");
}

$db = Database::getInstance();

// Récupération de l'année en cours
$anneeQuery = $db->query("SELECT id_annee, libelle FROM annees_universitaires ORDER BY date_debut DESC LIMIT 1");
$anneeData = $anneeQuery->fetch();
if (!$anneeData) {
    die("Aucune année universitaire configurée.");
}
$id_annee_courante = (int)$anneeData['id_annee'];
$annee_libelle = $anneeData['libelle'];

// Récupérer la classe
$stmtC = $db->prepare("SELECT nom, niveau, filiere FROM classes WHERE id_classe = ?");
$stmtC->execute([$id_classe]);
$classeData = $stmtC->fetch();
if (!$classeData) {
    die("Classe introuvable.");
}
$classe_nom = htmlspecialchars($classeData['nom']);
$niveau = htmlspecialchars($classeData['niveau']);

// Récupérer les étudiants de cette classe pour l'année en cours
$queryEtudiants = "
    SELECT e.id_etudiant, e.nom, e.prenom, e.matricule
    FROM inscriptions i
    JOIN etudiants e ON i.id_etudiant = e.id_etudiant
    WHERE i.id_classe = ? AND i.id_annee = ?
    ORDER BY e.nom ASC
";
$stmtE = $db->prepare($queryEtudiants);
$stmtE->execute([$id_classe, $id_annee_courante]);
$etudiants = $stmtE->fetchAll();

if (empty($etudiants)) {
    die("Aucun étudiant trouvé dans cette classe.");
}

// ---------------------------------------------------------
// GÉNÉRATION HTML GLOBALE
// ---------------------------------------------------------
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletins de la classe <?= $classe_nom ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #00236f;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #00236f;
            margin: 0 0 5px 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #555;
            font-size: 13px;
        }
        .title-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .title-main {
            font-size: 22px;
            color: #00236f;
            text-transform: uppercase;
            font-weight: bold;
            margin: 0;
        }
        .annee-badge {
            background-color: #f0f4ff;
            color: #00236f;
            padding: 5px 10px;
            border: 1px solid #dce1ff;
            font-size: 12px;
            font-weight: bold;
        }
        .student-info {
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #fafafa;
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
            color: #555;
        }
        table.grades {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.grades th, table.grades td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        table.grades th {
            background-color: #f0f4ff;
            color: #00236f;
            text-align: center;
            font-size: 12px;
            text-transform: uppercase;
        }
        table.grades td {
            font-size: 13px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary-box {
            display: inline-block;
            width: 32%;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            padding: 10px;
            box-sizing: border-box;
            text-align: center;
        }
        .summary-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #777;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #00236f;
        }
        .footer-signatures {
            margin-top: 50px;
            width: 100%;
        }
        .footer-signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>
<body>

<?php 
$total_etudiants = count($etudiants);
$i = 0;
foreach ($etudiants as $etu): 
    $i++;
    $id_etu = $etu['id_etudiant'];
    $etudiant_nom = htmlspecialchars($etu['prenom'] . ' ' . $etu['nom']);
    $matricule = htmlspecialchars($etu['matricule']);
    
    // Récupérer les notes de l'étudiant
    $sqlMod = "
        SELECT a.id_assignation, m.nom AS module_nom, m.coefficient,
               n.moyenne, n.mention
        FROM assignation_modules a
        JOIN modules m ON m.id_module = a.id_module
        LEFT JOIN notes n ON n.id_assignation = a.id_assignation AND n.id_etudiant = ?
        WHERE a.id_classe = ? AND a.id_annee = ?
        ORDER BY m.nom
    ";
    $stmtM = $db->prepare($sqlMod);
    $stmtM->execute([$id_etu, $id_classe, $id_annee_courante]);
    $modules = $stmtM->fetchAll();
    
    // Calcul de la moyenne
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
    
    $moyenneGenerale = ($auMoinsUneNote && $sommeCoef > 0) ? ($sommePonderee / $sommeCoef) : null;
    $mention = "";
    if ($moyenneGenerale !== null) {
        if ($moyenneGenerale >= 16) $mention = "Très Bien";
        elseif ($moyenneGenerale >= 14) $mention = "Bien";
        elseif ($moyenneGenerale >= 12) $mention = "Assez Bien";
        elseif ($moyenneGenerale >= 10) $mention = "Passable";
        else $mention = "Insuffisant";
    }
    $decision = ($moyenneGenerale >= 10) ? "Admis(e)" : "Ajourné(e)";
?>

    <div class="header">
        <h1>SupTech Business School</h1>
        <p>Avenue Cheikh Anta Diop, Dakar, Sénégal</p>
        <p>Tél: +221 33 800 00 00 | Email: contact@suptech.sn</p>
    </div>

    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td>
                <h2 class="title-main">Relevé de Notes</h2>
            </td>
            <td style="text-align: right;">
                <span class="annee-badge">Année Académique: <?= htmlspecialchars($annee_libelle) ?></span>
            </td>
        </tr>
    </table>

    <div class="student-info">
        <div class="info-row"><span class="info-label">Étudiant(e) :</span> <strong><?= $etudiant_nom ?></strong></div>
        <div class="info-row"><span class="info-label">Matricule :</span> <?= $matricule ?></div>
        <div class="info-row"><span class="info-label">Classe :</span> <?= $classe_nom ?></div>
        <div class="info-row"><span class="info-label">Niveau :</span> <?= $niveau ?></div>
    </div>

    <table class="grades">
        <thead>
            <tr>
                <th style="text-align: left;">Module</th>
                <th>Coefficient</th>
                <th>Note /20</th>
                <th>Note Pondérée</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($modules)): ?>
                <tr>
                    <td colspan="4" class="text-center">Aucun module n'est assigné pour l'instant.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($modules as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['module_nom']) ?></td>
                        <td class="text-center"><?= $m['coefficient'] ?></td>
                        <td class="text-center font-weight-bold">
                            <?= $m['moyenne'] !== null ? number_format((float)$m['moyenne'], 2) : '--' ?>
                        </td>
                        <td class="text-right">
                            <?= $m['moyenne'] !== null ? number_format((float)($m['moyenne'] * $m['coefficient']), 2) : '--' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td class="text-right">Total</td>
                <td class="text-center"><?= $sommeCoef ?></td>
                <td class="text-center">--</td>
                <td class="text-right"><?= number_format((float)$sommePonderee, 2) ?></td>
            </tr>
        </tfoot>
    </table>

    <div style="width: 100%; margin-bottom: 40px;">
        <div class="summary-box">
            <div class="summary-title">Moyenne Générale</div>
            <div class="summary-value"><?= $moyenneGenerale !== null ? number_format((float)$moyenneGenerale, 2) : '--' ?> / 20</div>
        </div>
        <div class="summary-box">
            <div class="summary-title">Mention</div>
            <div class="summary-value" style="font-size: 16px;"><?= $mention ?: '--' ?></div>
        </div>
        <div class="summary-box">
            <div class="summary-title">Décision du Jury</div>
            <div class="summary-value" style="color: <?= ($moyenneGenerale >= 10) ? '#006c49' : '#ba1a1a' ?>; font-size: 16px;">
                <?= ($moyenneGenerale !== null) ? $decision : '--' ?>
            </div>
        </div>
    </div>

    <table class="footer-signatures">
        <tr>
            <td>
                <div style="font-size: 12px; color: #777; margin-bottom: 80px;">Cachet de l'Établissement</div>
            </td>
            <td>
                <div style="font-weight: bold; margin-bottom: 5px;">Le Directeur des Études</div>
                <div style="font-size: 11px; font-style: italic; color: #777; margin-bottom: 80px;">Fait à Dakar, le <?= date('d/m/Y') ?></div>
            </td>
        </tr>
    </table>

    <?php if ($i < $total_etudiants): ?>
        <div class="page-break"></div>
    <?php endif; ?>

<?php endforeach; ?>

</body>
</html>
<?php
$html = ob_get_clean();

$stream = !isset($_GET['download']);
$nom_fichier = "Bulletins_Classe_" . preg_replace('/[^A-Za-z0-9_-]/', '_', $classe_nom) . ".pdf";

renderPdf($html, $nom_fichier, $stream);
