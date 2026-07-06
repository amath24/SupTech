<?php
require_once __DIR__ . '/../include/config/database.php';

try {
    $db = Database::getInstance();
    
    // Check if photo column exists
    $stmt = $db->query("SHOW COLUMNS FROM utilisateurs LIKE 'photo'");
    if ($stmt->rowCount() == 0) {
        $db->exec("ALTER TABLE utilisateurs ADD COLUMN photo VARCHAR(255) DEFAULT NULL");
        echo "Column 'photo' added successfully.\n";
    } else {
        echo "Column 'photo' already exists.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
