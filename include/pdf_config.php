<?php
// Charger l'autoloader de Dompdf fourni par la release
require_once __DIR__ . '/../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function getPdfOptions() {
    $options = new Options();
    // Activer le chargement d'images distantes (utile si les logos utilisent des URL absolues)
    $options->set('isRemoteEnabled', true);
    // Configurer le dossier de base pour les ressources locales
    $options->set('chroot', realpath(__DIR__ . '/../'));
    
    return $options;
}

/**
 * Fonction utilitaire pour générer et télécharger/afficher un PDF.
 * @param string $html Le contenu HTML
 * @param string $filename Le nom du fichier de sortie
 * @param bool $stream Si true, affiche dans le navigateur, sinon force le téléchargement
 */
function renderPdf($html, $filename = 'document.pdf', $stream = true) {
    $dompdf = new Dompdf(getPdfOptions());
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Nettoyer tous les tampons de sortie actifs pour éviter de corrompre le PDF
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    $dompdf->stream($filename, [
        "Attachment" => !$stream // false = affiche dans le navigateur, true = télécharge
    ]);
    exit;
}
