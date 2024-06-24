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

$sql = "SELECT * FROM geography_questions ORDER BY question_type, topic";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Geography Questions</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: antiquewhite;
        }
        h1 {
            text-align: center;
        }
        h2 {
            color: #2c3e50;
            margin-top: 30px;
        }
        h3 {
            color: #34495e;
            margin-top: 20px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            background: #ffffff;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .buttons {
            display: flex;
            gap: 10px;
        }
        .popup {
            display: none;
            position: fixed;
            width: 450px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 3px gray inset;
            z-index: 1000;
        }
        .overlay {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .buttons .edit-btn,
        .buttons .delete-btn{
            padding: 3px 6px 3px 6px;
            outline: none;
            border: none;
            border-radius: 2px;
            box-shadow: 0 0 6px grey;
            cursor: pointer;
            transition: transform .4s ease-in-out;
        }
        .buttons .edit-btn:hover{
            transform: scale(1.05);
            background-color: greenyellow;
        }
        .buttons .delete-btn:hover {
            background-color: #f2452e;
            transform: scale(1.05);
        }
        .confirm-btn,
        .cancel-btn {
            padding: 7px 15px 7px 15px;
            outline: none;
            border: none;
            border-radius: 5px;
            box-shadow: 0 0 6px grey;
            cursor: pointer;
            transition: transform .4s ease-in-out;
        }
        .confirm-btn:hover,
        .cancel-btn:hover {
            transform: scale(1.09);
        }
        .cancel-btn{
            background-color: #f2452e;
            margin-right: 20px;
            margin-left: 15px;
        }
        .buttons .delete-btn {
            background-color: #f0c5bb;
        }
        .buttons .edit-btn {
            background-color: #c0f0bb;
        }
        .confirm-btn{
            background-color: greenyellow;
        }
        .popup {
            border-radius: 10px;
        }
        .popup textarea {
            padding: 7px 15px 7px 15px;
            outline: none;
            border: 1px solid gray;
            box-shadow: 0 0 6px grey;
            border-radius: 5px;
            max-width: 300px;
            max-height: 60px;
            min-width: 300px;
            min-height: 60px;
            margin-bottom: 10px;
            margin-left: 20px;
            font-family: Arial, Helvetica, sans-serif;
            resize: none;
            overflow-y: auto;
            height: 60px;
            font-size: 15px;
            scrollbar-width: thin;
            scrollbar-color: #ccc transparent;
        }
        .popup h2 {
            margin-top: 15px;
        }
        .popup p {
            display: flex;
            flex-direction: row;
        }
        
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showPopup(action, questionId, questionText) {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
            document.getElementById('popup-action').innerText = action === 'edit' ? 'Edit Question' : 'Delete Question';
            const questionTextElement = document.getElementById('popup-question-text');
            questionTextElement.value = questionText;
            questionTextElement.readOnly = action !== 'edit';
            document.getElementById('confirm-action').onclick = function() {
                if (action === 'edit') {
                    const newQuestionText = questionTextElement.value;
                    editQuestion(questionId, newQuestionText);
                } else {
                    deleteQuestion(questionId);
                }
            };
        }

        function closePopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }

        function editQuestion(questionId, newQuestionText) {
            const formData = new FormData();
            formData.append('action', 'edit');
            formData.append('id', questionId);
            formData.append('question_text', newQuestionText);

            fetch('question_action_geo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Question Edited Successfully',
                        `Question: ${newQuestionText}`,
                        'success'
                    ).then(() => location.reload());
                } else {
                    Swal.fire(
                        'Error',
                        'There was an error editing the question.',
                        'error'
                    );
                }
                closePopup();
            });
        }

        function deleteQuestion(questionId) {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', questionId);

            fetch('question_action_geo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Question Deleted Successfully',
                        'The question has been deleted.',
                        'success'
                    ).then(() => location.reload());
                } else {
                    Swal.fire(
                        'Error',
                        'There was an error deleting the question.',
                        'error'
                    );
                }
                closePopup();
            });
        }
    </script>
</head>
<body>
    <h1>Geography Questions</h1>
    <?php
    if ($result->num_rows > 0) {
        $current_question_type = "";
        $current_topic = "";
        $question_number = 0;

        while($row = $result->fetch_assoc()) {
            if ($row["question_type"] != $current_question_type) {
                if ($current_question_type != "") {
                    echo "</ul>";
                }
                $current_question_type = $row["question_type"];
                $current_topic = "";
                $question_number = 0;
                echo "<h2>" . htmlspecialchars($current_question_type) . "</h2>";
            }
            if ($row["topic"] != $current_topic) {
                if ($current_topic != "") {
                    echo "</ul>";
                }
                $current_topic = $row["topic"];
                echo "<h3>" . htmlspecialchars($current_topic) . "</h3>";
                echo "<ul>";
            }
            $question_number++;
            echo "<li>$question_number. " . htmlspecialchars($row["question_text"]) . "
                <div class='buttons'>
                    <button class='button edit-btn' onclick=\"showPopup('edit', " . $row['id'] . ", '" . htmlspecialchars($row['question_text']) . "')\">Edit</button>
                    <button class='button delete-btn' onclick=\"showPopup('delete', " . $row['id'] . ", '" . htmlspecialchars($row['question_text']) . "')\">Delete</button>
                </div>
            </li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No questions found in the database.</p>";
    }

    $conn->close();
    ?>

    <div id="overlay" class="overlay" onclick="closePopup()"></div>
    <div id="popup" class="popup">
        <h2 id="popup-action"></h2>
        <p>Question: <textarea type="text" id="popup-question-text"></textarea></p>
        <button class="cancel-btn" onclick="closePopup()">Cancel</button>
        <button class="confirm-btn" id="confirm-action">Confirm</button>
    </div>
</body>
</html>
