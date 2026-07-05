<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

$pageTitle = 'Gestion des Salles';
$activePage = 'salles';

// Données de session pour le header.php
$userName = $_SESSION['user_nom'] ?? 'Utilisateur';
$userRole = $_SESSION['user_role'] ?? 'Rôle';
$userInitials = strtoupper(substr($_SESSION['user_nom'] ?? 'U', 0, 1));

// Connexion BDD
$db = Database::getInstance();

// Récupération des salles (données statiques pour l'instant)
$salles = [
    ['id' => 1, 'nom' => 'Salle Cheikh Anta Diop', 'capacite' => 40, 'localisation' => 'Bâtiment A - 1er Étage', 'statut' => 'Disponible', 'type' => 'meeting_room'],
    ['id' => 2, 'nom' => 'Salle Léopold Sédar Senghor', 'capacite' => 35, 'localisation' => 'Bâtiment B - Rez-de-chaussée', 'statut' => 'Occupée', 'type' => 'meeting_room'],
    ['id' => 3, 'nom' => 'Amphi Blaise Diagne', 'capacite' => 80, 'localisation' => 'Aile Nord - 2ème Étage', 'statut' => 'Disponible', 'type' => 'stadium'],
    ['id' => 4, 'nom' => 'Salle Informatique 1', 'capacite' => 25, 'localisation' => 'Pôle Digital - 1er Étage', 'statut' => 'Disponible', 'type' => 'computer'],
    ['id' => 5, 'nom' => 'Salle Informatique 2', 'capacite' => 25, 'localisation' => 'Pôle Digital - 1er Étage', 'statut' => 'Occupée', 'type' => 'computer'],
];

$totalSalles = count($salles);
$disponibles = count(array_filter($salles, fn($s) => $s['statut'] === 'Disponible'));
$occupees = $totalSalles - $disponibles;
$capaciteTotale = array_sum(array_column($salles, 'capacite'));

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="page-title">Gestion des Salles</h2>
                <p class="page-subtitle mb-0">Gérez l'inventaire et la disponibilité des espaces de cours.</p>
            </div>
            <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addSalleModal">
                <span class="material-symbols-outlined">add_circle</span>
                Ajouter une salle
            </button>
        </div>

        <!-- Statistiques -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon primary">
                        <span class="material-symbols-outlined">door_front</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Total Salles</p>
                        <p class="stat-value mb-0"><?= $totalSalles ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon secondary">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Disponibles</p>
                        <p class="stat-value mb-0"><?= $disponibles ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon tertiary">
                        <span class="material-symbols-outlined">pending</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Occupées</p>
                        <p class="stat-value mb-0"><?= $occupees ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon primary">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Capacité Totale</p>
                        <p class="stat-value mb-0"><?= $capaciteTotale ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des salles -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center gap-3">
                <div class="flex-grow-1" style="min-width: 250px;">
                    <div class="input-group">
                        <span class="input-group-text"><span class="material-symbols-outlined">search</span></span>
                        <input class="form-control" placeholder="Rechercher une salle..." type="text" id="searchInput"/>
                    </div>
                </div>
                <div>
                    <span class="text-muted small"><?= count($salles) ?> salles enregistrées</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small text-uppercase fw-bold">NOM DE LA SALLE</th>
                            <th class="small text-uppercase fw-bold">LOCALISATION</th>
                            <th class="small text-uppercase fw-bold">CAPACITÉ</th>
                            <th class="small text-uppercase fw-bold">STATUT</th>
                            <th class="small text-uppercase fw-bold text-end">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salles as $salle): ?>
                            <?php $statusClass = $salle['statut'] === 'Disponible' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-2 rounded-2 bg-primary-subtle text-primary">
                                            <span class="material-symbols-outlined" style="font-size: 18px;"><?= $salle['type'] ?></span>
                                        </div>
                                        <div>
                                            <p class="fw-semibold mb-0"><?= htmlspecialchars($salle['nom']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted"><?= htmlspecialchars($salle['localisation']) ?></small>
                                </td>
                                <td>
                                    <strong><?= $salle['capacite'] ?> places</strong>
                                </td>
                                <td>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= $salle['statut'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-outline-primary" title="Modifier" data-bs-toggle="modal" data-bs-target="#editSalleModal">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="if(confirm('Êtes-vous sûr ?')) { /* delete action */ }">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
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

<!-- Modal Ajouter une salle -->
<div class="modal fade" id="addSalleModal" tabindex="-1" aria-labelledby="addSalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="addSalleModalLabel">
                    <span class="material-symbols-outlined me-2">add_circle</span>Ajouter une nouvelle salle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="addSalleForm">
                    <div class="mb-3">
                        <label for="nomSalle" class="form-label fw-semibold">Nom de la salle *</label>
                        <input type="text" class="form-control" id="nomSalle" placeholder="Ex: Salle Amadou Hampâté Bâ" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacite" class="form-label fw-semibold">Capacité (Places) *</label>
                            <input type="number" class="form-control" id="capacite" placeholder="Ex: 40" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="typeEspace" class="form-label fw-semibold">Type d'espace *</label>
                            <select class="form-select" id="typeEspace" required>
                                <option value="">Sélectionner...</option>
                                <option value="meeting_room">Salle de cours</option>
                                <option value="stadium">Amphithéâtre</option>
                                <option value="computer">Salle Informatique</option>
                                <option value="lab">Laboratoire</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="localisation" class="form-label fw-semibold">Localisation (Bâtiment/Étage) *</label>
                        <input type="text" class="form-control" id="localisation" placeholder="Ex: Bâtiment C, 3ème Étage" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="videoprojecteur">
                        <label class="form-check-label" for="videoprojecteur">
                            Équipée de vidéoprojecteur
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary-suptech" form="addSalleForm">
                    <span class="material-symbols-outlined me-1">save</span>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier une salle -->
<div class="modal fade" id="editSalleModal" tabindex="-1" aria-labelledby="editSalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="editSalleModalLabel">
                    <span class="material-symbols-outlined me-2">edit</span>Modifier la salle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="editSalleForm">
                    <div class="mb-3">
                        <label for="editNomSalle" class="form-label fw-semibold">Nom de la salle *</label>
                        <input type="text" class="form-control" id="editNomSalle" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editCapacite" class="form-label fw-semibold">Capacité (Places) *</label>
                            <input type="number" class="form-control" id="editCapacite" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editTypeEspace" class="form-label fw-semibold">Type d'espace *</label>
                            <select class="form-select" id="editTypeEspace" required>
                                <option value="meeting_room">Salle de cours</option>
                                <option value="stadium">Amphithéâtre</option>
                                <option value="computer">Salle Informatique</option>
                                <option value="lab">Laboratoire</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editLocalisation" class="form-label fw-semibold">Localisation (Bâtiment/Étage) *</label>
                        <input type="text" class="form-control" id="editLocalisation" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="editVideoprojecteur">
                        <label class="form-check-label" for="editVideoprojecteur">
                            Équipée de vidéoprojecteur
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary-suptech" form="editSalleForm">
                    <span class="material-symbols-outlined me-1">save</span>Enregistrer les modifications
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>

<script>
    // Recherche en temps réel
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
