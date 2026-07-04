<?php
require_once __DIR__ . '/../include/config.php';

$pageTitle = 'Gestion des Enseignants';
$activePage = 'enseignants';

require_once __DIR__ . '/../include/head.php';

$enseignants = [
    [
        'matricule' => 'ENS-2023-001',
        'prenom' => 'Aminata',
        'nom' => 'Fall',
        'grade' => 'Docteur',
        'specialite' => 'Génie Logiciel',
        'contact' => '+221 77 123 45 67',
        'initials' => 'AF',
        'badgeClass' => 'badge bg-info text-dark',
    ],
    [
        'matricule' => 'ENS-2021-042',
        'prenom' => 'Ibrahima',
        'nom' => 'Gueye',
        'grade' => 'Master',
        'specialite' => 'Réseaux & Sécurité',
        'contact' => '+221 76 987 65 43',
        'initials' => 'IG',
        'badgeClass' => 'badge bg-secondary',
    ],
    [
        'matricule' => 'ENS-2022-015',
        'prenom' => 'Khadija',
        'nom' => 'Sow',
        'grade' => 'Docteur',
        'specialite' => 'Intelligence Artificielle',
        'contact' => '+221 70 456 78 90',
        'initials' => 'KS',
        'badgeClass' => 'badge bg-info text-dark',
    ],
];
?>
<body class="admin-layout">
    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../include/header.php'; ?>

        <main class="main-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h2 class="page-title">Gestion des Enseignants</h2>
                    <p class="page-subtitle mb-0">Gérez la liste complète du personnel enseignant et leurs spécialités.</p>
                </div>
                <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
                    Ajouter un enseignant
                </button>
            </div>

            <div class="filter-bar d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between mb-4">
                <div class="position-relative flex-grow-1" style="max-width: 420px;">
                    <span class="material-symbols-outlined input-icon">search</span>
                    <input type="text" class="form-control ps-5" placeholder="Rechercher par nom, matricule...">
                </div>
                <div class="d-flex gap-2 w-100 w-md-auto">
                    <select class="form-select form-select-sm" style="min-width: 180px;">
                        <option value="">Toutes les spécialités</option>
                        <option value="genie">Génie Logiciel</option>
                        <option value="reseaux">Réseaux & Sécurité</option>
                        <option value="ia">Intelligence Artificielle</option>
                    </select>
                    <button type="button" class="btn btn-outline-suptech d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 20px;">filter_list</span>
                        <span class="d-none d-sm-inline">Filtres</span>
                    </button>
                </div>
            </div>

            <div class="surface-card">
                <div class="table-responsive">
                    <table class="table table-suptech mb-0">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Enseignant</th>
                                <th>Grade</th>
                                <th>Spécialité</th>
                                <th>Contact</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enseignants as $enseignant): ?>
                                <tr>
                                    <td><?= htmlspecialchars($enseignant['matricule']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm bg-light text-primary d-inline-flex align-items-center justify-content-center rounded-circle"> 
                                                <?= htmlspecialchars($enseignant['initials']) ?>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($enseignant['nom']) ?>, <?= htmlspecialchars($enseignant['prenom']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= htmlspecialchars($enseignant['badgeClass']) ?> text-uppercase px-2 py-1">
                                            <?= htmlspecialchars($enseignant['grade']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($enseignant['specialite']) ?></td>
                                    <td><?= htmlspecialchars($enseignant['contact']) ?></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Voir">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="border-top p-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 bg-light">
                    <span class="text-muted small">Affichage 1 à <?= count($enseignants) ?> sur 124 enseignants</span>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Précédent</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Suivant</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </main>
    </div>

    <?php require_once __DIR__ . '/../include/footer.php'; ?>
