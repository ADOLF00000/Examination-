<link rel="stylesheet" type="text/css" href="css/mycss.css">
<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div>MANAGE EXAMINEE</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">Examinee List
                    </div>
                    <div class="table-responsive">
                        <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                            <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Gender</th>
                                <th>Birthdate</th>
                                <th>Subject</th>
                                <th>Year level</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $selExmne = $conn->query("SELECT * FROM examinee_tbl ORDER BY exmne_id DESC ");
                                if($selExmne->rowCount() > 0)
                                {
                                    while ($selExmneRow = $selExmne->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                           <td><?php echo $selExmneRow['exmne_fullname']; ?></td>
                                           <td><?php echo $selExmneRow['exmne_gender']; ?></td>
                                           <td><?php echo $selExmneRow['exmne_birthdate']; ?></td>
                                           <td>
                                            <?php 
                                                 $exmneSubject = isset($selExmneRow['exmne_course']) ? $selExmneRow['exmne_course'] : null;
                                                 if ($exmneSubject) {
                                                     $selSubject = $conn->query("SELECT * FROM course_tbl WHERE cou_id='$exmneSubject' ")->fetch(PDO::FETCH_ASSOC);
                                                     echo isset($selSubject['cou_name']) ? $selSubject['cou_name'] : 'N/A';
                                                 } else {
                                                     echo 'N/A';
                                                 }
                                             ?>
                                            </td>
                                           <td><?php echo $selExmneRow['exmne_year_level']; ?></td>
                                           <td><?php echo $selExmneRow['exmne_email']; ?></td>
                                           <td><?php echo $selExmneRow['exmne_password']; ?></td>
                                           <td><?php echo $selExmneRow['exmne_status']; ?></td>
                                           <td>
                                               <a rel="facebox" href="facebox_modal/updateExaminee.php?id=<?php echo $selExmneRow['exmne_id']; ?>" class="btn btn-sm btn-primary">Update</a>
                                               <button type="button" class="btn btn-sm btn-info send-credentials" data-id="<?php echo $selExmneRow['exmne_id']; ?>">Send Credentials</button>
                                           </td>
                                        </tr>
                                    <?php }
                                }
                                else
                                { ?>
                                    <tr>
                                      <td colspan="2">
                                        <h3 class="p-3">No Course Found</h3>
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
         
