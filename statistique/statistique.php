<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Statistiques';
$activePage = 'statistiques';

$db = Database::getInstance();

// 1. Total Étudiants
$stmt = $db->query("SELECT COUNT(*) FROM etudiants");
$totalEtudiants = $stmt->fetchColumn();

// 2. Taux de Réussite Global
$stmt = $db->query("
    SELECT id_etudiant, AVG(moyenne) as mg 
    FROM notes 
    GROUP BY id_etudiant
");
$averages = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalEvaluated = count($averages);
$admisGlobal = 0;
foreach ($averages as $row) {
    if ($row['mg'] >= 10) {
        $admisGlobal++;
    }
}
$tauxReussite = $totalEvaluated > 0 ? round(($admisGlobal / $totalEvaluated) * 100) : 0;

// 3. Paiements Encaissés
$stmt = $db->query("SELECT COALESCE(SUM(montant_paye), 0) FROM paiements");
$totalPaiements = $stmt->fetchColumn();
if ($totalPaiements >= 1000000) {
    $totalPaiementsStr = number_format($totalPaiements / 1000000, 1, ',', ' ') . 'M FCFA';
} else {
    $totalPaiementsStr = number_format($totalPaiements, 0, ',', ' ') . ' FCFA';
}

// 4. Classes Actives
$stmt = $db->query("SELECT COUNT(*) FROM classes");
$classesActives = $stmt->fetchColumn();

// 5. Répartition par Sexe
$stmt = $db->query("SELECT sexe, COUNT(*) as count FROM etudiants GROUP BY sexe");
$sexes = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$countM = $sexes['M'] ?? 0;
$countF = $sexes['F'] ?? 0;
$totalSexe = $countM + $countF;
$pctM = $totalSexe > 0 ? round(($countM / $totalSexe) * 100) : 0;
$pctF = $totalSexe > 0 ? round(($countF / $totalSexe) * 100) : 0;

// 6. Données par classe
$queryClasses = "
    SELECT 
        c.id_classe,
        c.nom,
        c.niveau,
        COUNT(DISTINCT e.id_etudiant) as effectif
    FROM classes c
    LEFT JOIN etudiants e ON c.id_classe = e.id_classe
    GROUP BY c.id_classe
    ORDER BY c.niveau, c.nom
";
$stmtClasses = $db->query($queryClasses);
$classesData = $stmtClasses->fetchAll(PDO::FETCH_ASSOC);

$classes = [];
foreach ($classesData as $c) {
    $stmtNotes = $db->prepare("
        SELECT n.id_etudiant, AVG(n.moyenne) as mg 
        FROM notes n
        JOIN etudiants e ON n.id_etudiant = e.id_etudiant
        WHERE e.id_classe = ?
        GROUP BY n.id_etudiant
    ");
    $stmtNotes->execute([$c['id_classe']]);
    $etudNotes = $stmtNotes->fetchAll(PDO::FETCH_ASSOC);
    
    $admisC = 0;
    foreach ($etudNotes as $en) {
        if ($en['mg'] >= 10) {
            $admisC++;
        }
    }
    
    $evalues = count($etudNotes);
    $ajournesC = $evalues - $admisC;
    $tauxC = $evalues > 0 ? round(($admisC / $evalues) * 100) : 0;
    
    if ($c['effectif'] == 0) {
        $perf = 'Vide';
        $badge = 'bg-light text-muted';
    } elseif ($evalues == 0) {
        $perf = 'Non évalué';
        $badge = 'bg-secondary-subtle text-secondary border';
    } elseif ($tauxC >= 80) {
        $perf = 'Élite';
        $badge = 'bg-success-subtle text-success';
    } elseif ($tauxC >= 60) {
        $perf = 'Satisfaisant';
        $badge = 'bg-primary-subtle text-primary';
    } elseif ($tauxC >= 50) {
        $perf = 'Moyen';
        $badge = 'bg-warning-subtle text-warning';
    } else {
        $perf = 'Critique';
        $badge = 'bg-danger-subtle text-danger';
    }
    
    $classes[] = [
        'nom' => $c['nom'],
        'effectif' => $c['effectif'],
        'admis' => $admisC,
        'ajournes' => $ajournesC,
        'taux' => $tauxC,
        'performance' => $perf,
        'badge_class' => $badge
    ];
}

// Pour le graphique de répartition des classes (Top 4)
$sortedClasses = $classes;
usort($sortedClasses, function($a, $b) { return $b['effectif'] <=> $a['effectif']; });
$topClasses = array_slice($sortedClasses, 0, 4);
$colors = ['#00236f', '#4059aa', '#1e3a8a', '#d3e4fe']; // SupTech color variations
$conicParts = [];
$currentAngle = 0;
$legendHtml = [];

if ($totalEtudiants > 0) {
    foreach ($topClasses as $idx => $tc) {
        if ($tc['effectif'] > 0) {
            $pct = ($tc['effectif'] / $totalEtudiants) * 100;
            $nextAngle = $currentAngle + $pct;
            $conicParts[] = $colors[$idx] . " " . $currentAngle . "% " . $nextAngle . "%";
            $currentAngle = $nextAngle;
            $legendHtml[] = htmlspecialchars($tc['nom']) . ' (' . round($pct) . '%)';
        }
    }
    // Reste
    if ($currentAngle < 100 && $currentAngle > 0) {
        $conicParts[] = "#f8f9ff " . $currentAngle . "% 100%";
        $legendHtml[] = 'Autres (' . round(100 - $currentAngle) . '%)';
    }
}
$conicGradient = empty($conicParts) ? '#d3e4fe 0% 100%' : implode(', ', $conicParts);
$legendText = empty($legendHtml) ? 'Aucune donnée' : implode(', ', $legendHtml);

?>
<?php require_once __DIR__ . '/../include/head.php'; ?>

<body class="admin-layout">
    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>
    
    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../include/header.php'; ?>
        
        <main class="main-content">
            <!-- Page Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h1 class="page-title">Statistiques & Performances</h1>
                    <p class="page-subtitle mb-0">Tableau de bord analytique basé sur les données en temps réel.</p>
                </div>
                <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2" onclick="window.print()">
                    <span class="material-symbols-outlined">print</span>
                    Imprimer / PDF
                </button>
            </div>

            <!-- Statistiques - 4 cards -->
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card d-flex align-items-center gap-3 h-100">
                        <div class="stat-card-icon primary">
                            <span class="material-symbols-outlined">groups</span>
                        </div>
                        <div>
                            <p class="stat-label mb-0">Total Étudiants</p>
                            <p class="stat-value mb-0"><?= $totalEtudiants ?></p>
                            <small class="text-muted">Inscrits</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card d-flex align-items-center gap-3 h-100">
                        <div class="stat-card-icon secondary">
                            <span class="material-symbols-outlined">school</span>
                        </div>
                        <div>
                            <p class="stat-label mb-0">Taux de Réussite</p>
                            <p class="stat-value mb-0"><?= $tauxReussite ?>%</p>
                            <small class="text-muted">Moy. Gen. >= 10</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card d-flex align-items-center gap-3 h-100">
                        <div class="stat-card-icon tertiary">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <div>
                            <p class="stat-label mb-0">Paiements Encaissés</p>
                            <p class="stat-value mb-0" style="font-size: 1.5rem;"><?= $totalPaiementsStr ?></p>
                            <small class="text-muted">Volume financier</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card d-flex align-items-center gap-3 h-100">
                        <div class="stat-card-icon primary" style="background: rgba(0,0,0,0.05); color: #444;">
                            <span class="material-symbols-outlined">apartment</span>
                        </div>
                        <div>
                            <p class="stat-label mb-0">Classes Actives</p>
                            <p class="stat-value mb-0"><?= $classesActives ?></p>
                            <small class="text-muted">Gérées</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section - 2x2 Grid -->
            <div class="row g-4 mb-4">
                <!-- Répartition par classe -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center p-3">
                            <h5 class="mb-0 fw-semibold fs-6">Répartition par classe (Top 4)</h5>
                            <span class="material-symbols-outlined text-muted" style="font-size: 20px;">pie_chart</span>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                            <div class="mb-4">
                                <div style="width: 160px; height: 160px; margin: 0 auto; border-radius: 50%; background: conic-gradient(<?= $conicGradient ?>); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                    <div style="background: white; border-radius: 50%; width: 110px; height: 110px; display: flex; align-items: center; justify-content: center; flex-direction: column; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                                        <strong style="font-size: 24px; color: var(--suptech-primary);"><?= $totalEtudiants ?></strong>
                                        <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Total</small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center w-100 px-3">
                                <p class="text-muted small mb-0 lh-lg">
                                    <?= $legendText ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Répartition par sexe -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center p-3">
                            <h5 class="mb-0 fw-semibold fs-6">Répartition par sexe</h5>
                            <span class="material-symbols-outlined text-muted" style="font-size: 20px;">wc</span>
                        </div>
                        <div class="card-body d-flex flex-column flex-sm-row align-items-center justify-content-center gap-4 p-4">
                            <?php $sexeGradient = ($totalSexe > 0) ? "conic-gradient(var(--suptech-primary) 0% {$pctM}%, var(--suptech-secondary) {$pctM}% 100%)" : "conic-gradient(#eee 0% 100%)"; ?>
                            <div style="width: 140px; height: 140px; border-radius: 50%; background: <?= $sexeGradient ?>; box-shadow: 0 4px 10px rgba(0,0,0,0.1); flex-shrink: 0;"></div>
                            
                            <div class="w-100" style="max-width: 250px;">
                                <div class="mb-3 p-3 rounded border bg-light">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 12px; height: 12px; border-radius: 3px; background: var(--suptech-primary);"></div>
                                            <strong class="text-dark">Masculin</strong>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary"><?= $pctM ?>%</span>
                                    </div>
                                    <p class="mb-0 text-muted ms-4"><?= $countM ?> étudiant(s)</p>
                                </div>
                                <div class="p-3 rounded border bg-light">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 12px; height: 12px; border-radius: 3px; background: var(--suptech-secondary);"></div>
                                            <strong class="text-dark">Féminin</strong>
                                        </div>
                                        <span class="badge bg-success-subtle text-success"><?= $pctF ?>%</span>
                                    </div>
                                    <p class="mb-0 text-muted ms-4"><?= $countF ?> étudiante(s)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table des résultats par classe -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-bottom p-3">
                    <h5 class="mb-0 fw-semibold fs-6">Détail des performances par classe</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="small text-muted fw-semibold">CLASSE</th>
                                <th class="small text-muted fw-semibold text-center">EFFECTIF</th>
                                <th class="small text-muted fw-semibold text-center">ADMIS</th>
                                <th class="small text-muted fw-semibold text-center">AJOURNÉS</th>
                                <th class="small text-muted fw-semibold text-end">TAUX RÉUSSITE</th>
                                <th class="small text-muted fw-semibold text-center">PERFORMANCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($classes)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Aucune classe trouvée.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($classes as $classe): ?>
                                    <tr>
                                        <td class="fw-medium text-dark"><?= htmlspecialchars($classe['nom']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border"><?= $classe['effectif'] ?></span>
                                        </td>
                                        <td class="text-center text-success fw-medium"><?= $classe['admis'] ?></td>
                                        <td class="text-center text-danger fw-medium"><?= $classe['ajournes'] ?></td>
                                        <td class="text-end fw-bold">
                                            <?= $classe['taux'] ?>%
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= $classe['badge_class'] ?> px-2 py-1" style="font-size: 0.75rem;">
                                                <?= htmlspecialchars($classe['performance']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <?php require_once __DIR__ . '/../include/footer.php'; ?>
</body>
</html>