<?php
require_once __DIR__ . '/../include/config.php';

$pageTitle = 'Emploi du temps';
$activePage = 'emploi_du_temps';

require_once __DIR__ . '/../include/head.php';

$seances = [
    ['jour' => 'Lun', 'heure' => '08h00 - 11h00', 'intitule' => 'PHP Avancé', 'enseignant' => 'Dr. Fall', 'salle' => 'Salle C.A. Diop', 'niveau' => 'Licence 3'],
    ['jour' => 'Mar', 'heure' => '14h00 - 17h00', 'intitule' => 'Bases de données', 'enseignant' => 'Pr. Ndiaye', 'salle' => 'Amphi B', 'niveau' => 'Master 1'],
    ['jour' => 'Mer', 'heure' => '10h00 - 12h00', 'intitule' => 'Gestion Projets', 'enseignant' => 'Dr. Sow', 'salle' => 'Salle 102', 'niveau' => 'Master 1', 'conflict' => true],
];
?>
<body class="admin-layout">
    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../include/header.php'; ?>

        <main class="main-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h2 class="page-title">Emploi du temps</h2>
                    <p class="page-subtitle mb-0">Gestion hebdomadaire des cours et ressources</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <select class="form-select form-select-sm me-2" style="min-width:200px;">
                        <option>Master 1 - Informatique</option>
                        <option>Licence 3 - Gestion</option>
                        <option>Master 2 - Finance</option>
                    </select>
                    <select class="form-select form-select-sm me-2" style="min-width:220px;">
                        <option>Semaine 42 (16 Oct - 21 Oct)</option>
                        <option>Semaine 43 (23 Oct - 28 Oct)</option>
                    </select>
                    <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size:18px;">add</span>
                        Ajouter une séance
                    </button>
                </div>
            </div>

            <?php if (array_filter($seances, fn($s)=> isset($s['conflict']))) : ?>
            <div class="alert alert-danger d-flex justify-content-between align-items-start" role="alert">
                <div>
                    <h5 class="mb-1">Conflit d'horaire détecté</h5>
                    <div>Le Professeur <strong>Dr. Sow</strong> est assigné à deux cours simultanés le Mercredi à 10h00.</div>
                </div>
                <div class="ms-3">
                    <button class="btn btn-danger btn-sm">Résoudre</button>
                </div>
            </div>
            <?php endif; ?>

            <div class="surface-card">
                <div class="table-responsive p-3">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px">Heure</th>
                                <th>Lundi 16</th>
                                <th>Mardi 17</th>
                                <th>Mercredi 18</th>
                                <th>Jeudi 19</th>
                                <th>Vendredi 20</th>
                                <th>Samedi 21</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-semibold">08h00 - 11h00</td>
                                <td>
                                    <div class="card p-2 mb-2">
                                        <div class="fw-semibold">PHP Avancé</div>
                                        <div class="small text-muted">Dr. Fall — Salle C.A. Diop</div>
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">09h00 - 12h00</td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="card p-2 mb-2 border-danger">
                                        <div class="fw-semibold">Gestion Projets <span class="badge bg-danger ms-2">Conflit</span></div>
                                        <div class="small text-muted">Dr. Sow — Salle 102</div>
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">14h00 - 17h00</td>
                                <td></td>
                                <td>
                                    <div class="card p-2 mb-2">
                                        <div class="fw-semibold">Bases de données</div>
                                        <div class="small text-muted">Pr. Ndiaye — Amphi B</div>
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <?php require_once __DIR__ . '/../include/footer.php'; ?>