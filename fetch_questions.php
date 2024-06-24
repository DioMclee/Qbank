<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    // Check if topics are submitted
    if (empty($_POST['topics'])) {
        die("No topics or questions selected.");
    }

    // Extract form data
    $topics = $_POST['topics'];
    $easyQuestions = $_POST['easy_questions'];
    $mediumQuestions = $_POST['medium_questions'];
    $hardQuestions = $_POST['hard_questions'];

    // Database connection details
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

    // Initialize array to store fetched questions
    $questions = [];

    // Iterate through selected topics and question types
    foreach ($topics as $questionType => $topicList) {
        foreach ($topicList as $topic) {
            // Fetch easy questions
            if (isset($easyQuestions[$questionType][$topic]) && $easyQuestions[$questionType][$topic] > 0) {
                $numEasy = $easyQuestions[$questionType][$topic];
                $sql = "SELECT * FROM biology_questions WHERE topic='$topic' AND question_type='$questionType' AND difficulty='Easy' ORDER BY RAND() LIMIT $numEasy";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $questions[] = $row;
                    }
                }
            }

            // Fetch medium questions
            if (isset($mediumQuestions[$questionType][$topic]) && $mediumQuestions[$questionType][$topic] > 0) {
                $numMedium = $mediumQuestions[$questionType][$topic];
                $sql = "SELECT * FROM biology_questions WHERE topic='$topic' AND question_type='$questionType' AND difficulty='Medium' ORDER BY RAND() LIMIT $numMedium";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $questions[] = $row;
                    }
                }
            }

            // Fetch hard questions
            if (isset($hardQuestions[$questionType][$topic]) && $hardQuestions[$questionType][$topic] > 0) {
                $numHard = $hardQuestions[$questionType][$topic];
                $sql = "SELECT * FROM biology_questions WHERE topic='$topic' AND question_type='$questionType' AND difficulty='Hard' ORDER BY RAND() LIMIT $numHard";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $questions[] = $row;
                    }
                }
            }
        }
    }

    // Close MySQL connection
    $conn->close();

    // Store questions in session
    $_SESSION['questions'] = $questions;

    // Redirect to display questions
    header("Location: display_questions.php");
    exit;
} else {
    die("Unauthorized access.");
}
?>
