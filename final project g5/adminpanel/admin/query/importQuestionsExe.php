<?php
include("../../../conn.php");

if(!$conn) {
    die(json_encode(array("res" => "error", "msg" => "Database connection failed")));
}

if(isset($_FILES['csvFile'])) {
    $file = $_FILES['csvFile'];
    $examId = $_POST['exam_id']; // Make sure to pass exam_id from the form
    
    // Check if file is CSV
    $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
    if($fileType != "csv") {
        echo json_encode(array("res" => "error", "msg" => "Please upload a CSV file"));
        exit;
    }

    // Read CSV file
    $handle = fopen($file['tmp_name'], "r");
    if($handle !== FALSE) {
        // Skip header row
        fgetcsv($handle);
        
        $count = 0;
        $duplicates = 0;
        $errors = array();
        
        // Start transaction
        $conn->beginTransaction();
        
        try {
            while(($data = fgetcsv($handle)) !== FALSE) {
                if(count($data) >= 6) {
                    $question = trim($data[0]);
                    $choiceA = trim($data[1]);
                    $choiceB = trim($data[2]);
                    $choiceC = trim($data[3]);
                    $choiceD = trim($data[4]);
                    $correctAnswer = trim($data[5]);
                    
                    // Validate correct answer
                    if(!in_array($correctAnswer, array($choiceA, $choiceB, $choiceC, $choiceD))) {
                        $errors[] = "Question: '$question' - Correct answer must match one of the choices";
                        continue;
                    }
                    
                    // Check if question already exists
                    $checkQuestion = $conn->query("SELECT * FROM exam_question_tbl WHERE exam_id='$examId' AND exam_question='$question'");
                    if($checkQuestion->rowCount() > 0) {
                        $duplicates++;
                        continue; // Skip this question and continue with the next one
                    }
                    
                    // Insert question
                    $insertQuestion = $conn->query("INSERT INTO exam_question_tbl(exam_id, exam_question, exam_ch1, exam_ch2, exam_ch3, exam_ch4, exam_answer) 
                                                  VALUES('$examId', '$question', '$choiceA', '$choiceB', '$choiceC', '$choiceD', '$correctAnswer')");
                    
                    if($insertQuestion) {
                        $count++;
                    }
                }
            }
            
            if(count($errors) > 0) {
                throw new Exception(implode("\n", $errors));
            }
            
            $conn->commit();
            $message = "$count questions imported successfully";
            if($duplicates > 0) {
                $message .= " ($duplicates duplicate questions skipped)";
            }
            echo json_encode(array("res" => "success", "count" => $count, "duplicates" => $duplicates, "message" => $message));
            
        } catch(Exception $e) {
            $conn->rollBack();
            echo json_encode(array("res" => "error", "msg" => $e->getMessage()));
        }
        
        fclose($handle);
    } else {
        echo json_encode(array("res" => "error", "msg" => "Failed to read CSV file"));
    }
} else {
    echo json_encode(array("res" => "error", "msg" => "No file uploaded"));
}
?> 