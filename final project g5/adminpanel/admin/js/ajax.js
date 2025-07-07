// Admin Log in
$(document).on("submit","#adminLoginFrm", function(){
   $.post("query/loginExe.php", $(this).serialize(), function(data){
      if(data.res == "invalid")
      {
        Swal.fire(
          'Invalid',
          'Please input valid username / password',
          'error'
        )
      }
      else if(data.res == "success")
      {
        Swal.fire({
          title: 'Success!',
          text: 'Login successful',
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
        }).then(function() {
          $('body').fadeOut();
          window.location.href='home.php';
        });
      }
   },'json');

   return false;
});



// Add Course 
$(document).on("submit","#addCourseFrm" , function(){
  $.post("query/addCourseExe.php", $(this).serialize() , function(data){
  	if(data.res == "exist")
  	{
  		Swal.fire(
  			'Already Exist',
  			data.course_name.toUpperCase() + ' Already Exist',
  			'error'
  		)
  	}
  	else if(data.res == "success")
  	{
  		Swal.fire(
  			'Success',
  			data.course_name.toUpperCase() + ' Successfully Added',
  			'success'
  		)
          // $('#course_name').val("");
          refreshDiv();
            setTimeout(function(){ 
                $('#body').load(document.URL);
             }, 2000);
  	}
  },'json')
  return false;
});

// Update Course
$(document).on("submit","#updateCourseFrm" , function(){
  $.post("query/updateCourseExe.php", $(this).serialize() , function(data){
     if(data.res == "success")
     {
        Swal.fire(
            'Success',
            'Selected cluster has been successfully updated!',
            'success'
          )
          refreshDiv();
     }
  },'json')
  return false;
});


// Delete Course
$(document).on("click", "#deleteCourse", function(e){
    e.preventDefault();
    var id = $(this).data("id");
    
    $.ajax({
      type : "post",
      url : "query/deleteCourseExe.php",
      dataType : "json",  
      data : {id:id},
      cache : false,
      success : function(data){
        if(data.res == "success")
        {
          Swal.fire({
            title: 'Deleted!',
            text: 'Selected Cluster has been deleted.',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          }).then(function() {
            refreshDiv();
          });
        }
      },
      error : function(xhr, ErrorStatus, error){
        console.log(status.error);
      }
    });
    return false;
});


// Delete Exam
$(document).on("click", "#deleteExam", function(e){
    e.preventDefault();
    var id = $(this).data("id");
    
    $.ajax({
      type : "post",
      url : "query/deleteExamExe.php",
      dataType : "json",  
      data : {id:id},
      cache : false,
      success : function(data){
        if(data.res == "success")
        {
          Swal.fire({
            title: 'Deleted!',
            text: 'Selected Exam has been deleted.',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          }).then(function() {
            refreshDiv();
          });
        }
      },
      error : function(xhr, ErrorStatus, error){
        console.log(status.error);
      }
    });
    return false;
});



// Add Exam 
$(document).on("submit","#addExamFrm" , function(){
  $.post("query/addExamExe.php", $(this).serialize() , function(data){
    if(data.res == "noSelectedCourse")
   {
      Swal.fire(
          'No Course',
          'Please select course',
          'error'
       )
    }
    if(data.res == "noSelectedTime")
   {
      Swal.fire(
          'No Time Limit',
          'Please select time limit',
          'error'
       )
    }
    if(data.res == "noDisplayLimit")
   {
      Swal.fire(
          'No Display Limit',
          'Please input question display limit',
          'error'
       )
    }

     else if(data.res == "exist")
    {
      Swal.fire(
        'Already Exist',
        data.examTitle.toUpperCase() + '<br>Already Exist',
        'error'
      )
    }
    else if(data.res == "success")
    {
      Swal.fire(
        'Success',
        data.examTitle.toUpperCase() + '<br>Successfully Added',
        'success'
      )
          $('#addExamFrm')[0].reset();
          $('#course_name').val("");
          refreshDiv();
    }
  },'json')
  return false;
});



// Update Exam 
$(document).on("submit","#updateExamFrm" , function(){
  $.post("query/updateExamExe.php", $(this).serialize() , function(data){
    if(data.res == "success")
    {
      Swal.fire(
          'Update Successfully',
          data.msg + ' <br>are now successfully updated',
          'success'
       )
          refreshDiv();
    }
    else if(data.res == "failed")
    {
      Swal.fire(
        "Something's went wrong!",
         'Somethings went wrong',
        'error'
      )
    }
   
  },'json')
  return false;
});

// Update Question
$(document).on("submit","#updateQuestionFrm" , function(){
  $.post("query/updateQuestionExe.php", $(this).serialize() , function(data){
     if(data.res == "success")
     {
        Swal.fire(
            'Success',
            'Selected question has been successfully updated!',
            'success'
          )
          refreshDiv();
     }
  },'json')
  return false;
});


