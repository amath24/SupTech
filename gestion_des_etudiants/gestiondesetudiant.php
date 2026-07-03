<?php
require_once __DIR__ . '/../include/config.php';

$pageTitle = 'Gestion des Étudiants';
$activePage = 'etudiants';

require_once __DIR__ . '/../include/head.php';

$etudiants = [
    [
        'photo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAcG-BCqChRXsKOuMeuPoVwHF5200_xxLO8fbKmtaI_4C3sizkvyutNM5Fawre-Snh98bcmfqdiyRUGZljk__FrSwo__3gioZLYr880X29tgCxu9nMbmizlriJzJ4AhA_g_ZTWl7jDYm_MRJpRDye0qRbZdkBMDuph7y2yEcaGvhp6EX7RozScCyUt4CW9CdMJVeVlsOD53HeDAMNGaJvFOwbzRk8OlbQMk_LyPtqDVboI1ZiJIxhq5kA',
        'matricule' => 'STB-2025-001',
        'nom' => 'Diallo',
        'prenom' => 'Mamadou',
        'classe' => 'Licence 3 Développement Web',
    ],
    [
        'photo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBOBc12MX22TrU7asl7pdggcVIIQzi3kmdGLpu336wiV_see9WpM40bpZUtTRYcB-rJCsx7v2dxXnQ5yZtXklDIGVd613PK9EyqnNWk8Sa3qZr0MbAODCm5hAanisky_BCFoJCmTQVYs5u8CFCkO29SORI0ABZuK3gqKilHLyR7jcl0R_c07kOKGsEFPx-qJjRW6vKL5-7qun5S9NFazpKBFDWnL_wCCYYgFQ0JZ6WlSL2BgmJYekmK1Q',
        'matricule' => 'STB-2025-002',
        'nom' => 'Ndiaye',
        'prenom' => 'Fatou',
        'classe' => 'Licence 3 Développement Web',
    ],
    [
        'photo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDR1askVXyhNHBZqQHgdz348Wxc37ZRpEj7RaUfmGzryOWT-aY40gielogaTTuIQNIpVfnS0JqYBslgPqy8HaIu03b54pktUygGNnR82WmhNiRVF-Cr3_voEfv4d2XzPoIqnM4xOJEQ1h4eHNy_ISoEnAFnRLLsnVpq_CM7_UVMSPttZukJKuJjmZXIcDZ7RqzMZoIAdjr8wIaCdYw_xsjvyO-pf8-SDdRVQ-wXSJo1AUsZPFQIT9P9cg',
        'matricule' => 'STB-2025-003',
        'nom' => 'Diop',
        'prenom' => 'Cheikh',
        'classe' => 'Licence 3 Développement Web',
    ],
];
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <!-- En-tête -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="page-title" style="font-size: 1.5rem;">Gestion des Étudiants</h2>
                <p class="page-subtitle mb-0">Gérez la liste complète des étudiants inscrits pour l'année académique en cours.</p>
            </div>
            <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
                Ajouter un étudiant
            </button>
        </div>

        <!-- Barre de recherche / filtres -->
        <div class="filter-bar d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between mb-4">
            <div class="position-relative flex-grow-1" style="max-width: 400px;">
                <span class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 20px;">search</span>
                <input type="text" class="form-control ps-5" placeholder="Rechercher par nom, matricule...">
            </div>
            <div class="d-flex gap-2 w-100 w-md-auto">
                <select class="form-select form-select-sm" style="min-width: 180px;">
                    <option value="">Toutes les classes</option>
                    <option value="l3">Licence 3 Développement Web</option>
                    <option value="l2">Licence 2 Informatique</option>
                </select>
                <button type="button" class="btn btn-outline-suptech d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 20px;">filter_list</span>
                    <span class="d-none d-sm-inline">Filtres</span>
                </button>
            </div>
        </div>

        <!-- Tableau des étudiants -->
        <div class="surface-card">
            <div class="table-responsive">
                <table class="table table-suptech mb-0">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Classe</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($etudiants as $etudiant): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($etudiant['photo']) ?>"
                                     alt="Photo <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?>"
                                     class="student-photo">
                            </td>
                            <td class="fw-medium"><?= htmlspecialchars($etudiant['matricule']) ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($etudiant['nom']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars($etudiant['prenom']) ?></td>
                            <td><span class="badge-classe"><?= htmlspecialchars($etudiant['classe']) ?></span></td>
                            <td class="text-end">
                                <div class="table-actions d-flex justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-link text-muted p-1" title="Voir">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-link text-muted p-1" title="Modifier">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-link text-danger p-1" title="Supprimer">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="border-top p-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 bg-light">
                <span class="text-muted small">Affichage 1 à 3 sur 15 étudiants</span>
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
