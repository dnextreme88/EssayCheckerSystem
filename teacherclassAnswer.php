<!DOCTYPE html>
<?php
include 'db.php';

session_start(); 
  
        $user = $_SESSION['user'];
        $selectedClass =$_SESSION['selectedclass'];
        if( !isset($_SESSION['user']) ){
         echo "<script>
       
        window.location.href='index.php';
        </script>";
        }
    $sqlstore = "SELECT * FROM user where username='$user'";
    $resultstore = $conn->query($sqlstore) or trigger_error("Query Failed! SQL: $sqlstore - Error: ".mysqli_error(), E_USER_ERROR);
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
 
        $essayid = $_SESSION['essay_id'];
        $essaytitle='';
        $essaynewid ='';
        $sqlessay = "SELECT essay_id FROM class_essay where ce_id='$essayid'";
        $resultessay = $conn->query($sqlessay) or trigger_error("Query Failed! SQL: $sqlessay - Error: ".mysqli_error(), E_USER_ERROR);
         while($rowessay = $resultessay->fetch_assoc()) {
            $essaynewid = $rowessay['essay_id'];
            $sqlessay2 = "SELECT essay_title FROM essay where essay_id='$essaynewid'";
            $resultessay2 = $conn->query($sqlessay2) or trigger_error("Query Failed! SQL: $sqlessay2 - Error: ".mysqli_error(), E_USER_ERROR);
            while($rowessay2 = $resultessay2->fetch_assoc()){
                $essaytitle = $rowessay2['essay_title'];
            }
         }
        
    
    if(isset($_POST['save'])){
        $score = $_POST['score'];
                $answer_id = $_POST['save'];
         $sqlinsert = "update essay_answer set score ='$score' where answer_id = '$answer_id '";
        $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: ".mysqli_error(), E_USER_ERROR);
        echo "<script>
       alert('Score Updated!');
         window.location.href='teacherclassAnswer.php';    
        </script>";   
        
    }
      
     if(isset($_POST['done'])){
     
      $sqlinsert = "update class_essay set ce_status = 'ENDED' where ce_id = ' $essayid'";
        $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: ".mysqli_error(), E_USER_ERROR);
        
         
       echo "<script>
      
         window.location.href='teacherClassroom.php';    
        </script>"; 
    }
?>
<html>
    <head>
       <?php        include 'headercontent.php'; ?>
    </head>
    <body>
        <?php include 'navi.php'; ?>
         <div id="container" >
            <div id="header">
              
                <label style="top:5px;left:15px;font-size: 30px;position:absolute">Vanguard Essay Checker</label> 
                
            </div>
            
            <fieldset id="teachprofile">
                
                      <img src='<?php  echo 'data:image/jpeg;base64,'.base64_encode( $userimage ) ?>' alt='no image' class='profimage' id="blah"/>
                      <br><br>
                      <label style='font-size: 15px;font-family: Arial, Charcoal, sans-serif;  '>ID: <?php echo $user; ?></label>
            </fieldset>
             <fieldset id="myClassroom">
                 <form method="POST" action="#">
                    
                     <br>&nbsp;<button class="success"><a href="teacherClassroom.php" style="text-decoration:none;color:white;">Back</a></button></br></br>
                 <label>Class Code : <?php echo $selectedClass; ?></label>   <Br><br>
                  <label>Essay Title : <?php echo $essaytitle; ?> &nbsp;</label><input type="submit" onclick="return confirm('Are you sure to end this essay for this class?')" name="done" value="End this essay?" class="success"/>
                 </form>
                 <br><br>
                 <form method="POST" action="#">
                 <table style='margin-top:0px;width:750px; text-align: left'>
                     <thead >
                         <tr >
                             
                             <th width="150" style="border: 1px solid gray;background:white;color:black;padding:5px;">Name</th>
                             <th width="300" style="border: 1px solid gray;background:white;color:black;padding:5px;">Answer</th>
                             <th width="50" style="border: 1px solid gray;background:white;color:black;padding:5px;">Score</th>
                             <th width="50" style="border: 1px solid gray;background:white;color:black;padding:5px;">Date Submitted</th>
                             <th width="50" style="border: 1px solid gray;background:white;color:black;padding:5px;">Action</th>
                             
                      
                         </tr>
                             </thead>
                             
                     <tbody>
                          <?php
                        $sqlessay2 = "SELECT distinct answer_id,class_student.username as id, concat (firstname,' ', lastname) as name, "
                                . " answer, score, date_submitted "
                                . " from user,class_student,essay_answer,essay"
                                . " where user.username = class_student.username and user.username=essay_answer.username"
                                . " and essay_answer.essay_id = '$essaynewid' "
                                . " and class_student.class_id = '$selectedClass'";
                        $resultessay2 = $conn->query($sqlessay2) or trigger_error("Query Failed! SQL: $sqlessay2 - Error: ".mysqli_error(), E_USER_ERROR); ;
                        
                        while($rowessay2 = $resultessay2->fetch_assoc()) {
                        ?>
                         <tr>
                    
                            
                             <td style="border: 1px solid gray;background:white;color:black;padding:5px;"><?php echo $rowessay2['name']; ?></td>
                            <td style="border: 1px solid gray;background:white;color:black;padding:5px;"><?php echo $rowessay2['answer']; ?></td>
                            <td style="border: 1px solid gray;background:white;color:black;padding:5px;"><input type="number" name="score" value="<?php echo $rowessay2['score']; ?>" max="100" min="0" style="width:75px;" required/></td>
                            <td style="border: 1px solid gray;background:white;color:black;padding:5px;"><?php echo $rowessay2['date_submitted']; ?></td>
                            <td style="border: 1px solid gray;background:white;color:black;padding:5px;"><input type="image" name="save" src="img/save.png" value="<?php echo $rowessay2['answer_id']; ?>" style="height: 25px;width: 25px;"></td>
                         
                     </tr>
                   <?php
                  }
                  ?>
                         
                     </tbody>
                     
                 
                     
                 </table>
                     </form>
             </fieldset>
             <fieldset id="submission">    
               
                <?php  include 'classMember.php'; ?>
               
                </fieldset> 
             
             
            
            
        
           
            </div>
       
    </body>
</html>

