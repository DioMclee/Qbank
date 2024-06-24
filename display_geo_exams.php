<?php
session_start();

// Check if questions are stored in session
if (isset($_SESSION['questions']) && !empty($_SESSION['questions'])) {
    $questions = $_SESSION['questions'];
} else {
    // If no questions are found in session, redirect back to the form page
    header("Location: generate_geo_questions.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Display Geography Questions</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
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
  </style>
</head>
<body>
  <label for="examName">EXAM NAME: </label>
  <input type="text" id="examName" placeholder="Enter exam name here">
  <button onclick="convertHTMLtoPDF()">DOWNLOAD EXAM</button>
  <div class="exam_to_download">
    <div class="container">
      <h2>Geography Exam Questions</h2>
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
                  if (isset($topic_question['options']) && !empty($topic_question['options'])) {
                      echo "<p><strong>Options:</strong> " . $topic_question['options'] . "</p>";
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

  <script type="text/javascript">
    function convertHTMLtoPDF() {
      const { jsPDF } = window.jspdf;

      // Get the exam name from the input field
      let examName = document.getElementById('examName').value;
      // Set a default name if the input is empty
      if (!examName) {
        examName = 'exam_questions';
      }

      // Create a new jsPDF instance with standard A4 page dimensions in landscape orientation
      let doc = new jsPDF('landscape', 'mm', 'a4');

      // Get the HTML content of the element with class 'exam_to_download'
      let pdfjs = document.querySelector('.exam_to_download');

      // Use html2canvas to convert HTML to canvas and then to PDF
      html2canvas(pdfjs, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgWidth = 297; // A4 width in mm
        const pageHeight = 210; // A4 height in mm
        const imgHeight = canvas.height * imgWidth / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;

        // Add the first page with the image
        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        // Add more pages if needed
        while (heightLeft >= 0) {
          position = heightLeft - imgHeight;
          doc.addPage();
          doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
          heightLeft -= pageHeight;
        }

        // Save the generated PDF with the exam name
        doc.save(examName + '.pdf');
      });
    }
  </script>
</body>
</html>

<?php
// Clear session data after displaying questions
unset($_SESSION['questions']);
?>
