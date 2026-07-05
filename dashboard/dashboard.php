<?php
$page_required_role = 'Administrateur';
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Tableau de bord';
$activePage = 'dashboard';

// Données de session
$userName = $_SESSION['user_nom'] ?? '';

$db = Database::getInstance();

// 1. Statistiques KPIs
$totalEtudiants = $db->query("SELECT COUNT(*) FROM etudiants")->fetchColumn();
$totalEnseignants = $db->query("SELECT COUNT(*) FROM enseignants")->fetchColumn();

// Total des inscriptions pour l'année courante (celle avec le libelle le plus récent)
$anneeCouranteId = $db->query("SELECT id_annee FROM annees_universitaires ORDER BY date_debut DESC LIMIT 1")->fetchColumn();
$totalInscriptions = $db->prepare("SELECT COUNT(*) FROM inscriptions WHERE id_annee = ?");
$totalInscriptions->execute([$anneeCouranteId]);
$totalInscriptions = $totalInscriptions->fetchColumn();

// Taux de réussite (Simulé comme demandé)
$tauxReussite = 78;

// 2. Données pour le graphique des paiements mensuels (Année en cours)
// On récupère la somme des paiements groupée par mois
$stmtPaiements = $db->query("
    SELECT DATE_FORMAT(date_paiement, '%Y-%m') as mois, SUM(montant_paye) as total
    FROM paiements
    WHERE YEAR(date_paiement) = YEAR(CURDATE())
    GROUP BY DATE_FORMAT(date_paiement, '%Y-%m')
    ORDER BY mois ASC
");
$paiementsMensuels = $stmtPaiements->fetchAll();

$moisLibelles = [];
$paiementsData = [];
$moisNoms = ['01' => 'JAN', '02' => 'FEV', '03' => 'MAR', '04' => 'AVR', '05' => 'MAI', '06' => 'JUIN', '07' => 'JUIL', '08' => 'AOU', '09' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DEC'];

foreach ($paiementsMensuels as $pm) {
    $moisSplit = explode('-', $pm['mois']);
    $moisLibelles[] = $moisNoms[$moisSplit[1]];
    $paiementsData[] = $pm['total'];
}

// 3. Données pour le graphique de répartition par classe
$stmtClasses = $db->query("
    SELECT c.nom as classe, c.id_classe, COUNT(i.id_inscription) as nb
    FROM inscriptions i
    JOIN classes c ON i.id_classe = c.id_classe
    WHERE i.id_annee = " . intval($anneeCouranteId) . "
    GROUP BY c.id_classe
    ORDER BY nb DESC
");
$repartitionClasses = $stmtClasses->fetchAll();

$classesLabels = [];
$classesData = [];
$classesColors = ['#00236f', '#006c49', '#4b1c00', '#005236', '#1e3a8a', '#ffb593']; // Palette SupTech
$i = 0;
foreach ($repartitionClasses as $rc) {
    $classesLabels[] = $rc['classe'];
    $classesData[] = $rc['nb'];
}

// 4. Derniers étudiants inscrits
$stmtDerniersInscrits = $db->prepare("
    SELECT e.nom, e.prenom, c.nom as classe_nom, i.date_inscription
    FROM inscriptions i
    JOIN etudiants e ON i.id_etudiant = e.id_etudiant
    JOIN classes c ON i.id_classe = c.id_classe
    ORDER BY i.date_inscription DESC, i.id_inscription DESC
    LIMIT 5
");
$stmtDerniersInscrits->execute();
$derniersInscrits = $stmtDerniersInscrits->fetchAll();

require_once __DIR__ . '/../include/head.php';
?>
<!-- Ajouter Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <!-- En-tête du tableau de bord -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="page-title">Tableau de bord académique</h1>
                <p class="page-subtitle mb-0">Bienvenue, <?= htmlspecialchars($userName) ?>. Voici l'état actuel de l'institution.</p>
            </div>
        </div>

        <!-- Grille de statistiques -->
        <div class="row g-4 mb-5">
            <!-- Étudiants -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon primary-gradient d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined" style="font-size: 28px;">groups</span>
                        </div>
                    </div>
                    <p class="stat-label mb-2">ÉTUDIANTS</p>
                    <h3 class="stat-value mb-2"><?= number_format($totalEtudiants, 0, ',', ' ') ?></h3>
                    <small class="text-muted d-flex align-items-center gap-1">
                        Total base de données
                    </small>
                </div>
            </div>

            <!-- Enseignants -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon secondary-gradient d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined" style="font-size: 28px;">school</span>
                        </div>
                    </div>
                    <p class="stat-label mb-2">ENSEIGNANTS</p>
                    <h3 class="stat-value mb-2"><?= number_format($totalEnseignants, 0, ',', ' ') ?></h3>
                    <small class="text-muted">
                        Tous les rôles confondus
                    </small>
                </div>
            </div>

            <!-- Inscriptions -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon tertiary-gradient d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined" style="font-size: 28px;">person_add</span>
                        </div>
                        <span class="badge bg-info">Actif</span>
                    </div>
                    <p class="stat-label mb-2">INSCRIPTIONS</p>
                    <h3 class="stat-value mb-2"><?= number_format($totalInscriptions, 0, ',', ' ') ?></h3>
                    <small class="text-muted">Année en cours</small>
                </div>
            </div>

            <!-- Taux de réussite -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon success-gradient d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined" style="font-size: 28px;">auto_graph</span>
                        </div>
                    </div>
                    <p class="stat-label mb-2">TAUX DE RÉUSSITE</p>
                    <h3 class="stat-value mb-2"><?= $tauxReussite ?>%</h3>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: <?= $tauxReussite ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des graphiques -->
        <div class="row g-4 mb-5">
            <!-- Paiements mensuels -->
            <div class="col-lg-8">
                <div class="surface-card h-100">
                    <div class="card-header-surface d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 h6 fw-semibold">Paiements mensuels (FCFA) - Année <?= date('Y') ?></h3>
                    </div>
                    <div class="p-4">
                        <div style="height: 300px; width: 100%;">
                            <canvas id="paiementsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition par classe -->
            <div class="col-lg-4">
                <div class="surface-card h-100">
                    <div class="card-header-surface">
                        <h3 class="mb-0 h6 fw-semibold">Répartition des inscrits par classe</h3>
                    </div>
                    <div class="p-4 d-flex flex-column justify-content-center align-items-center" style="height: calc(100% - 53px);">
                        <?php if (empty($classesData)): ?>
                            <div class="text-muted text-center">
                                <span class="material-symbols-outlined mb-2" style="font-size: 48px; opacity: 0.5;">pie_chart</span>
                                <p>Aucune donnée d'inscription pour l'année en cours.</p>
                            </div>
                        <?php else: ?>
                            <div style="width: 100%; max-width: 250px;">
                                <canvas id="classesChart"></canvas>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables des données récentes -->
        <div class="row g-4">
            <!-- Derniers étudiants inscrits -->
            <div class="col-lg-12">
                <div class="surface-card">
                    <div class="card-header-surface d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 h6 fw-semibold">Derniers étudiants inscrits</h3>
                        <a href="<?= url('gestion_des_inscriptions/gestiondesinscription.php') ?>" class="text-primary text-decoration-none small fw-semibold">
                            Voir toutes les inscriptions
                            <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: -2px;">arrow_forward</span>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-suptech mb-0">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Classe</th>
                                    <th>Date d'inscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($derniersInscrits)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Aucune inscription récente.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($derniersInscrits as $di): 
                                        $initials = strtoupper(substr($di['prenom'], 0, 1) . substr($di['nom'], 0, 1));
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="student-avatar sm" style="width: 32px; height: 32px; border-radius: 50%; background: var(--suptech-primary-light); color: var(--suptech-primary); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;"><?= $initials ?></div>
                                                <span class="fw-medium"><?= htmlspecialchars($di['prenom'] . ' ' . $di['nom']) ?></span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($di['classe_nom']) ?></span></td>
                                        <td class="text-muted"><?= date('d/m/Y', strtotime($di['date_inscription'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>

<!-- Script Chart.js -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Configuration globale Chart.js pour s'adapter au Dark Mode
    const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const textColor = isDarkMode ? '#cbd5e1' : '#444651';
    const gridColor = isDarkMode ? '#334155' : '#e2e8f0';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Inter', sans-serif";

    // 1. Graphique des paiements (Bar)
    const paiementsCtx = document.getElementById('paiementsChart');
    if (paiementsCtx) {
        new Chart(paiementsCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($moisLibelles) ?>,
                datasets: [{
                    label: 'Montant encaissé (FCFA)',
                    data: <?= json_encode($paiementsData) ?>,
                    backgroundColor: '#00236f',
                    borderRadius: 4,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR') + ' F';
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw.toLocaleString('fr-FR') + ' FCFA';
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Graphique Répartition par classe (Doughnut)
    const classesCtx = document.getElementById('classesChart');
    if (classesCtx) {
        new Chart(classesCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($classesLabels) ?>,
                datasets: [{
                    data: <?= json_encode($classesData) ?>,
                    backgroundColor: <?= json_encode($classesColors) ?>,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });
    }

});
</script>
</body>
</html>
