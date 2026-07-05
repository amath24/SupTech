<?php
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$activePage = 'statistiques';
$userName = $_SESSION['user_name'] ?? 'Admin';
$userRole = $_SESSION['user_role'] ?? 'Admin';
$userInitials = strtoupper(substr($userName, 0, 1));

// Données statistiques
$totalEtudiants = 342;
$tauxReussite = 78;
$totalPaiements = 45.2;
$classesActives = 12;

// Données par classe
$classes = [
    ['nom' => 'M1 Génie Logiciel', 'effectif' => 45, 'admis' => 42, 'ajournes' => 3, 'taux' => 94, 'performance' => 'Élite', 'badge_class' => 'bg-success-subtle text-success'],
    ['nom' => 'L3 Développement Web', 'effectif' => 88, 'admis' => 72, 'ajournes' => 16, 'taux' => 82, 'performance' => 'Satisfaisant', 'badge_class' => 'bg-primary-subtle text-primary'],
    ['nom' => 'L2 Réseaux & Télécoms', 'effectif' => 64, 'admis' => 44, 'ajournes' => 20, 'taux' => 68, 'performance' => 'Moyen', 'badge_class' => 'bg-warning-subtle text-warning'],
    ['nom' => 'BTS Commerce', 'effectif' => 145, 'admis' => 61, 'ajournes' => 84, 'taux' => 42, 'performance' => 'Critique', 'badge_class' => 'bg-danger-subtle text-danger'],
];
?>

<?php require_once __DIR__ . '/../include/head.php'; ?>

<div class="wrapper">
    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>
    
    <main class="main-wrapper">
        <?php require_once __DIR__ . '/../include/header.php'; ?>
        
        <div class="main-content">
            <!-- Page Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h2 class="page-title">Statistiques</h2>
                    <p class="page-subtitle mb-0">Tableau de bord analytique et indicateurs de performance.</p>
                </div>
                <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined">download</span>
                    Exporter en PDF
                </button>
            </div>

            <!-- Statistiques - 4 cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card d-flex align-items-center gap-3">
                        <div class="stat-card-icon primary">
                            <span class="material-symbols-outlined">groups</span>
                        </div>
                        <div>
                            <p class="stat-label mb-0">Total Étudiants</p>
                            <p class="stat-value mb-0"><?= $totalEtudiants ?></p>
                            <small class="text-success">+5% vs 2024</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card d-flex align-items-center gap-3">
                        <div class="stat-card-icon secondary">
                            <span class="material-symbols-outlined">trending_up</span>
                        </div>
                        <div>
                            <p class="stat-label mb-0">Taux de Réussite</p>
                            <p class="stat-value mb-0"><?= $tauxReussite ?>%</p>
                            <small class="text-muted">Stable</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card d-flex align-items-center gap-3">
                        <div class="stat-card-icon tertiary">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <div>
                            <p class="stat-label mb-0">Paiements Encaissés</p>
                            <p class="stat-value mb-0"><?= $totalPaiements ?>M FCFA</p>
                            <small class="text-warning">85% Recouvré</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card d-flex align-items-center gap-3">
                        <div class="stat-card-icon primary">
                            <span class="material-symbols-outlined">apartment</span>
                        </div>
                        <div>
                            <p class="stat-label mb-0">Classes Actives</p>
                            <p class="stat-value mb-0"><?= $classesActives ?></p>
                            <small class="text-muted">En cours</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section - 2x2 Grid -->
            <div class="row g-3 mb-4">
                <!-- Répartition par classe -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">Répartition par classe</h5>
                            <span class="material-symbols-outlined cursor-pointer text-muted">more_vert</span>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center" style="height: 300px;">
                            <div class="text-center">
                                <div class="mb-3">
                                    <div style="width: 150px; height: 150px; margin: 0 auto; border-radius: 50%; background: conic-gradient(#00236f 0% 30%, #4059aa 30% 55%, #1e3a8a 55% 80%, #d3e4fe 80% 100%); display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                        <div style="background: white; border-radius: 50%; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                            <strong style="font-size: 20px;">342</strong>
                                            <small class="text-muted text-uppercase">Total</small>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">L3 Dev Web (30%), L2 Réseaux (25%), M1 GL (25%), BTS Commerce (20%)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Répartition par sexe -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">Répartition par sexe</h5>
                            <span class="material-symbols-outlined cursor-pointer text-muted">more_vert</span>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center gap-4" style="height: 300px;">
                            <div style="width: 150px; height: 150px; border-radius: 50%; background: conic-gradient(#00236f 0% 58%, #006c49 58% 100%);"></div>
                            <div>
                                <div class="mb-3 p-3 rounded border">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div style="width: 12px; height: 12px; border-radius: 2px; background: #00236f;"></div>
                                        <strong class="text-primary">Masculin (58%)</strong>
                                    </div>
                                    <p class="mb-0" style="font-size: 20px; font-weight: bold; margin-left: 20px;">198</p>
                                </div>
                                <div class="p-3 rounded border">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div style="width: 12px; height: 12px; border-radius: 2px; background: #006c49;"></div>
                                        <strong class="text-success">Féminin (42%)</strong>
                                    </div>
                                    <p class="mb-0" style="font-size: 20px; font-weight: bold; margin-left: 20px;">144</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table des résultats par classe -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Détail des résultats par classe</h5>
                    <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined">filter_list</span>
                        Filtrer
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="small text-uppercase fw-bold">CLASSE</th>
                                <th class="small text-uppercase fw-bold text-center">EFFECTIF</th>
                                <th class="small text-uppercase fw-bold text-center">ADMIS</th>
                                <th class="small text-uppercase fw-bold text-center">AJOURNÉS</th>
                                <th class="small text-uppercase fw-bold text-end">TAUX RÉUSSITE</th>
                                <th class="small text-uppercase fw-bold text-center">PERFORMANCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classes as $classe): ?>
                                <tr class="<?= $classe['taux'] >= 80 ? 'table-success' : ($classe['taux'] < 50 ? 'table-danger' : '') ?>">
                                    <td class="fw-semibold"><?= htmlspecialchars($classe['nom']) ?></td>
                                    <td class="text-center"><?= $classe['effectif'] ?></td>
                                    <td class="text-center"><?= $classe['admis'] ?></td>
                                    <td class="text-center"><?= $classe['ajournes'] ?></td>
                                    <td class="text-end fw-bold"><?= $classe['taux'] ?>%</td>
                                    <td class="text-center">
                                        <span class="badge <?= $classe['badge_class'] ?>"><?= htmlspecialchars($classe['performance']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">Affichage de 1-4 sur 12 classes</small>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" disabled>
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>

<script>
    // Recherche en temps réel (si nécessaire)
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>