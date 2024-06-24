<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize inputs
    function sanitize_input($data) {
        // Remove whitespace from beginning and end of string
        $data = trim($data);
        // Remove backslashes (\)
        $data = stripslashes($data);
        // Convert special characters to HTML entities
        $data = htmlspecialchars($data);
        return $data;
    }

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "new-qbank";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        $response = array(
            'success' => false,
            'message' => 'Connection failed: ' . $conn->connect_error
        );
        echo json_encode($response);
        exit;
    }

    // Prepare data for insertion
    $topic = sanitize_input($_POST['topic']);
    $difficulty_level = sanitize_input($_POST['difficulty_level']);
    $question_type = sanitize_input($_POST['question_type']);
    $question_text = sanitize_input($_POST['question_text']);

    // SQL to insert data into the biology_questions table
    $sql = "INSERT INTO biology_questions (topic, difficulty_level, question_type, question_text)
            VALUES ('$topic', '$difficulty_level', '$question_type', '$question_text')";

    if ($conn->query($sql) === TRUE) {
        $question_id = $conn->insert_id; // Get the last inserted ID for the question

        // If the question type is 'Multiple Choice', insert the options
        if ($question_type === 'Multiple Choice') {
            $option_a = sanitize_input($_POST['option_a']);
            $option_b = sanitize_input($_POST['option_b']);
            $option_c = sanitize_input($_POST['option_c']);
            $option_d = sanitize_input($_POST['option_d']);
            $correct_option = sanitize_input($_POST['correct_option']);

            $sql_options = "INSERT INTO bio_qns_choices (question_id, option_a, option_b, option_c, option_d, correct_option)
                            VALUES ('$question_id', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_option')";

            if ($conn->query($sql_options) === TRUE) {
                $response = array(
                    'success' => true,
                    'message' => 'New question and options added successfully!'
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Error: ' . $sql_options . '<br>' . $conn->error
                );
            }
        } else {
            $response = array(
                'success' => true,
                'message' => 'New question added successfully!'
            );
        }
    } else {
        $response = array(
            'success' => false,
            'message' => 'Error: ' . $sql . '<br>' . $conn->error
        );
    }

    // Close database connection
    $conn->close();

    // Return JSON response
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Biology Question</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: url(images/blur\(1\).jpg);
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-size: 20px;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            margin-top: 50px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: antiquewhite;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: 20px;
        }
        .form-group input[type=text],
        .form-group select {
            width: 60%;
            padding: 15px 15px 15px 15px;
            border: 1px solid #ccc;
            border-radius: 7px;
            box-sizing: border-box;
            font-size: 20px;
            box-shadow: 0 0 6px grey;
            outline: none;
        }
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 7px;
            box-sizing: border-box;
            outline: none;
            height: 100px;
            font-size: 20px;
            box-shadow: 0 0 6px grey;
            font-family: Arial, Helvetica, sans-serif;
            resize: vertical;
            min-height: 100px;
        }
        .form-group button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-right: 10px;
            font-size: 20px;
            box-shadow: 0 0 6px grey;
        }
        .form-group button:hover {
            background-color: #45a049;
            box-shadow: 0 0 10px grey;
            transform: scale(1.01);
        }
        .form-group button[type=reset] {
            background-color: #f44336;
        }
        .form-group button[type=reset]:hover {
            background-color: #d32f2f;
            box-shadow: 0 0 10px grey;
            transform: scale(1.01);
        }
        .form-group input[type=text]:hover,
        .form-group select:hover {
            box-shadow: 0 0 10px grey;
        }
        .container .input-arrangement {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
        }
        .container .input-arrangement .form-group {
            margin-right: 20px;
        }
        .container .input-arrangement .form-group:last-child {
            margin-right: 0;
        }
        .container .input-arrangement .form-group input[type=text],
        .container .input-arrangement .form-group select {
            width: 100%;
        }
        .choice-input {
            display: flex;
            flex-direction: row;
        }
        .choice-input input[type=text] {
            padding: 10px 10px 10px 10px;
            width: 100%;
        }
        ::-webkit-scrollbar {
            display: none;
        }

        .radio-btns {
            display: flex;
            flex-direction: row;
        }
        .radio-btns input[type=radio] {
            position: relative;
            top: 15%;
            margin-right: 15px;
        }
    </style>
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('questionForm');
            const questionTypeSelect = document.getElementById('question_type');
            const mcqOptions = document.getElementById('mcq_options');

            questionTypeSelect.addEventListener('change', function () {
                if (this.value === 'Multiple Choice') {
                    mcqOptions.style.display = 'block';
                } else {
                    mcqOptions.style.display = 'none';
                }
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent normal form submission

                // Serialize form data
                const formData = new FormData(form);

                // Send form data using fetch API
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message
                        }).then(() => {
                            form.reset(); // Reset the form
                            mcqOptions.style.display = 'none'; // Hide MCQ options
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Add New Question</h2>
        <form id="questionForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-arrangement">
            <div class="form-group">
                    <label for="topic">Topic:</label>
                    <select id="topic" name="topic" required>
                        <option value="">Select Topic</option>
                        <?php
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
                        // Fetch distinct topics from the biology_questions table
                        $sql = "SELECT DISTINCT topic FROM biology_questions ORDER BY topic";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row["topic"]) . "'>" . htmlspecialchars($row["topic"]) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No topics found</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="difficulty_level">Difficulty Level:</label>
                    <select id="difficulty_level" name="difficulty_level" required>
                        <option value="">Select Difficulty</option>
                        <option value="Easy">Easy</option>
                        <option value="Medium">Medium</option>
                        <option value="Hard">Hard</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="question_type">Question Type:</label>
                    <select id="question_type" name="question_type" required>
                        <option value="">Select Type</option>
                        <option value="Multiple Choice">Multiple Choice</option>
                        <option value="True/False">True/False</option>
                        <option value="Short Answer">Short Answer</option>
                        <!-- Add more types as needed -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="question_text">Question Text:</label>
                <textarea id="question_text" name="question_text" required></textarea>
            </div>
            <div id="mcq_options" style="display: none;">
                <div class="form-group choice-input">
                    <label for="option_a">A:</label>
                    <input type="text" id="option_a" name="option_a" required autocomplete="off">
                </div>
                <div class="form-group choice-input">
                    <label for="option_b">B:</label>
                    <input type="text" id="option_b" name="option_b" required autocomplete="off">
                </div>
                <div class="form-group choice-input">
                    <label for="option_c">C:</label>
                    <input type="text" id="option_c" name="option_c" required autocomplete="off">
                </div>
                <div class="form-group choice-input">
                    <label for="option_d">D:</label>
                    <input type="text" id="option_d" name="option_d" required autocomplete="off">
                </div>
                <div class="form-group choice-input">
                    <label for="correct_option">Correct Option:</label>
                    <div class="radio-btns">
                        <label for="correct_option_a">A</label>
                        <input type="radio" value="A" name="correct_option" id="correct_option_a">
                    </div>
                    <div class="radio-btns">
                        <label for="correct_option_b">B</label>
                        <input type="radio" value="B" name="correct_option" id="correct_option_b">
                    </div>
                    <div class="radio-btns">
                        <label for="correct_option_c">C</label>
                        <input type="radio" value="C" name="correct_option" id="correct_option_c">
                    </div>
                    <div class="radio-btns">
                        <label for="correct_option_d">D</label>
                        <input type="radio" value="D" name="correct_option" id="correct_option_d">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Add Question</button>
                <button type="reset" name="clear">Clear Form</button>
            </div>
        </form>
    </div>
</body>
</html>
