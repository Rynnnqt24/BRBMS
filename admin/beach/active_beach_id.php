<?php
session_start();  // Ensure the session is started

include 'checkuser.php';

if (isset($_GET['beach_id']) && is_numeric($_GET['beach_id'])) {
    $beach_id = (int) $_GET['beach_id'];
    $user_id = $_SESSION['user_id'];

    // Verify ownership
    try {
        $query = "SELECT beach_name FROM beaches WHERE beach_id = :beach_id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':beach_id', $beach_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Set active beach ID and name in session
            $_SESSION['active_beach_id'] = $beach_id;
            $_SESSION['active_beach_name'] = $result['beach_name'];
            header("Location: ../index.php?beach_id=" . $beach_id);
            exit();
        } else {
            // Unauthorized access or invalid beach
            echo "<script>alert('Unauthorized access!'); window.location.href = 'index.php';</script>";
            exit();
        }
    } catch (PDOException $e) {
        // Error handling if query fails
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid beach ID.";
    exit();
}
?>
