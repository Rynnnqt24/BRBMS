<?php
include 'checkuser.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT beach_id, beach_name FROM beaches WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Your Beaches</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<a href='switch_beach.php?beach_id={$row['beach_id']}'>{$row['beach_name']}</a><br>";
}
?>
