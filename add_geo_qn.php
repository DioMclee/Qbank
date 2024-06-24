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

    // SQL to insert data into the geography_questions table
    $sql = "INSERT INTO geography_questions (topic, difficulty_level, question_type, question_text)
            VALUES ('$topic', '$difficulty_level', '$question_type', '$question_text')";

    if ($conn->query($sql) === TRUE) {
        $response = array(
            'success' => true,
            'message' => 'New question added successfully!'
        );
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
    <title>Add Geography Question</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: url(images/blur\(1\).jpg);
            background-size: cover;
            height: 100vh;
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
    </style>
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('questionForm');

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
                        $sql = "SELECT DISTINCT topic FROM geography_questions ORDER BY topic";
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
                        <option value="Easy">Easy</option>
                        <option value="Medium">Medium</option>
                        <option value="Hard">Hard</option>
                    </select>
                </div>
            
                <div class="form-group">
                    <label for="question_type">Question Type:</label>
                    <select id="question_type" name="question_type" required>
                        <option value="Essay">Essay</option>
                        <option value="True/False">True/False</option>
                        <option value="Short">Short</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="question_text">Question Text:</label>
                <textarea id="question_text" name="question_text" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Add Question</button>
                <button type="reset" name="clear">Clear Form</button>
            </div>
        </form>
    </div>
</body>
</html>
