<?php
require_once __DIR__ . '/../include/config.php';

$pageTitle = 'Gestion des paiements';
$activePage = 'paiements';
$userName = 'Aminata Sow';
$userRole = 'Profil Utilisateur';
$userInitials = 'AS';

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <div class="mx-auto" style="max-width: 1280px;">
            <!-- En-tête -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h2 class="page-title">Mamadou Diallo</h2>
                    <p class="page-subtitle mb-0 d-flex flex-wrap align-items-center gap-2">
                        <span class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 16px;">badge</span>
                            Matricule: ETD-2023-0145
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 16px;">school</span>
                            Licence 3 - Finance
                        </span>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-suptech d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 18px;">print</span>
                        Imprimer le reçu
                    </button>
                    <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span>
                        Ajouter un paiement
                    </button>
                </div>
            </div>

            <!-- Résumé financier -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="payment-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 text-muted mb-0">Scolarité annuelle</h3>
                            <span class="material-symbols-outlined text-primary bg-primary-subtle p-2 rounded-circle">account_balance</span>
                        </div>
                        <p class="amount mb-0">500 000 <span class="amount-unit">FCFA</span></p>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="payment-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 text-muted mb-0">Montant payé</h3>
                            <span class="material-symbols-outlined text-success bg-success-subtle p-2 rounded-circle">check_circle</span>
                        </div>
                        <p class="amount text-success mb-0">250 000 <span class="amount-unit">FCFA</span></p>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 50%"></div>
                        </div>
                        <p class="text-muted small mt-2 mb-0">50% réglé</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="payment-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 text-muted mb-0">Reste à payer</h3>
                            <span class="material-symbols-outlined p-2 rounded-circle" style="color: #ff8f4f; background: var(--suptech-tertiary-fixed);">pending_actions</span>
                        </div>
                        <p class="amount mb-0" style="color: #ff8f4f;">250 000 <span class="amount-unit">FCFA</span></p>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar" style="width: 50%; background: #ff8f4f;"></div>
                        </div>
                        <p class="text-danger small mt-2 mb-0 d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 14px;">warning</span>
                            Échéance: 15 Mars 2024
                        </p>
                    </div>
                </div>
            </div>

            <!-- Historique des paiements -->
            <div class="surface-card">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h3 class="h5 fw-semibold mb-0">Historique des paiements</h3>
                    <button type="button" class="btn btn-link text-primary p-0">
                        <span class="material-symbols-outlined">filter_list</span>
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-suptech mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Référence</th>
                                <th>Mode</th>
                                <th>Motif</th>
                                <th class="text-end">Montant</th>
                                <th class="text-center">Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>12 Nov 2023</td>
                                <td class="text-primary fw-medium">TRX-9824-A</td>
                                <td>
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="material-symbols-outlined text-muted" style="font-size: 16px;">account_balance_wallet</span>
                                        Espèces
                                    </span>
                                </td>
                                <td>Mensualité 1 &amp; 2</td>
                                <td class="text-end fw-medium">150 000 FCFA</td>
                                <td class="text-center"><span class="badge-validated">Validé</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-link text-muted p-1" title="Reçu">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">receipt_long</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-link text-muted p-1" title="Modifier">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>05 Sep 2023</td>
                                <td class="text-primary fw-medium">TRX-8102-B</td>
                                <td>
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="material-symbols-outlined text-muted" style="font-size: 16px;">credit_card</span>
                                        Chèque
                                    </span>
                                </td>
                                <td>Frais d'inscription</td>
                                <td class="text-end fw-medium">100 000 FCFA</td>
                                <td class="text-center"><span class="badge-validated">Validé</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-link text-muted p-1" title="Reçu">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">receipt_long</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-link text-muted p-1" title="Modifier">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <span class="material-symbols-outlined d-block mb-2 text-secondary" style="font-size: 2.5rem;">history</span>
                                    Fin de l'historique des transactions.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
