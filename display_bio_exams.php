<?php
session_start();
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

// Check if questions are stored in session
if (isset($_SESSION['questions']) && !empty($_SESSION['questions'])) {
    $questions = $_SESSION['questions'];
} else {
    // If no questions are found in session, redirect back to the form page
    header("Location: generate_bio_questions.php");
    exit;
}

// Fetch choices for multiple-choice questions
function getChoices($question_id, $conn) {
    $choices = [];
    $sql = "SELECT option_a, option_b, option_c, option_d, correct_option FROM bio_qns_choices WHERE question_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $choices = [
            'A' => $row['option_a'],
            'B' => $row['option_b'],
            'C' => $row['option_c'],
            'D' => $row['option_d'],
            'correct' => $row['correct_option']
        ];
    }
    $stmt->close();
    return $choices;
}

// Include choices in the questions array
foreach ($questions as &$question) {
    if ($question['question_type'] === 'Multiple Choice') {
        $question['choices'] = getChoices($question['id'], $conn);
    }
}
unset($question); // Break the reference with the last element

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Display Biology Questions</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
    }
    button {
      cursor: pointer;
    }
    .container {
      width: 80%;
      margin: 20px auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #f9f9f9;
    }

    .question-type {
      margin-bottom: 20px;
    }

    .topic {
      margin-bottom: 20px;
    }

    .topic-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .question-list {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background-color: #f0f0f0;
    }

    .question {
      margin-bottom: 10px;
    }

    .question p {
      margin-bottom: 5px;
    }

    .choices {
      margin-left: 20px;
      margin-top: -10px;
    }
    .choices ul {
      list-style-type: none;
    }
    .exam_to_download {
      margin-bottom: 100px;
    }
    ::-webkit-scrollbar {
            display: none;
        }
  </style>
</head>
<body>
  <label for="examName">EXAM NAME: </label>
  <input type="text" id="examName" placeholder="Enter exam name here">
  <button onclick="convertHTMLtoPDF()">DOWNLOAD EXAM</button>
  <button onclick="downloadAnswerSheet()">DOWNLOAD ANSWER SHEET</button>
  <div class="exam_to_download">
    <div class="container">
      <h2>Biology Exam Questions</h2>
      <?php
      // Initialize arrays to categorize questions
      $question_types = [];
      // Categorize questions by question type and topic
      foreach ($questions as $question) {
          $question_type = $question['question_type'];
          $topic = $question['topic'];
          // Check if question type exists in array, if not, initialize it
          if (!isset($question_types[$question_type])) {
              $question_types[$question_type] = [];
          }
          // Check if topic exists in question type array, if not, initialize it
          if (!isset($question_types[$question_type][$topic])) {
              $question_types[$question_type][$topic] = [];
          }
          // Add question to the respective category
          $question_types[$question_type][$topic][] = $question;
      }
      // Display questions grouped by question type and topic
      foreach ($question_types as $question_type => $topics) {
          echo "<div class='question-type'>";
          echo "<h3>$question_type</h3>";
    
          foreach ($topics as $topic => $topic_questions) {
              echo "<div class='topic'>";
              echo "<div class='topic-title'>Topic: $topic</div>";
              echo "<div class='question-list'>";
              // Initialize counter for question numbering
              $question_number = 1;
              foreach ($topic_questions as $topic_question) {
                  echo "<div class='question'>";
                  echo "<p><strong>Question $question_number:</strong> " . $topic_question['question_text'] . "</p>";
    
                  // Display options if they exist
                  if ($question_type === 'Multiple Choice' && isset($topic_question['choices']) && !empty($topic_question['choices'])) {
                      echo "<div class='choices'><ul>";
                      foreach ($topic_question['choices'] as $key => $choice) {
                          if ($key !== 'correct') {
                              echo "<li><strong>$key.</strong> $choice</li>";
                          }
                      }
                      echo "</ul></div>";
                  }
                  echo "</div>";
                  // Increment question number
                  $question_number++;
              }
              echo "</div>"; // Close question-list
              echo "</div>"; // Close topic
          }
          echo "</div>"; // Close question-type
      }
      ?>
    </div>
  </div>
  <div class="answer_sheet_to_download">
    <div class="container">
      <h2>Answer Sheet</h2>
      <?php
      foreach ($question_types as $question_type => $topics) {
          if ($question_type === 'Multiple Choice') {
              echo "<div class='question-type'>";
              echo "<h3>$question_type</h3>";

              foreach ($topics as $topic => $topic_questions) {
                  echo "<div class='topic'>";
                  echo "<div class='topic-title'>Topic: $topic</div>";
                  echo "<div class='question-list'>";
                  $question_number = 1;
                  foreach ($topic_questions as $topic_question) {
                      echo "<div class='question'>";
                      echo "<p><strong>Question $question_number:</strong> Correct Answer: " . $topic_question['choices']['correct'] . "</p>";
                      echo "</div>";
                      $question_number++;
                  }
                  echo "</div>"; // Close question-list
                  echo "</div>"; // Close topic
              }
              echo "</div>"; // Close question-type
          }
      }
      ?>
    </div>
  </div>

  <script type="text/javascript">
    function convertHTMLtoPDF() {
      const { jsPDF } = window.jspdf;

      // Get the exam name from the input field
      let examName = document.getElementById('examName').value;
      // Set a default name if the input is empty
      if (!examName) {
        examName = 'exam_questions';
      }

      // Get the HTML content of the element with class 'exam_to_download'
      let pdfjs = document.querySelector('.exam_to_download');

      // Use html2pdf to convert HTML to PDF
      html2pdf().from(pdfjs).set({
        margin: 1,
        filename: examName + '.pdf',
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
      }).save();
    }

    function downloadAnswerSheet() {
      const { jsPDF } = window.jspdf;

      // Get the exam name from the input field
      let examName = document.getElementById('examName').value;
      // Set a default name if the input is empty
      if (!examName) {
        examName = 'exam_questions';
      }

      // Get the HTML content of the element with class 'answer_sheet_to_download'
      let pdfjs = document.querySelector('.answer_sheet_to_download');
      
      // Temporarily make the answer sheet visible
      pdfjs.style.display = 'block';

      // Use html2pdf to convert HTML to PDF
      html2pdf().from(pdfjs).set({
        margin: 1,
        filename: examName + '_answer_sheet.pdf',
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
      }).save().then(() => {
        // Revert the display property after generating the PDF
        pdfjs.style.display = 'none';
      });
    }
  </script>
</body>
</html>

<?php
// Clear session data after displaying questions
unset($_SESSION['questions']);
?>
