<?php
// Désactiver l'affichage des erreurs pour ne pas corrompre le PDF
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';
require_once __DIR__ . '/../include/pdf_config.php';

// Vérification de l'ID du paiement
$id_paiement = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_paiement <= 0) {
    die("ID de paiement invalide.");
}

$db = Database::getInstance();

// Récupérer les informations du paiement, étudiant, classe et année
$query = "
    SELECT p.*, 
           e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.matricule, 
           c.nom as classe_nom, 
           a.libelle as annee_libelle 
    FROM paiements p
    JOIN inscriptions i ON p.id_inscription = i.id_inscription
    JOIN etudiants e ON i.id_etudiant = e.id_etudiant
    JOIN classes c ON i.id_classe = c.id_classe
    JOIN annees_universitaires a ON i.id_annee = a.id_annee
    WHERE p.id_paiement = ?
";
$stmt = $db->prepare($query);
$stmt->execute([$id_paiement]);
$paiement = $stmt->fetch();

if (!$paiement) {
    die("Paiement introuvable.");
}

$reference = "PAY-" . str_pad($paiement['id_paiement'], 5, '0', STR_PAD_LEFT);
$date_paiement = date('d/m/Y', strtotime($paiement['date_paiement']));
$montant = number_format($paiement['montant_paye'], 0, ',', ' ');
$mode = htmlspecialchars($paiement['mode_paiement']);
$etudiant_nom = htmlspecialchars($paiement['etudiant_prenom'] . ' ' . $paiement['etudiant_nom']);
$matricule = htmlspecialchars($paiement['matricule']);
$classe = htmlspecialchars($paiement['classe_nom']);
$annee = htmlspecialchars($paiement['annee_libelle']);

// Génération du contenu HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement - <?= $reference ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #00236f;
            padding-bottom: 20px;
            margin-bottom: 30px;
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
            font-size: 14px;
        }
        .receipt-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            background-color: #f8f9ff;
            padding: 10px;
            border: 1px solid #dce1ff;
            color: #00236f;
        }
        .details-container {
            width: 100%;
            margin-bottom: 30px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 8px 0;
            vertical-align: top;
        }
        .details-table .label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        .amount-box {
            border: 2px solid #006c49;
            background-color: #eaf8f1;
            padding: 15px;
            text-align: center;
            margin-top: 30px;
            border-radius: 5px;
        }
        .amount-title {
            font-size: 14px;
            color: #006c49;
            text-transform: uppercase;
            font-weight: bold;
        }
        .amount-value {
            font-size: 28px;
            color: #006c49;
            font-weight: bold;
            margin-top: 5px;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px dashed #999;
            padding-top: 5px;
            font-size: 12px;
            color: #777;
        }
        .stamp {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.1;
            font-size: 60px;
            color: #00236f;
            text-transform: uppercase;
            font-weight: bold;
            transform: rotate(-15deg);
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>SupTech Business School</h1>
        <p>Avenue Cheikh Anta Diop, Dakar, Sénégal</p>
        <p>Tél: +221 33 800 00 00 | Email: contact@suptech.sn</p>
    </div>

    <div class="receipt-title">
        REÇU DE PAIEMENT N° <?= $reference ?>
    </div>

    <div class="details-container">
        <table class="details-table">
            <tr>
                <td class="label">Date :</td>
                <td><?= $date_paiement ?></td>
            </tr>
            <tr>
                <td class="label">Étudiant(e) :</td>
                <td><strong><?= $etudiant_nom ?></strong></td>
            </tr>
            <tr>
                <td class="label">Matricule :</td>
                <td><?= $matricule ?></td>
            </tr>
            <tr>
                <td class="label">Classe :</td>
                <td><?= $classe ?></td>
            </tr>
            <tr>
                <td class="label">Année académique :</td>
                <td><?= $annee ?></td>
            </tr>
            <tr>
                <td class="label">Mode de paiement :</td>
                <td><?= $mode ?></td>
            </tr>
        </table>
    </div>

    <div class="amount-box">
        <div class="amount-title">Montant payé</div>
        <div class="amount-value"><?= $montant ?> FCFA</div>
    </div>

    <div class="stamp">Payé</div>

    <table style="width: 100%; margin-top: 60px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <strong>Le service scolarité</strong>
                <div class="signature-line" style="margin-top: 80px; width: 80%; margin-left: auto; margin-right: auto;">Signature et cachet</div>
            </td>
            <td style="width: 50%; text-align: center;">
                <strong>L'étudiant(e)</strong>
                <div class="signature-line" style="margin-top: 80px; width: 80%; margin-left: auto; margin-right: auto;">Signature</div>
            </td>
        </tr>
    </table>

</body>
</html>
<?php
$html = ob_get_clean();

$stream = !isset($_GET['download']);
// Génération et affichage du PDF
renderPdf($html, "Recu_Paiement_{$reference}.pdf", $stream);
