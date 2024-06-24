<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "new-qbank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'];

    if ($action == 'edit') {
        $question_text = $_POST['question_text'];
        $option_a = $_POST['option_a'] ?? null;
        $option_b = $_POST['option_b'] ?? null;
        $option_c = $_POST['option_c'] ?? null;
        $option_d = $_POST['option_d'] ?? null;

        $update_question_sql = "UPDATE biology_questions SET question_text=? WHERE id=?";
        $stmt = $conn->prepare($update_question_sql);
        $stmt->bind_param("si", $question_text, $id);
        if ($stmt->execute()) {
            if ($option_a !== null && $option_b !== null && $option_c !== null && $option_d !== null) {
                $update_choices_sql = "UPDATE bio_qns_choices SET option_a=?, option_b=?, option_c=?, option_d=? WHERE question_id=?";
                $stmt = $conn->prepare($update_choices_sql);
                $stmt->bind_param("ssssi", $option_a, $option_b, $option_c, $option_d, $id);
                $stmt->execute();
            }
            $response['success'] = true;
        }
        $stmt->close();
    } elseif ($action == 'delete') {
        $delete_choices_sql = "DELETE FROM bio_qns_choices WHERE question_id=?";
        $stmt = $conn->prepare($delete_choices_sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $delete_question_sql = "DELETE FROM biology_questions WHERE id=?";
        $stmt = $conn->prepare($delete_question_sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response['success'] = true;
        }
        $stmt->close();
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
