<?php
// Désactiver l'affichage des erreurs pour ne pas corrompre le PDF
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';
require_once __DIR__ . '/../include/pdf_config.php';

// Vérification de l'ID de l'inscription
$id_inscription = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_inscription <= 0) {
    die("ID d'inscription invalide.");
}

$db = Database::getInstance();

// Récupérer les informations de l'inscription
$query = "
    SELECT i.*, 
           e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.matricule, e.date_naissance, e.lieu_naissance,
           c.nom as classe_nom, c.niveau as classe_niveau, c.filiere,
           a.libelle as annee_libelle 
    FROM inscriptions i
    JOIN etudiants e ON i.id_etudiant = e.id_etudiant
    JOIN classes c ON i.id_classe = c.id_classe
    JOIN annees_universitaires a ON i.id_annee = a.id_annee
    WHERE i.id_inscription = ?
";
$stmt = $db->prepare($query);
$stmt->execute([$id_inscription]);
$inscription = $stmt->fetch();

if (!$inscription) {
    die("Inscription introuvable.");
}

$reference = "INS-" . date('Y', strtotime($inscription['date_inscription'])) . "-" . str_pad($inscription['id_inscription'], 4, '0', STR_PAD_LEFT);
$date_inscription = date('d/m/Y', strtotime($inscription['date_inscription']));
$etudiant_nom = htmlspecialchars($inscription['etudiant_prenom'] . ' ' . $inscription['etudiant_nom']);
$matricule = htmlspecialchars($inscription['matricule']);
$date_naissance = date('d/m/Y', strtotime($inscription['date_naissance']));
$lieu_naissance = htmlspecialchars($inscription['lieu_naissance']);
$classe = htmlspecialchars($inscription['classe_nom']);
$filiere = htmlspecialchars($inscription['filiere']);
$niveau = htmlspecialchars($inscription['classe_niveau']);
$annee = htmlspecialchars($inscription['annee_libelle']);

// Génération du contenu HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation d'Inscription - <?= $reference ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #00236f;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        .header h1 {
            color: #00236f;
            margin: 0 0 5px 0;
            font-size: 26px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #555;
            font-size: 14px;
        }
        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 40px;
            color: #00236f;
            text-decoration: underline;
            text-underline-offset: 5px;
        }
        .content {
            font-size: 16px;
            text-align: justify;
        }
        .content p {
            margin-bottom: 15px;
        }
        .student-info {
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9ff;
            border-left: 5px solid #00236f;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 5px 0;
        }
        .info-table .label {
            font-weight: bold;
            width: 180px;
            color: #555;
        }
        .footer {
            margin-top: 80px;
        }
        .date-location {
            text-align: right;
            margin-bottom: 40px;
            font-style: italic;
        }
        .signature {
            text-align: right;
            font-weight: bold;
            margin-right: 20px;
        }
        .watermark {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            opacity: 0.05;
            font-size: 120px;
            color: #00236f;
            text-transform: uppercase;
            font-weight: bold;
            z-index: -1;
            white-space: nowrap;
        }
    </style>
</head>
<body>

    <div class="watermark">SUPTECH</div>

    <div class="header">
        <h1>SupTech Business School</h1>
        <p>Avenue Cheikh Anta Diop, Dakar, Sénégal</p>
        <p>Tél: +221 33 800 00 00 | Email: contact@suptech.sn</p>
    </div>

    <div class="title">
        ATTESTATION D'INSCRIPTION
    </div>

    <div class="content">
        <p>
            Le Directeur de <strong>SupTech Business School</strong> soussigné, atteste par la présente que l'étudiant(e) dont les informations suivent est régulièrement inscrit(e) au sein de notre établissement :
        </p>
        
        <div class="student-info">
            <table class="info-table">
                <tr>
                    <td class="label">Nom et Prénom :</td>
                    <td><strong><?= $etudiant_nom ?></strong></td>
                </tr>
                <tr>
                    <td class="label">Date et lieu de naissance :</td>
                    <td><?= $date_naissance ?> à <?= $lieu_naissance ?></td>
                </tr>
                <tr>
                    <td class="label">Matricule :</td>
                    <td><?= $matricule ?></td>
                </tr>
                <tr>
                    <td class="label">Filière :</td>
                    <td><?= $filiere ?></td>
                </tr>
                <tr>
                    <td class="label">Niveau d'études :</td>
                    <td><?= $niveau ?></td>
                </tr>
                <tr>
                    <td class="label">Classe :</td>
                    <td><?= $classe ?></td>
                </tr>
                <tr>
                    <td class="label">Année académique :</td>
                    <td><strong><?= $annee ?></strong></td>
                </tr>
            </table>
        </div>

        <p>
            En foi de quoi, la présente attestation lui est délivrée pour servir et valoir ce que de droit.
        </p>
    </div>

    <div class="footer">
        <div class="date-location">
            Fait à Dakar, le <?= date('d/m/Y') ?>
        </div>
        <div class="signature">
            <p>La Direction Scolarité</p>
            <p style="margin-top: 80px; font-size: 14px; font-weight: normal; color: #777;">(Cachet et Signature)</p>
        </div>
    </div>

</body>
</html>
<?php
$html = ob_get_clean();

$stream = !isset($_GET['download']);
// Génération et affichage du PDF
renderPdf($html, "Attestation_Inscription_{$reference}.pdf", $stream);
