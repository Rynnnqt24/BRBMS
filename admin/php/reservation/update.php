<?php
// Include database connection and PHPMailer
require_once '../../../config/db.php'; // Adjust the path based on your folder structure
require_once '../phpmailer/PHPMailerAutoload.php'; // Adjust for your PHPMailer setup

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and fetch data
    $reservation_id = isset($_POST['reservation_id']) ? intval($_POST['reservation_id']) : null;
    $new_status = isset($_POST['status']) ? $_POST['status'] : null;
    $owner_note = isset($_POST['note']) ? trim($_POST['note']) : '';
    
    // Validate input
    if (!$reservation_id || !$new_status) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid reservation or status!']);
        exit;
    }

    // Fetch reservation details
    $query = "SELECT * FROM reservations WHERE reservation_id = :reservation_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
    $stmt->execute();
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        echo json_encode(['status' => 'error', 'message' => 'Reservation not found!']);
        exit;
    }

    // Update reservation status
    $updateQuery = "UPDATE reservations SET status = :status, owner_note = :note WHERE reservation_id = :reservation_id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(':status', $new_status);
    $updateStmt->bindParam(':note', $owner_note);
    $updateStmt->bindParam(':reservation_id', $reservation_id);

    if ($updateStmt->execute()) {
        // Send email notification to customer
        $customer_email = $reservation['customer_email'];
        $reservation_date = $reservation['reservation_date'];
        $subject = "Reservation Status Updated";
        $body = "Dear Customer, your reservation made on $reservation_date has been updated to '$new_status'. Note from owner: $owner_note";

        if (sendEmail($customer_email, $subject, $body)) {
            echo json_encode(['status' => 'success', 'message' => 'Reservation updated and email sent!']);
        } else {
            echo json_encode(['status' => 'warning', 'message' => 'Reservation updated but email failed to send.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update reservation status!']);
    }
}

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'your-email@gmail.com'; // Replace with your email
    $mail->Password = 'your-email-password'; // Replace with your email password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your-email@gmail.com', 'Beach Management System');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;

    return $mail->send();
}
