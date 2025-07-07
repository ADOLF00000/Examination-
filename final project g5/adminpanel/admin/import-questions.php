<?php 
session_start();

if(!isset($_SESSION['admin']['adminnakalogin']) == true) header("location:index.php");

?>
<?php include("../../conn.php"); ?>
<!-- MAO NI ANG HEADER -->
<?php include("includes/header.php"); ?>      

<!-- UI THEME DIRI -->
<?php include("includes/ui-theme.php"); ?>

<div class="app-main">
<!-- sidebar diri  -->
<?php include("includes/sidebar.php"); ?>

<?php 
   if(!$conn) {
       die("Database connection failed");
   }
   $exId = $_GET['id'];
   $stmt = $conn->prepare("SELECT * FROM exam_tbl WHERE ex_id = ?");
   $stmt->execute([$exId]);
   $selExamRow = $stmt->fetch(PDO::FETCH_ASSOC);
   
   if(!$selExamRow) {
       die("Exam not found");
   }
?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>IMPORT QUESTIONS</div>
                    <div class="page-title-subheading">
                        Import questions for <?php echo $selExamRow['ex_title']; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Import Questions from CSV</h5>
                <p class="text-muted">Upload a CSV file with the following format:</p>
                <p class="text-muted">Question,Choice A,Choice B,Choice C,Choice D,Correct Answer</p>
                
                <form id="importQuestionFrm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="exam_id" value="<?php echo $exId; ?>">
                    <div class="form-group">
                        <input type="file" class="form-control-file" id="csvFileInput" name="csvFile" accept=".csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Import Questions</button>
                    <a href="manage-exam.php?id=<?php echo $exId; ?>" class="btn btn-secondary">Back to Questions</a>
                </form>

                <div id="questionList" class="mt-4"></div>
            </div>
        </div>
    </div>
</div>

<!-- MAO NI IYA FOOTER -->
<?php include("includes/footer.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('importQuestionFrm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('query/importQuestionsExe.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.res == "success") {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000
                }).then(function() {
                    window.location.href = 'manage-exam.php?id=<?php echo $exId; ?>';
                });
            } else if(data.res == "error") {
                Swal.fire(
                    'Error!',
                    data.msg,
                    'error'
                );
            }
        })
        .catch(error => {
            Swal.fire(
                'Error!',
                'Failed to import questions',
                'error'
            );
        });
    });
});
</script>