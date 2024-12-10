<?php
require_once('../../config.php');


$beach_id = $_GET['id'] ?? null;

if ($beach_id) {
    $stmt = $db->prepare("DELETE FROM beaches WHERE beach_id = :id");
    $stmt->execute(['id' => $beach_id]);

    $_SESSION['success'] = 'Beach deleted successfully.';
} else {
    $_SESSION['error'] = 'Beach ID not provided.';
}

header('Location: ../../admin/manage_beaches.php');
exit;
