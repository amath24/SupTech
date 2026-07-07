<?php
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

// Seul l'administrateur peut ajouter une séance
if (strtolower(trim($_SESSION['user_role'] ?? '')) !== 'administrateur') {
    header("Location: emploi_du_temps.php");
    exit();
}

$pageTitle = 'Ajouter une séance';
$activePage = 'emploi_du_temps';

$pdo = Database::getInstance();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_assignation = (int)($_POST['id_assignation'] ?? 0);
    $jour = $_POST['jour'] ?? '';
    $heure_debut = $_POST['heure_debut'] ?? '';
    $heure_fin = $_POST['heure_fin'] ?? '';
    $id_salle = (int)($_POST['id_salle'] ?? 0);

    if ($id_assignation > 0 && $jour && $heure_debut && $heure_fin && $id_salle > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO emploi_temps (jour, heure_debut, heure_fin, id_salle, id_assignation) VALUES (:jour, :heure_debut, :heure_fin, :id_salle, :id_assignation)");
            $stmt->execute([
                'jour' => $jour,
                'heure_debut' => $heure_debut,
                'heure_fin' => $heure_fin,
                'id_salle' => $id_salle,
                'id_assignation' => $id_assignation
            ]);
            
            // Get the class ID for redirection
            $stmtClass = $pdo->prepare("SELECT id_classe FROM assignation_modules WHERE id_assignation = :id");
            $stmtClass->execute(['id' => $id_assignation]);
            $classe_id = $stmtClass->fetchColumn();

            header("Location: emploi_du_temps.php?classe_id=" . $classe_id);
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout (Conflit de salle possible) : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

// Fetch Assignations
$sql_assign = "
    SELECT am.id_assignation, c.nom as classe_nom, c.niveau, m.nom as module_nom, e.nom as prof_nom, e.prenom as prof_prenom
    FROM assignation_modules am
    JOIN classes c ON am.id_classe = c.id_classe
    JOIN modules m ON am.id_module = m.id_module
    JOIN enseignants e ON am.id_enseignant = e.id_enseignant
    ORDER BY c.niveau, c.nom, m.nom
";
$assignations = $pdo->query($sql_assign)->fetchAll();

// Fetch Salles
$salles = $pdo->query("SELECT id_salle, nom FROM salles ORDER BY nom")->fetchAll();

require_once __DIR__ . '/../include/head.php';
?>
<body class="admin-layout">
    <?php require_once __DIR__ . '/../include/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../include/header.php'; ?>

        <main class="main-content">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="emploi_du_temps.php" class="btn btn-outline-secondary d-flex align-items-center p-2 rounded-circle" title="Retour">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="page-title mb-0">Ajouter une séance</h2>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="surface-card p-4" style="max-width: 800px;">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Affectation (Classe - Module - Professeur) <span class="text-danger">*</span></label>
                        <select name="id_assignation" class="form-select" required>
                            <option value="">Sélectionnez une affectation</option>
                            <?php foreach ($assignations as $a): ?>
                                <option value="<?= $a['id_assignation'] ?>">
                                    <?= htmlspecialchars($a['niveau'] . ' ' . $a['classe_nom'] . ' | ' . $a['module_nom'] . ' (' . $a['prof_prenom'] . ' ' . $a['prof_nom'] . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jour <span class="text-danger">*</span></label>
                            <select name="jour" class="form-select" required>
                                <option value="">Sélectionnez un jour</option>
                                <?php foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $j): ?>
                                    <option value="<?= $j ?>"><?= $j ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Salle <span class="text-danger">*</span></label>
                            <select name="id_salle" class="form-select" required>
                                <option value="">Sélectionnez une salle</option>
                                <?php foreach ($salles as $s): ?>
                                    <option value="<?= $s['id_salle'] ?>"><?= htmlspecialchars($s['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Heure de début <span class="text-danger">*</span></label>
                            <input type="time" name="heure_debut" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Heure de fin <span class="text-danger">*</span></label>
                            <input type="time" name="heure_fin" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="emploi_du_temps.php" class="btn btn-light">Annuler</a>
                        <button type="submit" class="btn btn-primary-suptech">Enregistrer la séance</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <?php require_once __DIR__ . '/../include/footer.php'; ?>
