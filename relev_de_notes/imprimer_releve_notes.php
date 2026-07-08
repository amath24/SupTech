<?php
/**
 * Générateur de bulletin de notes individuel (PDF via Dompdf)
 * URL: imprimer_releve_notes.php?id_etudiant=X            -> affiche dans navigateur
 * URL: imprimer_releve_notes.php?id_etudiant=X&download=1 -> télécharge
 */

// 1. Désactiver TOUT affichage d'erreur pour ne pas corrompre le PDF
@ini_set('display_errors', '0');
@error_reporting(0);
@ini_set('log_errors', '0');

// 2. Connexion PDO directe (pas de session, pas de config.php)
$host   = 'localhost';
$dbname = 'gestion_academique';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('Erreur de connexion: ' . $e->getMessage());
}

// 3. Paramètres
$id_etudiant = isset($_GET['id_etudiant']) ? (int)$_GET['id_etudiant'] : 0;
$id_annee    = isset($_GET['id_annee'])    ? (int)$_GET['id_annee']    : 0;
$download    = isset($_GET['download']);

if ($id_etudiant <= 0) {
    http_response_code(400);
    die('ID étudiant invalide.');
}

// 4. Récupérer l'inscription, l'étudiant, la classe et l'année
$sqlInsc = "
    SELECT i.id_classe, i.id_annee,
           e.nom AS etudiant_nom, e.prenom AS etudiant_prenom, e.matricule,
           c.nom AS classe_nom, c.niveau AS classe_niveau,
           a.libelle AS annee_libelle
    FROM inscriptions i
    JOIN etudiants e ON e.id_etudiant = i.id_etudiant
    JOIN classes   c ON c.id_classe   = i.id_classe
    JOIN annees_universitaires a ON a.id_annee = i.id_annee
    WHERE i.id_etudiant = ?
";
$params = [$id_etudiant];

if ($id_annee > 0) {
    $sqlInsc .= " AND i.id_annee = ?";
    $params[] = $id_annee;
} else {
    $sqlInsc .= " ORDER BY a.date_debut DESC LIMIT 1";
}

$stmtInsc = $pdo->prepare($sqlInsc);
$stmtInsc->execute($params);
$insc = $stmtInsc->fetch();

if (!$insc) {
    http_response_code(404);
    die('Aucune inscription trouvée pour cet étudiant.');
}

$id_classe          = (int) $insc['id_classe'];
$id_annee_effective = (int) $insc['id_annee'];
$etudiant_nom       = htmlspecialchars($insc['etudiant_prenom'] . ' ' . $insc['etudiant_nom']);
$matricule          = htmlspecialchars($insc['matricule']);
$classe             = htmlspecialchars($insc['classe_nom']);
$niveau             = htmlspecialchars($insc['classe_niveau']);
$annee              = htmlspecialchars($insc['annee_libelle']);

// 5. Récupérer les modules et notes
$sqlMod = "
    SELECT m.nom AS module_nom, m.coefficient,
           n.moyenne
    FROM assignation_modules a
    JOIN modules m ON m.id_module = a.id_module
    LEFT JOIN notes n ON n.id_assignation = a.id_assignation AND n.id_etudiant = ?
    WHERE a.id_classe = ? AND a.id_annee = ?
    ORDER BY m.nom
";
$stmtMod = $pdo->prepare($sqlMod);
$stmtMod->execute([$id_etudiant, $id_classe, $id_annee_effective]);
$modules = $stmtMod->fetchAll();

// 6. Calcul de la moyenne générale
$sommePonderee  = 0.0;
$sommeCoef      = 0;
$auMoinsUneNote = false;

foreach ($modules as $m) {
    if ($m['moyenne'] !== null) {
        $sommePonderee  += (float)$m['moyenne'] * (int)$m['coefficient'];
        $sommeCoef      += (int)$m['coefficient'];
        $auMoinsUneNote  = true;
    }
}

$moyenneGenerale = ($auMoinsUneNote && $sommeCoef > 0) ? ($sommePonderee / $sommeCoef) : null;

$mention = '';
if ($moyenneGenerale !== null) {
    if ($moyenneGenerale >= 16)     $mention = 'Très Bien';
    elseif ($moyenneGenerale >= 14) $mention = 'Bien';
    elseif ($moyenneGenerale >= 12) $mention = 'Assez Bien';
    elseif ($moyenneGenerale >= 10) $mention = 'Passable';
    else                            $mention = 'Insuffisant';
}

$decision      = ($moyenneGenerale !== null && $moyenneGenerale >= 10) ? 'Admis(e)' : 'Ajourné(e)';
$decisionColor = ($moyenneGenerale !== null && $moyenneGenerale >= 10) ? '#006c49' : '#ba1a1a';

// 7. Charger Dompdf
$autoload = __DIR__ . '/../dompdf/autoload.inc.php';
if (!file_exists($autoload)) {
    http_response_code(500);
    die('Dompdf introuvable. Vérifiez que le dossier dompdf/ existe dans la racine du projet.');
}
require_once $autoload;

use Dompdf\Dompdf;
use Dompdf\Options;

