<?php 
  include("../../../conn.php");
  $id = $_GET['id'];
 
  try {
    if (!$conn) {
      throw new Exception("Database connection failed");
    }
    $stmt = $conn->prepare("SELECT * FROM course_tbl WHERE cou_id = ?");
    $stmt->execute([$id]);
    $selCourse = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$selCourse) {
      throw new Exception("Course not found");
    }
  } catch (Exception $e) {
    die($e->getMessage());
  }

 ?>

<fieldset style="width:543px;" >
	<legend><i class="facebox-header"><i class="edit large icon"></i>&nbsp;Update Cluster Name ( <?php echo strtoupper($selCourse['cou_name']); ?> )</i></legend>
  <div class="col-md-12 mt-4">
<form method="post" id="updateCourseFrm">
     <div class="form-group">
      <legend>Cluster Name</legend>
    <input type="hidden" name="course_id" value="<?php echo $id; ?>">
    <input type="" name="newCourseName" class="form-control" required="" value="<?php echo $selCourse['cou_name']; ?>" >
  </div>
  <div class="form-group" align="right">
    <button type="submit" class="btn btn-sm btn-primary">Update Now</button>
  </div>
</form>
  </div>
</fieldset>







