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
    $question_id = intval($_POST['id']);

    if ($action == 'edit') {
        $question_text = $conn->real_escape_string($_POST['question_text']);
        $question_choices = json_decode($_POST['question_choices'], true);

        $update_question_sql = "UPDATE biology_questions SET question_text = '$question_text' WHERE id = $question_id";

        if ($conn->query($update_question_sql) === TRUE) {
            $response['success'] = true;
            foreach ($question_choices as $choice) {
                $choice_id = intval($choice['choice_id']);
                $choice_text = $conn->real_escape_string($choice['choice_text']);
                $update_choice_sql = "UPDATE bio_qns_choices SET choice_text = '$choice_text' WHERE id = $choice_id";
                $conn->query($update_choice_sql);
            }
        }
    } elseif ($action == 'delete') {
        $delete_question_sql = "DELETE FROM biology_questions WHERE id = $question_id";
        $delete_choices_sql = "DELETE FROM bio_qns_choices WHERE question_id = $question_id";
        
        if ($conn->query($delete_question_sql) === TRUE && $conn->query($delete_choices_sql) === TRUE) {
            $response['success'] = true;
        }
    }
}

echo json_encode($response);

$conn->close();
?>
