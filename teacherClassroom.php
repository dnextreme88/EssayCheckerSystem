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
    if(isset($_POST['addessay'])){
        $essayid=$_POST['essay'];
        date_default_timezone_set('Asia/Singapore');
        $date = date('m/d/Y H:i');
        $sqlinsert = "INSERT INTO class_essay (essay_id,class_id,date_assigned) VALUES ('$essayid' , '$selectedClass','$date');";
        $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: ".mysqli_error(), E_USER_ERROR);
         echo "<script>
       alert('Essay Added to this Class Successfully!');
         window.location.href='teacherClassroom.php';    
        </script>";   
        
    }
    if(isset($_POST['view'])){
      $_SESSION['essay_id']=$_POST['view']; 
       echo "<script>
      
         window.location.href='teacherclassAnswer.php';    
        </script>"; 
    }
    if(isset($_POST['prevBtn'])){
       header("location:javascript://history.go(-1)");
        
    }
    
     if(isset($_POST['remove'])){
      $ce_id = $_POST['remove'];
      $sqlinsert = "update class_essay set ce_status = 'ENDED' where ce_id = '$ce_id'";
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
                
		<br>&nbsp;<button class="success"><a href="teacherclass.php" style="text-decoration:none;color:white;">Back</a></button></br></br>
                  <label>Class Code : <?php echo $selectedClass; ?></label>   <Br><br>
                
                 <form method="post" action="#">
                 <label>Essay List : </label>
                 <select name="essay" style="width:400px;" required>
                  <?php
                  $sqlessay = "SELECT * FROM essay where username='$user' and essay_status = 'ACTIVE'";
                  $resultessay = $conn->query($sqlessay) or trigger_error("Query Failed! SQL: $sqlessay - Error: ".mysqli_error(), E_USER_ERROR); ;
                  while($rowessay = $resultessay->fetch_assoc()) {
                  ?>
                     <option value="<?php echo $rowessay['essay_id'] ?>"><?php echo $rowessay['essay_title'] ?></option>
                   <?php
                  }
                  ?>
                 </select>
                 &nbsp;<input type='submit' name='addessay' value='Add Essay to this Class' class='success' />
                 </form>
                 
                 <br><br>
                 <table style='margin-top:0px;width:700px; text-align: left'>
                     <thead>
                         <tr>
                             <th width="50">ID</th>
                             <th width="330">Title</th>
                             <th width="120">Date</th>
                             <th width="50">Action</th>
                         </tr>
                     <tbody>
                          <?php
                        $sqlessay2 = "SELECT ce_id,essay_title,date_assigned FROM essay,class_essay where class_essay.essay_id = essay.essay_id and class_id='$selectedClass' and ce_status='ACTIVE'";
                        $resultessay2 = $conn->query($sqlessay2) or trigger_error("Query Failed! SQL: $sqlessay2 - Error: ".mysqli_error(), E_USER_ERROR); ;
                        while($rowessay2 = $resultessay2->fetch_assoc()) {
                        ?>
                         <tr>
                             <td><?php echo $rowessay2['ce_id']; ?></td>
                             <td><?php echo $rowessay2['essay_title']; ?></td>
                             <td><?php echo $rowessay2['date_assigned']; ?></td>
                             <td>
                             <form method="POST" action="#">
                             <input type="image" name ="view" src="img/view.png" value="<?php echo $rowessay2['ce_id']; ?>" style="height: 12px;width: 15px;">
                             <input type="image" name="remove" onclick="return confirm('Are you sure to end this essay for this class?')" src="img/remove.png" value="<?php echo $rowessay2['ce_id']; ?>" style="height: 12px;width: 15px;">
                            </form>
                             </td>
                     </tr>
                   <?php
                  }
                  ?>
                         
                     </tbody>
                     </thead>
                     
                 </table>
             </fieldset>
             <fieldset id="submission">    
                
                <?php  include 'classMember.php'; ?>
               
                </fieldset> 
             
             
            
            
        
           
            </div>
       
    </body>
</html>

