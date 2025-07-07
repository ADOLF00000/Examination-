<?php 
 include("../../../conn.php");


extract($_POST);

// Start transaction
$conn->beginTransaction();

try {
    // Delete all related records
    $queries = [
        "DELETE FROM exam_question_tbl WHERE exam_id IN (SELECT ex_id FROM exam_tbl WHERE cou_id='$id')",
        "DELETE FROM exam_attempt WHERE exam_id IN (SELECT ex_id FROM exam_tbl WHERE cou_id='$id')",
        "DELETE FROM exam_answers WHERE exam_id IN (SELECT ex_id FROM exam_tbl WHERE cou_id='$id')",
        "DELETE FROM exam_tbl WHERE cou_id='$id'",
        "UPDATE examinee_tbl SET exmne_course='0' WHERE exmne_course='$id'",
        "DELETE FROM course_tbl WHERE cou_id='$id'"
    ];

    $success = true;
    foreach ($queries as $query) {
        if (!$conn->query($query)) {
            $success = false;
            break;
        }
    }

    if ($success) {
        $conn->commit();
        $res = array("res" => "success");
    } else {
        throw new Exception("Failed to delete course");
    }
} catch (Exception $e) {
    $conn->rollBack();
    $res = array("res" => "failed");
}



	echo json_encode($res);
 ?>