// 8. Construire le HTML
$html = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Relevé de notes – {$matricule}</title>
<style>
  body { font-family: Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 24px; font-size: 13px; }
  .header { text-align: center; border-bottom: 2px solid #00236f; padding-bottom: 10px; margin-bottom: 18px; }
  .header h1 { color: #00236f; margin: 0 0 4px; font-size: 22px; text-transform: uppercase; }
  .header p  { margin: 2px 0; color: #555; font-size: 12px; }
  .title-table { width: 100%; margin-bottom: 18px; }
  .title-main  { font-size: 20px; color: #00236f; text-transform: uppercase; font-weight: bold; }
  .badge-annee { background: #f0f4ff; color: #00236f; border: 1px solid #dce1ff; padding: 4px 10px; font-size: 12px; font-weight: bold; }
  .info-box { border: 1px solid #ddd; background: #fafafa; padding: 14px; margin-bottom: 18px; }
  .info-row { margin-bottom: 5px; }
  .info-label { font-weight: bold; display: inline-block; width: 140px; color: #555; }
  table.grades { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
  table.grades th, table.grades td { border: 1px solid #ccc; padding: 7px 10px; }
  table.grades thead th { background: #f0f4ff; color: #00236f; text-align: center; font-size: 11px; text-transform: uppercase; }
  table.grades td { font-size: 13px; }
  .ta-c { text-align: center; }
  .ta-r { text-align: right; }
  .summary-wrap { width: 100%; margin-bottom: 36px; }
  .summary-box { display: inline-block; width: 32%; border: 1px solid #ccc; background: #f9f9f9; padding: 10px; box-sizing: border-box; text-align: center; vertical-align: top; }
  .summary-title { font-size: 10px; text-transform: uppercase; color: #777; margin-bottom: 4px; }
  .summary-value { font-size: 18px; font-weight: bold; color: #00236f; }
  .signatures { width: 100%; margin-top: 50px; }
  .signatures td { width: 50%; text-align: center; vertical-align: top; }
</style>
</head>
<body>

<div class="header">
  <h1>SupTech Business School</h1>
  <p>Avenue Cheikh Anta Diop, Dakar, Sénégal</p>
  <p>Tél: +221 33 800 00 00 &nbsp;|&nbsp; Email: contact@suptech.sn</p>
</div>

<table class="title-table">
  <tr>
    <td><span class="title-main">Relevé de Notes</span></td>
    <td style="text-align:right;"><span class="badge-annee">Année Académique : {$annee}</span></td>
  </tr>
</table>

<div class="info-box">
  <div class="info-row"><span class="info-label">Étudiant(e) :</span> <strong>{$etudiant_nom}</strong></div>
  <div class="info-row"><span class="info-label">Matricule :</span> {$matricule}</div>
  <div class="info-row"><span class="info-label">Classe :</span> {$classe}</div>
  <div class="info-row"><span class="info-label">Niveau :</span> {$niveau}</div>
</div>

HTML;

// Table des modules
$html .= '<table class="grades"><thead><tr>';
$html .= '<th style="text-align:left;">Module</th><th>Coefficient</th><th>Note /20</th><th>Note Pondérée</th>';
$html .= '</tr></thead><tbody>';

if (empty($modules)) {
    $html .= '<tr><td colspan="4" class="ta-c">Aucun module assigné pour l\'instant.</td></tr>';
} else {
    foreach ($modules as $m) {
        $note     = $m['moyenne'] !== null ? number_format((float)$m['moyenne'], 2) : '--';
        $pondere  = $m['moyenne'] !== null ? number_format((float)$m['moyenne'] * (int)$m['coefficient'], 2) : '--';
        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($m['module_nom']) . "</td>";
        $html .= "<td class='ta-c'>" . $m['coefficient'] . "</td>";
        $html .= "<td class='ta-c'><strong>" . $note . "</strong></td>";
        $html .= "<td class='ta-r'>" . $pondere . "</td>";
        $html .= "</tr>";
    }
}

$totalCoef = $sommeCoef;
$totalPond = number_format($sommePonderee, 2);
$html .= "</tbody><tfoot>";
$html .= "<tr style='background:#f9f9f9;font-weight:bold;'><td class='ta-r'>Total</td><td class='ta-c'>$totalCoef</td><td class='ta-c'>--</td><td class='ta-r'>$totalPond</td></tr>";
$html .= "</tfoot></table>";

// Résumé
$moyAff = $moyenneGenerale !== null ? number_format((float)$moyenneGenerale, 2) . ' / 20' : '-- / 20';
$html .= '<div class="summary-wrap">';
$html .= "<div class='summary-box'><div class='summary-title'>Moyenne Générale</div><div class='summary-value'>$moyAff</div></div>";
$html .= "<div class='summary-box'><div class='summary-title'>Mention</div><div class='summary-value' style='font-size:15px;'>" . ($mention ?: '--') . "</div></div>";
$html .= "<div class='summary-box'><div class='summary-title'>Décision du Jury</div><div class='summary-value' style='color:$decisionColor;font-size:15px;'>" . ($moyenneGenerale !== null ? $decision : '--') . "</div></div>";
$html .= '</div>';

// Signatures
$date = date('d/m/Y');
$html .= <<<SIG
<table class="signatures">
  <tr>
    <td>
      <div style="font-size:12px;color:#777;margin-bottom:80px;">Cachet de l'Établissement</div>
    </td>
    <td>
      <div style="font-weight:bold;margin-bottom:5px;">Le Directeur des Études</div>
      <div style="font-size:11px;font-style:italic;color:#777;margin-bottom:80px;">Fait à Dakar, le $date</div>
    </td>
  </tr>
</table>
SIG;

$html .= '</body></html>';

// 9. Génération Dompdf
$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 10. Nettoyer les buffers actifs AVANT de streamer
while (ob_get_level() > 0) {
    ob_end_clean();
}

$filename = 'Bulletin_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $insc['matricule']) . '.pdf';
$dompdf->stream($filename, ['Attachment' => $download ? 1 : 0]);
exit;
