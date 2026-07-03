<?php
require_once __DIR__ . '/../include/config.php';

$pageTitle = 'Gestion des Inscriptions';
$activePage = 'inscriptions';
$userName = 'Admin Principal';
$userRole = 'Secrétariat';
$userInitials = 'A';

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <div class="mb-4">
            <h1 class="page-title" style="font-size: 1.5rem;">Gestion des inscriptions</h1>
            <p class="page-subtitle mb-0">Saisie d'une nouvelle inscription administrative.</p>
        </div>

        <div class="row g-4">
            <!-- Colonne principale : formulaire -->
            <div class="col-lg-8">
                <!-- Alerte inscription existante -->
                <div class="alert alert-inscription d-flex align-items-start gap-3 mb-4" role="alert">
                    <span class="material-symbols-outlined text-danger mt-1">warning</span>
                    <div>
                        <h3 class="h6 fw-semibold mb-1">Attention : Inscription existante</h3>
                        <p class="mb-0 small">
                            L'étudiant <strong>Mamadou Diallo</strong> possède déjà un dossier d'inscription actif
                            pour l'année <strong>2025-2026</strong> en <strong>Licence 3</strong>.
                            Veuillez vérifier avant de forcer une nouvelle saisie.
                        </p>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="surface-card">
                    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                        <h2 class="h6 fw-semibold mb-0">Formulaire de sélection</h2>
                        <span class="material-symbols-outlined text-muted">edit_document</span>
                    </div>
                    <div class="p-4">
                        <form action="#" method="POST">
                            <div class="mb-4">
                                <label for="student" class="form-label fw-semibold small">Étudiant</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="student" name="student"
                                           value="Mamadou Diallo" readonly>
                                    <span class="material-symbols-outlined position-absolute top-50 end-0 translate-middle-y me-3 text-muted">person</span>
                                </div>
                                <p class="text-muted small mt-1 mb-0 ms-1">Matricule: STB-23-0492</p>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label for="classe" class="form-label fw-semibold small">Classe</label>
                                    <div class="position-relative">
                                        <select class="form-select" id="classe" name="classe">
                                            <option value="l1">Licence 1</option>
                                            <option value="l2">Licence 2</option>
                                            <option value="l3" selected>Licence 3</option>
                                            <option value="m1">Master 1</option>
                                            <option value="m2">Master 2</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="annee" class="form-label fw-semibold small">Année Universitaire</label>
                                    <select class="form-select" id="annee" name="annee">
                                        <option value="2024-2025">2024-2025</option>
                                        <option value="2025-2026" selected>2025-2026</option>
                                        <option value="2026-2027">2026-2027</option>
                                    </select>
                                </div>
                            </div>

                            <div class="filiere-info d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="d-block stat-label mb-1">Filière Associée</span>
                                    <span class="fw-medium">Management des Affaires Internationales</span>
                                </div>
                                <span class="material-symbols-outlined text-primary opacity-50" style="font-size: 2rem;">public</span>
                            </div>
                        </form>
                    </div>
                    <div class="px-4 py-3 border-top bg-light d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-suptech">Annuler</button>
                        <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                            Inscrire
                            <span class="material-symbols-outlined" style="font-size: 20px;">check_circle</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale : infos étudiant -->
            <div class="col-lg-4">
                <div class="surface-card p-4 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="student-avatar-lg">MD</div>
                        <div>
                            <h3 class="h6 fw-semibold mb-1">Mamadou Diallo</h3>
                            <span class="status-badge">
                                <span class="dot"></span>
                                Dossier Complet
                            </span>
                        </div>
                    </div>
                    <div class="pt-3 border-top">
                        <div class="d-flex justify-content-between py-2 small">
                            <span class="text-muted fw-medium">Statut Financier:</span>
                            <span class="fw-semibold">À jour</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 small">
                            <span class="text-muted fw-medium">Dernière Inscription:</span>
                            <span>L2 (2024-2025)</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 small">
                            <span class="text-muted fw-medium">Bourse:</span>
                            <span>Partielle (30%)</span>
                        </div>
                    </div>
                </div>

                <div class="surface-card p-4" style="background: linear-gradient(to bottom right, var(--suptech-background), rgba(211,228,254,0.3));">
                    <h4 class="h6 fw-semibold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        Procédure d'inscription
                    </h4>
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-2">Vérifiez l'identité et le parcours de l'étudiant.</li>
                        <li class="mb-2">Assurez-vous que l'année académique correspond à la prochaine session.</li>
                        <li>En cas de doublon (alerte rouge), contactez le service scolarité avant de forcer l'enregistrement.</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
