<?php
require_once __DIR__ . '/../include/config.php';

// Vérifier que l'utilisateur est administrateur
$user_role = strtolower(trim($_SESSION['user_role'] ?? ''));
if ($user_role !== 'administrateur') {
    header('Location: ' . url('index.php'));
    exit;
}

// Charger les logs depuis le fichier JSON
$log_file = __DIR__ . '/activity_logs.json';
$logs = [];
if (file_exists($log_file)) {
    $content = file_get_contents($log_file);
    if ($content) {
        $logs = json_decode($content, true) ?: [];
    }
}

if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="journaux_activites.csv"');
    $out = fopen('php://output', 'w');
    fputs($out, "\xEF\xBB\xBF"); // BOM pour UTF-8
    fputcsv($out, ['Date', 'Utilisateur', 'Role', 'Action', 'Details', 'Adresse IP'], ';');
    
    foreach ($logs as $log) {
        fputcsv($out, [
            $log['date'],
            $log['nom_complet'],
            $log['role'],
            $log['action'],
            $log['details'],
            $log['adresse_ip']
        ], ';');
    }
    fclose($out);
    exit;
}

// Pagination
$effectif = count($logs);
$parPage = 15;
$totalPages = max(1, (int) ceil($effectif / $parPage));
$page = isset($_GET['page']) ? max(1, min($totalPages, (int) $_GET['page'])) : 1;
$logsPage = array_slice($logs, ($page - 1) * $parPage, $parPage);

$pageTitle = 'Journaux d\'activités';
$activePage = 'journaux';

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
                        <span>Administration</span>
                        <span>/</span>
                        <span class="active">Journaux d'activités</span>
                    </nav>
                    <h2 class="page-title" style="font-size: 1.5rem;">Journaux des connexions et actions</h2>
                    <p class="page-subtitle mb-0">Suivi détaillé des actions effectuées par les utilisateurs sur le portail</p>
                </div>
                <div class="d-flex gap-2 flex-wrap align-items-end">
                    <a href="?export=1" class="btn btn-success d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 18px;">download</span>
                        Exporter Excel
                    </a>
                    <button class="btn btn-outline-suptech d-flex align-items-center gap-2" onclick="window.location.reload();">
                        <span class="material-symbols-outlined" style="font-size: 18px;">refresh</span>
                        Actualiser
                    </button>
                </div>
            </div>

            <!-- Tableau des logs -->
            <div class="surface-card d-flex flex-column" style="height: auto; min-height: 500px;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div class="position-relative" style="width: 280px;">
                        <span class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y ms-2 text-muted" style="font-size: 18px;">search</span>
                        <input type="text" id="searchInput" class="form-control form-control-sm ps-5" placeholder="Rechercher (nom, action, rôle...)">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-muted">Total des logs:</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary" style="font-size: 12px;"><?= $effectif ?></span>
                    </div>
                </div>

                <div class="table-responsive flex-grow-1 overflow-auto">
                    <table class="table table-suptech mb-0">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th>Date & Heure</th>
                                <th>Utilisateur</th>
                                <th>Rôle</th>
                                <th>Action</th>
                                <th style="width: 35%;">Détails</th>
                                <th>Adresse IP</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">
                            <?php if (empty($logsPage)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Aucun journal d'activité enregistré pour le moment.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logsPage as $log): 
                                    // Déterminer la couleur de l'action pour un meilleur repère visuel
                                    $action_class = 'bg-light text-dark';
                                    if (stripos($log['action'], 'connexion') !== false && stripos($log['action'], 'échec') === false) {
                                        $action_class = 'bg-success-subtle text-success';
                                    } elseif (stripos($log['action'], 'déconnexion') !== false) {
                                        $action_class = 'bg-secondary-subtle text-secondary';
                                    } elseif (stripos($log['action'], 'échec') !== false || stripos($log['action'], 'erreur') !== false) {
                                        $action_class = 'bg-danger-subtle text-danger';
                                    }
                                ?>
                                    <tr class="log-row">
                                        <td class="text-muted small">
                                            <?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($log['date']))) ?>
                                        </td>
                                        <td class="fw-medium">
                                            <?= htmlspecialchars($log['nom_complet']) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary border">
                                                <?= htmlspecialchars(ucfirst($log['role'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill <?= $action_class ?> px-2 py-1" style="font-weight: 500;">
                                                <?= htmlspecialchars($log['action']) ?>
                                            </span>
                                        </td>
                                        <td class="small text-muted" style="max-width: 300px; white-space: normal;">
                                            <?= htmlspecialchars($log['details']) ?>
                                        </td>
                                        <td class="font-monospace small text-muted">
                                            <?= htmlspecialchars($log['adresse_ip']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <span class="small text-muted">
                        Affichage <?= $effectif > 0 ? (($page - 1) * $parPage + 1) : 0 ?>-<?= min($page * $parPage, $effectif) ?> sur <?= $effectif ?> entrées
                    </span>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= max(1, $page - 1) ?>"><span class="material-symbols-outlined" style="font-size: 16px;">chevron_left</span></a>
                            </li>
                            <?php 
                            // Logique simple pour afficher quelques pages (ex: max 5 boutons)
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $page + 2);
                            for ($p = $startPage; $p <= $endPage; $p++): 
                            ?>
                                <li class="page-item <?= $p === $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a></li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>"><span class="material-symbols-outlined" style="font-size: 16px;">chevron_right</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('searchInput')?.addEventListener('input', function(ev) {
            var term = ev.target.value.trim().toLowerCase();
            document.querySelectorAll('.log-row').forEach(function(tr) {
                tr.style.display = tr.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    </script>

    <?php require_once __DIR__ . '/../include/footer.php'; ?>
