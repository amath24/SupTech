<?php
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if (strtolower(trim($_SESSION['user_role'] ?? '')) !== 'administrateur') {
    header("Location: emploi_du_temps.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: emploi_du_temps.php");
    exit();
}

$id_seance = (int)$_GET['id'];
$pdo = Database::getInstance();

try {
    // Get class id before deleting to redirect back to the same class
    $stmtClass = $pdo->prepare("
        SELECT am.id_classe 
        FROM emploi_temps et
        JOIN assignation_modules am ON et.id_assignation = am.id_assignation
        WHERE et.id_seance = :id
    ");
    $stmtClass->execute(['id' => $id_seance]);
    $classe_id = $stmtClass->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM emploi_temps WHERE id_seance = :id");
    $stmt->execute(['id' => $id_seance]);

    if ($classe_id) {
        header("Location: emploi_du_temps.php?classe_id=" . $classe_id);
    } else {
        header("Location: emploi_du_temps.php");
    }
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
