<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    if (empty($_POST['topics'])) {
        die("No topics or questions selected.");
    }

    $topics = $_POST['topics'];
    $easyQuestions = $_POST['easy_questions'];
    $mediumQuestions = $_POST['medium_questions'];
    $hardQuestions = $_POST['hard_questions'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "new-qbank";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $questions = [];

    function fetchQuestions($conn, $questionType, $topic, $difficulty, $limit) {
        $questions = [];
        $sql = "SELECT * FROM geography_questions WHERE topic=? AND question_type=? AND difficulty_level=? ORDER BY RAND() LIMIT ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $topic, $questionType, $difficulty, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $questions[] = $row;
            }
            $stmt->close();
        } else {
            die("Error preparing statement: " . $conn->error);
        }
        return $questions;
    }

    foreach ($topics as $questionType => $topicList) {
        foreach ($topicList as $topic) {
            if (isset($easyQuestions[$questionType][$topic]) && $easyQuestions[$questionType][$topic] > 0) {
                $numEasy = $easyQuestions[$questionType][$topic];
                $questions = array_merge($questions, fetchQuestions($conn, $questionType, $topic, 'Easy', $numEasy));
            }

            if (isset($mediumQuestions[$questionType][$topic]) && $mediumQuestions[$questionType][$topic] > 0) {
                $numMedium = $mediumQuestions[$questionType][$topic];
                $questions = array_merge($questions, fetchQuestions($conn, $questionType, $topic, 'Medium', $numMedium));
            }

            if (isset($hardQuestions[$questionType][$topic]) && $hardQuestions[$questionType][$topic] > 0) {
                $numHard = $hardQuestions[$questionType][$topic];
                $questions = array_merge($questions, fetchQuestions($conn, $questionType, $topic, 'Hard', $numHard));
            }
        }
    }

    $conn->close();

    $_SESSION['questions'] = $questions;

    header("Location: display_geo_exams.php");
    exit;
} else {
    die("Unauthorized access.");
}
?>
