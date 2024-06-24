<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Select Biology Questions</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      background-image: url('images/blur(1).jpg');
      background-size: cover;
      font-size: 20px;
    }
    .container {
      width: 70%;
      margin: 20px auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: #f9f9f9;
    }
    .form-group {
      margin-bottom: 10px;
    }
    .form-group select,
    .form-group input[type=text],
    .form-group input[type=number] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 7px;
      box-sizing: border-box;
      font-size: 18px;
      outline: none;
      box-shadow: 0 0 6px grey;
    }
    .form-group button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 7px;
      cursor: pointer;
      font-size: 20px;
      margin-top: 30px;
      margin-bottom: 20px;
      margin-left: 25px;
      margin-right: 25px;
    }
    .form-group .clear-button {
      background-color: #f44336;
      margin-left: 10px;
    }
    .form-group button:hover {
      background-color: #45a049;
    }
    .form-group .clear-button:hover {
      background-color: #e53935;
    }
    .form-group .question-type {
      margin-bottom: 10px;
    }
    .form-group .question-type label {
      display: block;
      margin-top: 20px;
    }
    .form-group .topic-container {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background-color: #faebd7;
    }
    .form-group .topic {
      display: flex;
      align-items: center;
      margin-bottom: 5px;
    }
    .form-group .topic label {
      margin-right: 10px;
    }
    .form-group .topic input[type=checkbox] {
      margin-right: 5px;
      margin-left: 20px;
      margin-top: 20px;
    }
    .no_of_qns {
      display: flex;
      align-items: center;
      /* margin-left: 30px; */
      position: absolute;
      left: 370px;
    }
    .no_of_qns label {
      margin-left: 25px;
    }
    .no_of_qns input[type=number] {
      width: 80px;
      position: relative;
      top: 7px;
    }
    ::-webkit-scrollbar {
            display: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Biology Exam</h2>
    <form action="generate_bio_questions.php" method="post">
      <div class="form-group">
        <div class="question-type">
          <label>Select Question from the Topics:</label>
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

          // Query to fetch distinct topics from biology_questions table
          $sql = "SELECT DISTINCT topic FROM biology_questions";
          $result = $conn->query($sql);

          // Output each topic as a section
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $topic = $row['topic'];
              echo '<div>';
              echo "<label for='question_type_$topic'>$topic</label>";
              echo "<div class='topic-container'>";

              // Display topic-specific inputs for each question type
              $question_types = ['Essay', 'True/False', 'Short', 'Multiple Choice'];
              foreach ($question_types as $question_type) {
                echo "<div class='topic'>";
                echo "<input type='checkbox' name='topics[$question_type][]' value='$topic'>";
                echo "<label for='topic_{$question_type}_$topic'>{$question_type}</label>";
                echo "<div class='no_of_qns'>";
                echo "<label for='topic_{$question_type}_{$topic}_easy_questions'>Easy Qns:</label>";
                echo "<input type='number' name='easy_questions[$question_type][$topic]' min='0' placeholder='Easy Qns'>";
                echo "<label for='topic_{$question_type}_{$topic}_medium_questions'>Medium Qns:</label>";
                echo "<input type='number' name='medium_questions[$question_type][$topic]' min='0' placeholder='Medium Qns'>";
                echo "<label for='topic_{$question_type}_{$topic}_hard_questions'>Hard Qns:</label>";
                echo "<input type='number' name='hard_questions[$question_type][$topic]' min='0' placeholder='Hard Qns'>";
                echo "</div>";
                echo "</div>";
              }

              echo "</div>";
              echo "</div>";
            }
          } else {
            echo "No topics found in the database.";
          }

          // Close connection
          $conn->close();
          ?>
        </div>
      </div>
      <div class="form-group">
        <button type="submit" name="create">Create</button>
        <button type="reset" class="clear-button">Clear</button>
      </div>
    </form>
  </div>
</body>
</html>
