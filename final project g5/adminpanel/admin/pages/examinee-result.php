<link rel="stylesheet" type="text/css" href="css/mycss.css">
<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div>EXAMINEE RESULT</div>
                    </div>
                </div>
            </div>        
            
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">Examinee Result
                    </div>
                    <div class="table-responsive">
                        <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                            <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Exam Name</th>
                                <th>Scores</th>
                                <th>Ratings</th>
                            </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $selExmne = $conn->query("SELECT * FROM examinee_tbl et INNER JOIN exam_attempt ea ON et.exmne_id = ea.exmne_id ORDER BY ea.examat_id DESC ");
                                if($selExmne->rowCount() > 0)
                                {
                                    while ($selExmneRow = $selExmne->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                           <td><?php echo $selExmneRow['exmne_fullname']; ?></td>
                                           <td>
                                             <?php 
                                                $eid = $selExmneRow['exmne_id'];
                                                $selExName = $conn->query("SELECT * FROM exam_tbl et INNER JOIN exam_attempt ea ON et.ex_id=ea.exam_id WHERE ea.exmne_id='$eid' ")->fetch(PDO::FETCH_ASSOC);
                                                
                                                if($selExName) {
                                                $exam_id = $selExName['ex_id'];
                                                echo $selExName['ex_title'];
                                                } else {
                                                    echo "No exam found";
                                                }
                                              ?>
                                           </td>
                                           <td>
                                                    <?php 
                                                    if($selExName) {
                                                    $selScore = $conn->query("SELECT * FROM exam_question_tbl eqt INNER JOIN exam_answers ea ON eqt.eqt_id = ea.quest_id AND eqt.exam_answer = ea.exans_answer  WHERE ea.axmne_id='$eid' AND ea.exam_id='$exam_id' AND ea.exans_status='new' ");
                                                        $score = $selScore->rowCount();
                                                        $over = $selExName['ex_questlimit_display'];
                                                        echo "<span>$score</span> / $over";
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    ?>
                                           </td>
                                           <td>
                                              <?php 
                                                if($selExName && $over > 0) {
                                                        $percentage = ($score / $over) * 100;
                                                        
                                                        if($percentage >= 75) {
                                                            echo '<span class="text-success">PASSED</span>';
                                                        } else {
                                                            echo '<span class="text-danger">FAILED</span>';
                                                    }
                                                } else {
                                                    echo "N/A";
                                                        }
                                                     ?>
                                           </td>
                                        </tr>
                                    <?php }
                                }
                                else
                                { ?>
                                    <tr>
                                      <td colspan="4">
                                        <h3 class="p-3">No Results Found</h3>
                                      </td>
                                    </tr>
                                <?php }
                               ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
      
        
</div>
         