// Delete Question
$(document).on("click", "#deleteQuestion", function(e){
    e.preventDefault();
    var id = $(this).data("id");
    
    $.ajax({
      type : "post",
      url : "query/deleteQuestionExe.php",
      dataType : "json",  
      data : {id:id},
      cache : false,
      success : function(data){
        if(data.res == "success")
        {
          Swal.fire({
            title: 'Deleted!',
            text: 'Selected Question has been deleted.',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          }).then(function() {
            refreshDiv();
          });
        }
      },
      error : function(xhr, ErrorStatus, error){
        console.log(status.error);
      }
    });
    return false;
});


// Add Question 
$(document).on("submit","#addQuestionFrm" , function(){
  $.post("query/addQuestionExe.php", $(this).serialize() , function(data){
    if(data.res == "exist")
    {
      Swal.fire(
          'Already Exist',
          data.msg + ' question <br>already exist in this exam',
          'error'
       )
    }
    else if(data.res == "success")
    {
      Swal.fire(
        'Success',
         data.msg + ' question <br>Successfully added',
        'success'
      )
        $('#addQuestionFrm')[0].reset();
        refreshDiv();
    }
   
  },'json')
  return false;
});


// Add Examinee
$(document).on("submit","#addExamineeFrm" , function(){
  $.post("query/addExamineeExe.php", $(this).serialize() , function(data){
    if(data.res == "noGender")
    {
      Swal.fire(
          'No Gender',
          'Please select gender',
          'error'
       )
    }
    else if(data.res == "noCourse")
    {
      Swal.fire(
          'No Course',
          'Please select course',
          'error'
       )
    }
    else if(data.res == "noLevel")
    {
      Swal.fire(
          'No Year Level',
          'Please select year level',
          'error'
       )
    }
    else if(data.res == "fullnameExist")
    {
      Swal.fire(
          'Fullname Already Exist',
          data.msg + ' are already exist',
          'error'
       )
    }
    else if(data.res == "emailExist")
    {
      Swal.fire(
          'Email Already Exist',
          data.msg + ' are already exist',
          'error'
       )
    }
    else if(data.res == "success")
    {
      Swal.fire(
          'Success',
          data.msg + ' has been successfully added and login credentials have been sent to their email.',
          'success'
       )
        refreshDiv();
        $('#addExamineeFrm')[0].reset();
    }
    else if(data.res == "success_no_mail")
    {
      Swal.fire(
          'Success',
          data.msg + ' has been successfully added but failed to send email. Please use the "Send Credentials" button to send login details.',
          'warning'
       )
        refreshDiv();
        $('#addExamineeFrm')[0].reset();
    }
    else if(data.res == "failed")
    {
      Swal.fire(
          "Something's Went Wrong",
          '',
          'error'
       )
    }


    
  },'json')
  return false;
});



// Update Examinee
$(document).on("submit","#updateExamineeFrm" , function(){
  $.post("query/updateExamineeExe.php", $(this).serialize() , function(data){
     if(data.res == "success")
     {
        Swal.fire({
          title: 'Success!',
          text: data.exFullname + ' has been successfully updated!',
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
        }).then(function() {
          refreshDiv();
          $('#updateExamineeFrm')[0].reset();
        });
     }
     else if(data.res == "failed")
     {
        Swal.fire({
          title: 'Error!',
          text: 'Failed to update examinee information.',
          icon: 'error'
        });
     }
  },'json')
  return false;
});


function refreshDiv()
{
  $('#tableList').load(document.URL +  ' #tableList');
  $('#refreshData').load(document.URL +  ' #refreshData');

}

// Send Credentials
$(document).on("click", ".send-credentials", function(){
    var exmne_id = $(this).data('id');
    
    Swal.fire({
        title: 'Send Credentials',
        text: 'Are you sure you want to send login credentials to this examinee?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, send it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("query/sendCredentials.php", {exmne_id: exmne_id}, function(data){
                if(data.res == "success") {
                    Swal.fire(
                        'Success!',
                        'Login credentials have been sent to the examinee.',
                        'success'
                    );
                } else if(data.res == "failed") {
                    Swal.fire(
                        'Error!',
                        data.msg || 'Failed to send login credentials. Please try again.',
                        'error'
                    );
                } else if(data.res == "not_found") {
                    Swal.fire(
                        'Error!',
                        data.msg || 'Examinee not found.',
                        'error'
                    );
                } else if(data.res == "error") {
                    Swal.fire(
                        'Error!',
                        data.msg || 'An error occurred. Please try again.',
                        'error'
                    );
                }
            }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire(
                    'Error!',
                    'Failed to connect to the server. Please try again.',
                    'error'
                );
            });
        }
    });
});


