<?php
$page_required_role = ['Administrateur'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Gestion des Utilisateurs';
$activePage = 'parametres';

$db = Database::getInstance();

// Récupérer les utilisateurs (Administrateurs et Scolarité)
$queryStr = "
    SELECT u.id_utilisateur, u.nom, u.prenom, u.email, u.statut, u.date_creation, r.libelle as role_nom, u.id_role 
    FROM utilisateurs u 
    JOIN roles r ON u.id_role = r.id_role 
    WHERE r.id_role IN (1, 3) 
    ORDER BY u.nom, u.prenom
";
$utilisateurs = $db->query($queryStr)->fetchAll();

// Récupérer les rôles possibles pour l'ajout
$roles = $db->query("SELECT id_role, libelle FROM roles WHERE id_role IN (1, 3)")->fetchAll();

require_once __DIR__ . '/../include/head.php';
?>
<body class="admin-layout">
    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../include/header.php'; ?>

        <main class="main-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <nav class="breadcrumb-custom mb-2">
                        <span class="material-symbols-outlined" style="font-size: 14px;">home</span>
                        <span>/</span>
                        <span>Paramètres</span>
                        <span>/</span>
                        <span class="active">Utilisateurs</span>
                    </nav>
                    <h1 class="page-title">Gestion des Administrateurs & Scolarité</h1>
                    <p class="page-subtitle mb-0">Ajoutez ou modifiez les accès pour le personnel de l'établissement.</p>
                </div>
                <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <span class="material-symbols-outlined" style="font-size: 20px;">person_add</span>
                    Ajouter un utilisateur
                </button>
            </div>

            <!-- Notifications -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 border-0 shadow-sm" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
                    <span class="material-symbols-outlined">check_circle</span>
                    <div><?= htmlspecialchars($_SESSION['success']) ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 border-0 shadow-sm" role="alert" style="background-color: #f8d7da; color: #842029;">
                    <span class="material-symbols-outlined">error</span>
                    <div><?= htmlspecialchars($_SESSION['error']) ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="surface-card">
                <div class="table-responsive">
                    <table class="table table-suptech mb-0">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Date d'ajout</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($utilisateurs as $user): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center rounded-circle fw-bold"> 
                                                <?= mb_strtoupper(mb_substr($user['prenom'], 0, 1) . mb_substr($user['nom'], 0, 1)) ?>
                                            </div>
                                            <div class="fw-semibold text-dark"><?= htmlspecialchars($user['nom'] . ', ' . $user['prenom']) ?></div>
                                        </div>
                                    </td>
                                    <td class="text-muted"><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-light border text-dark fw-normal"><?= htmlspecialchars($user['role_nom']) ?></span>
                                    </td>
                                    <td class="text-muted small"><?= date('d/m/Y', strtotime($user['date_creation'])) ?></td>
                                    <td>
                                        <?php if ($user['statut'] === 'actif'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php 
                                        $isAnotherAdmin = ($user['id_role'] == 1 && $user['id_utilisateur'] != $_SESSION['user_id']);
                                        ?>
                                        <div class="d-flex justify-content-end gap-2">
                                            <?php if (!$isAnotherAdmin): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" title="Modifier"
                                                    data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                    data-id="<?= $user['id_utilisateur'] ?>"
                                                    data-nom="<?= htmlspecialchars($user['nom'], ENT_QUOTES) ?>"
                                                    data-prenom="<?= htmlspecialchars($user['prenom'], ENT_QUOTES) ?>"
                                                    data-email="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>"
                                                    data-role="<?= $user['id_role'] ?>"
                                                    data-statut="<?= $user['statut'] ?>"
                                                    onclick="ouvrirModalModifUser(this)">
                                                    <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($user['id_utilisateur'] != $_SESSION['user_id']): ?>
                                                <form action="supprimer_utilisateur.php" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment désactiver (ou supprimer) cet utilisateur ?');">
                                                    <input type="hidden" name="id_utilisateur" value="<?= $user['id_utilisateur'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" title="Supprimer">
                                                        <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary small align-self-center">Vous-même</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modale Ajout Utilisateur -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 0.75rem;">
                <div class="modal-header bg-primary text-white" style="border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined">person_add</span>
                        Ajouter un utilisateur
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="ajouter_utilisateur.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="alert alert-info d-flex align-items-center gap-2 mb-4" role="alert">
                            <span class="material-symbols-outlined">info</span>
                            <div>Le mot de passe par défaut sera généré automatiquement et sera : <strong>Pass123!</strong></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="prenom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Email (Identifiant) <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required placeholder="prenom.nom@suptech.sn">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Rôle <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_role" required>
                                    <option value="">Sélectionner un rôle</option>
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= $r['id_role'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3" style="border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 20px;">save</span>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modale Modification Utilisateur -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 0.75rem;">
                <div class="modal-header text-white d-flex align-items-center gap-2" style="background: linear-gradient(135deg, #00236f 0%, #1a56db 100%); border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                    <span class="material-symbols-outlined">manage_accounts</span>
                    <h5 class="modal-title mb-0">Modifier l'utilisateur</h5>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="modifier_utilisateur.php" method="POST">
                    <input type="hidden" name="id_utilisateur" id="edit_id_utilisateur">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="prenom" id="edit_prenom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom" id="edit_nom" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Email (Identifiant) <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="edit_email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Rôle <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_role" id="edit_id_role" required>
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= $r['id_role'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Statut <span class="text-danger">*</span></label>
                                <select class="form-select" name="statut" id="edit_statut" required>
                                    <option value="actif">Actif</option>
                                    <option value="inactif">Inactif</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-warning small mb-0 py-2">
                                    Laissez le mot de passe vide si vous ne souhaitez pas le modifier.
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Nouveau mot de passe</label>
                                <input type="password" class="form-control" name="mot_de_passe" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3" style="border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 20px;">save</span>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function ouvrirModalModifUser(btn) {
        document.getElementById('edit_id_utilisateur').value = btn.dataset.id;
        document.getElementById('edit_nom').value = btn.dataset.nom;
        document.getElementById('edit_prenom').value = btn.dataset.prenom;
        document.getElementById('edit_email').value = btn.dataset.email;
        document.getElementById('edit_id_role').value = btn.dataset.role;
        document.getElementById('edit_statut').value = btn.dataset.statut;
    }
    </script>

    <?php require_once __DIR__ . '/../include/footer.php'; ?>
</body>
</html>
