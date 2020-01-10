<!DOCTYPE html>
<?php
include 'db.php';

include 'studentNavi.php';

$essaytitle = '';
$essayselectedid='';
session_start(); 
  
        $user = $_SESSION['user'];
        $selectedClass =$_SESSION['selectedclass'];
        if( !isset($_SESSION['user']) ){
         echo "<script>
       
        window.location.href='index.php';
        </script>";
        }
    $sqlstore = "SELECT * FROM user where username='$user'";
    $resultstore = $conn->query($sqlstore);
    $ln='';
    $fn='';
    $pass ='';
    $userimage='';
    while($rowstore = $resultstore->fetch_assoc()) {
     $userimage= $rowstore['image'];   
         $ln= $rowstore['lastname'];                     
         $fn= $rowstore['firstname'];
         $pass =$rowstore['password'];
    }
    
    if(isset($_POST['view'])){
        $ce_id = $_POST['view'];
      
        $sqlstore = "SELECT essay.essay_id as 'essayid',essay_title,essay_content FROM essay,class_essay where essay.essay_id = class_essay.essay_id and ce_id = '$ce_id'";
    $resultstore = $conn->query($sqlstore);
        while($rowstore = $resultstore->fetch_assoc()) {
            $essaytitle = htmlspecialchars($rowstore['essay_title'], ENT_QUOTES);
            $essayselectedid =  $rowstore['essayid'];
             $_SESSION['essayid'] = $rowstore['essayid'];
            
    }
    
        
    }
    if(isset($_POST['submitessay'])){
          $ans =mysqli_real_escape_string($conn,$_POST['essaycontent']);
          $essayselectedid = $_SESSION['essayid'];
          date_default_timezone_set('Asia/Singapore');
        $date = date('m/d/Y H:i');
         $sqlinsert = "INSERT INTO essay_answer (essay_id,username,answer,score,date_submitted) VALUES ($essayselectedid ,'$user', '$ans',0.0,'$date');";
        $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: ".mysqli_error(), E_USER_ERROR);
        echo "<script>
        window.location.href='StudentEssayResult.php';
        
        </script>";
         
    }
    
?>
<html>
    <head>
       <?php        include 'headercontent.php'; ?>
    </head>
    <body>
         <div id="container" >
            <div id="header">
              
                <label style="top:5px;left:15px;font-size: 30px;position:absolute">Vanguard Essay Checker</label> 
                
            </div>
            
            <fieldset id="teachprofile">
                
                      <img src='<?php  echo 'data:image/jpeg;base64,'.base64_encode( $userimage ) ?>' alt='no image' class='profimage' id="blah"/>
                      <br><br>
                      <label style='font-size: 15px;font-family: Arial, Charcoal, sans-serif;  '>ID: <?php echo $user; ?></label>
            </fieldset>
             <fieldset id="myClassroom" style="height: auto;">
                 <br>
                 <form method="post" action="#">
                 <a href="StudentClassroom.php" style="color: buttonface">Back</a><br>
                 <label>Essay Title:</label><br><br>
                 <input type="text" name="essaytitle" value='<?php echo $essaytitle; ?>' maxlength="500" style="width:750px;" disabled/><br><br>
                 <label>Your Essay for this title:</label><br><br>
                 <textarea name="essaycontent" placeholder="Maximum of 1500 Characters!" maxlength="1500" style="width:745px;height: 150px;background:white;border-radius: 5px;"></textarea>
                 <br><br>
                 <input type="submit" name="submitessay" value="Submit Essay" class="success" style="margin-left: 595px;"/>
                 </form>
             </fieldset>
           
              <fieldset id="submission">    
                
                <?php  include 'classMember.php'; ?>
               
                </fieldset> 
             
             
            
               <fieldset id="footer">     
                <label>Essay Checker (Virtual Classroom) ©2017</label>
                  
               
                </fieldset> 
        
           
            </div>
       
    </body>
</html>

