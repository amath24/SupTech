<?php
require_once __DIR__ . '/../include/config.php';

$pageTitle = 'Gestion des notes';
$activePage = 'notes';
$userName = 'Profil Utilisateur';
$userRole = 'Admin';
$userInitials = 'AD';

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
                    <span>Notes</span>
                    <span>/</span>
                    <span class="active">Saisie</span>
                </nav>
                <h2 class="page-title" style="font-size: 1.5rem;">Saisie des notes : Développement Web</h2>
                <p class="page-subtitle mb-0">L3 Informatique - Semestre 5 | Prof. Diallo</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-suptech d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px;">download</span>
                    Exporter
                </button>
                <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                    Enregistrer les notes
                </button>
            </div>
        </div>

        <!-- Cartes résumé -->
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="h6 fw-semibold mb-0">Effectif</h3>
                        <span class="summary-card-icon bg-primary-subtle text-primary">
                            <span class="material-symbols-outlined">groups</span>
                        </span>
                    </div>
                    <div class="stat-value">42</div>
                    <p class="text-muted small mb-0">Étudiants inscrits</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="h6 fw-semibold mb-0">Saisies</h3>
                        <span class="summary-card-icon bg-success-subtle text-success">
                            <span class="material-symbols-outlined">check_circle</span>
                        </span>
                    </div>
                    <div class="stat-value">38<span class="fs-5 text-muted">/42</span></div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 90%"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="h6 fw-semibold mb-0">Moyenne Classe</h3>
                        <span class="summary-card-icon" style="background: var(--suptech-tertiary-fixed); color: #ff8f4f;">
                            <span class="material-symbols-outlined">analytics</span>
                        </span>
                    </div>
                    <div class="stat-value">14.2</div>
                    <p class="text-success small mb-0 d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size: 14px;">trending_up</span>
                        +0.5 vs S4
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="summary-card info-card">
                    <h3 class="h6 fw-semibold mb-2">Pondération</h3>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="d-flex justify-content-between py-1"><span>Contrôle Continu (CC):</span> <span class="fw-medium text-dark">30%</span></li>
                        <li class="d-flex justify-content-between py-1"><span>Travaux Pratiques (TP):</span> <span class="fw-medium text-dark">20%</span></li>
                        <li class="d-flex justify-content-between py-1"><span>Examen Final:</span> <span class="fw-medium text-dark">50%</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tableau des notes -->
        <div class="surface-card d-flex flex-column" style="height: 500px;">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="position-relative" style="width: 280px;">
                    <span class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y ms-2 text-muted" style="font-size: 18px;">search</span>
                    <input type="text" class="form-control form-control-sm ps-5" placeholder="Rechercher un étudiant...">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted">Statut:</span>
                    <span class="badge rounded-pill bg-success-subtle text-success" style="font-size: 10px;">Brouillon</span>
                </div>
            </div>

            <div class="table-responsive flex-grow-1 overflow-auto">
                <table class="table table-suptech notes-table mb-0">
                    <thead class="sticky-top">
                        <tr>
                            <th>Matricule</th>
                            <th style="width: 25%;">Nom Étudiant</th>
                            <th class="text-center">Note CC<br><small class="fw-normal text-muted">/20 (30%)</small></th>
                            <th class="text-center">Note TP<br><small class="fw-normal text-muted">/20 (20%)</small></th>
                            <th class="text-center">Examen<br><small class="fw-normal text-muted">/20 (50%)</small></th>
                            <th class="text-center bg-light">Moyenne</th>
                            <th class="text-center">Mention</th>
                            <th class="text-center">Classement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-muted">ETU-2023-001</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-sm" style="background: #b6c4ff; color: var(--suptech-primary);">ND</span>
                                    <span class="fw-medium">Ndiaye, Aminata</span>
                                </div>
                            </td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto" min="0" max="20" step="0.5" value="15.5"></td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto" min="0" max="20" step="0.5" value="16"></td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto bg-light" min="0" max="20" step="0.5" value="14"></td>
                            <td class="text-center fw-bold text-primary bg-light moyenne-cell">14.85</td>
                            <td class="text-center mention-cell"><span class="badge-mention bien">Bien</span></td>
                            <td class="text-center text-muted">4/42</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ETU-2023-014</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-sm" style="background: var(--suptech-secondary-container); color: #00714d;">DF</span>
                                    <span class="fw-medium">Diop, Fallou</span>
                                </div>
                            </td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto" min="0" max="20" step="0.5" value="18"></td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto" min="0" max="20" step="0.5" value="19"></td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto bg-light" min="0" max="20" step="0.5" value="17.5"></td>
                            <td class="text-center fw-bold text-success bg-light moyenne-cell">17.95</td>
                            <td class="text-center mention-cell"><span class="badge-mention tres-bien">Très Bien</span></td>
                            <td class="text-center fw-bold text-success">1/42</td>
                        </tr>
                        <tr class="row-incomplete">
                            <td class="text-muted">ETU-2023-022</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-sm bg-light">SS</span>
                                    <span class="fw-medium">Sow, Seydou</span>
                                </div>
                            </td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto" min="0" max="20" step="0.5" value="12"></td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto" min="0" max="20" step="0.5" value="11.5"></td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input error mx-auto bg-light" min="0" max="20" step="0.5" placeholder="--"></td>
                            <td class="text-center fw-bold text-muted bg-light moyenne-cell">--</td>
                            <td class="text-center mention-cell"><span class="badge-mention incomplet">Incomplet</span></td>
                            <td class="text-center text-muted">--</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ETU-2023-031</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-sm" style="background: var(--suptech-tertiary-fixed); color: #341100;">KF</span>
                                    <span class="fw-medium">Kane, Fatou</span>
                                </div>
                            </td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input warning mx-auto" min="0" max="20" step="0.5" value="9"></td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto" min="0" max="20" step="0.5" value="13"></td>
                            <td class="text-center"><input type="number" class="form-control form-control-sm note-input mx-auto bg-light" min="0" max="20" step="0.5" value="10"></td>
                            <td class="text-center fw-bold bg-light moyenne-cell">10.30</td>
                            <td class="text-center mention-cell"><span class="badge-mention passable">Passable</span></td>
                            <td class="text-center text-muted">28/42</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top d-flex justify-content-between align-items-center">
                <span class="small text-muted">Affichage 1-4 sur 42 étudiants</span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#"><span class="material-symbols-outlined" style="font-size: 16px;">chevron_left</span></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <li class="page-item"><a class="page-link" href="#"><span class="material-symbols-outlined" style="font-size: 16px;">chevron_right</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Actions finales -->
        <div class="d-flex justify-content-end gap-3 mt-4 pb-4">
            <button type="button" class="btn btn-outline-suptech px-4">Annuler</button>
            <button type="button" class="btn btn-secondary-suptech d-flex align-items-center gap-2 px-4">
                <span class="material-symbols-outlined">done_all</span>
                Valider et publier les notes
            </button>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
