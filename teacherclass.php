<!DOCTYPE html>
<?php
include 'db.php';

include 'navi.php';
session_start(); 
       
        $user = $_SESSION['user'];
        if( !isset($_SESSION['user']) ){
         echo "<script>
       
        window.location.href='index.php';
        </script>";
        }
    include 'submission.php';     
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
if(isset($_POST['myClass'])){
     $_SESSION['selectedclass']=$_POST['myClass'];
     echo "<script>
       
         window.location.href='teacherClassroom.php';    
        </script>";   
}

 if(isset($_POST['createclass'])){
   $newclass = mysqli_real_escape_string($conn,$_POST['newclass']);
   $sqlinsert = "INSERT INTO class (class_name,teacher) VALUES ('$newclass' , '$user');";
   $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: ".mysqli_error(), E_USER_ERROR);
         echo "<script>
       alert('New Class Successfully Created!');
         window.location.href='teacherclass.php';    
        </script>";   
 }
 
  if(isset($_POST['removeclass'])){
  $removeclass = $_POST['removeclass'];
    
    $sql= "delete from class where class_id ='$removeclass'";

    $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
   
       echo "<script>
       alert('Class Remove Successfully!');
            
        </script>";  
        
    
 }

?>
<html  >
    <head>
        <title>Essay Checker</title>
        <link rel="stylesheet" type="text/css" href="css/maindesign.css">
         <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<meta charset=utf-8 />
       
 
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
            
           
             
           
            
            <fieldset id="classlist">
                <form method="POST" action="#">
                <Br><p><label>Class List</label>
                    <input type='text' name='newclass' placeholder="Enter New Class Name" maxlength="100" style='margin-left:340px;' required/>
                    <input type='submit' name='createclass' value='Create' class='success' />
                </p><br><br>
                </form>
                <form method="POST" action="#">
                 <table style='margin-top:0px;'>
                    <tbody>
                            <?php
                            $sql3 ="select * from class where teacher='$user'";
                            $result3 = $conn->query($sql3) or trigger_error("Query Failed! SQL: $sql3 - Error: ".mysqli_error(), E_USER_ERROR);;
                            $num_rows = mysqli_num_rows($result3);
                                
                            $arrCount=0;
                            while($rows = $result3->fetch_assoc()) { ?>
                         <tr>
                            <?php 
                            $arrCount++;
                            if($arrCount <= $num_rows){ ?>
                                <td id='tdstyle'>
                                <input type='image' src='img/classroom.jpg' id='classroom' name="myClass" value='<?php echo $rows['class_id']?>'/><br>
                                <label>Code:<?php echo $rows['class_id']?></label><br>
                                <textarea id='tbllbl' disabled><?php echo $rows['class_name']?></textarea>
                                <br><input type="image" onclick="return confirm('Are you sure you want to delete this class?')" src="img/remove.png" name ="removeclass" value='<?php echo $rows['class_id']?>' style="height:15px;width:15px;margin-top:50px;"/><label style="color:gray;font-size: 12px;">Remove</label>
                                </td>
                            <?php
                            $rows = $result3->fetch_assoc();
                            }
                            else{
                                break;
                            }
                                     
                            ?>
                             
                            <?php 
                            $arrCount++;
                            if($arrCount <= $num_rows){ ?>
                                <td id='tdstyle'>
                                <input type='image' src='img/classroom.jpg' id='classroom' name="myClass" value='<?php echo $rows['class_id']?>'/><br>
                                <label>Code:<?php echo $rows['class_id']?></label><br>
                                <textarea id='tbllbl' disabled><?php echo $rows['class_name']?></textarea>
                                <br><input type="image" onclick="return confirm('Are you sure you want to delete this class?')" src="img/remove.png" name="removeclass" value='<?php echo $rows['class_id']?>' style="height:15px;width:15px;margin-top:50px;"/><label style="color:gray;font-size: 12px;">Remove</label>
                                
                                </td>
                            <?php
                            $rows = $result3->fetch_assoc();
                            }
                            else{
                                break;
                            }
                                     
                            ?>
                            <?php 
                            $arrCount++;
                            if($arrCount <= $num_rows){ ?>
                                <td id='tdstyle'>
                                <input type='image' src='img/classroom.jpg' id='classroom' name="myClass" value='<?php echo $rows['class_id']?>'/><br>
                                
                                <label>Code:<?php echo $rows['class_id']?></label><br>
                                <textarea id='tbllbl' disabled><?php echo $rows['class_name']?></textarea>
                                <br><input type="image" onclick="return confirm('Are you sure you want to delete this class?')" src="img/remove.png" name="removeclass" value='<?php echo $rows['class_id']?>' style="height:15px;width:15px;margin-top:50px;"/><label style="color:gray;font-size: 12px;">Remove</label>
                                
                                </td>
                            <?php
                            $rows = $result3->fetch_assoc();
                            }
                            else{
                                break;
                            }
                                     
                            ?>
                            <?php 
                            $arrCount++;
                            if($arrCount <= $num_rows){ ?>
                                <td id='tdstyle'>
                                <input type='image' src='img/classroom.jpg' id='classroom' name="myClass" value='<?php echo $rows['class_id']?>'/><br>
                               
                                <label>Code:<?php echo $rows['class_id']?></label><br>
                                <textarea id='tbllbl' disabled><?php echo $rows['class_name']?></textarea>
                                <br><input type="image" onclick="return confirm('Are you sure you want to delete this class?')" src="img/remove.png" name="removeclass" value='<?php echo $rows['class_id']?>' style="height:15px;width:15px;margin-top:50px;"/><label style="color:gray;font-size: 12px;">Remove</label>
                                
                                </td>
                            <?php
                            $rows = $result3->fetch_assoc();
                            }
                            else{
                                break;
                            }
                                     
                            ?>
                            <?php 
                            $arrCount++;
                            if($arrCount <= $num_rows){ ?>
                                <td id='tdstyle'>
                                <input type='image' src='img/classroom.jpg' id='classroom' name="myClass" value='<?php echo $rows['class_id']?>'/><br>
                               
                                <label>Code:<?php echo $rows['class_id']?></label><br>
                                <textarea id='tbllbl' disabled><?php echo $rows['class_name']?></textarea>
                                <br><input type="image" onclick="return confirm('Are you sure you want to delete this class?')" src="img/remove.png" name="removeclass" value='<?php echo $rows['class_id']?>' style="height:15px;width:15px;margin-top:50px;"/><label style="color:gray;font-size: 12px;">Remove</label>
                                
                                </td>
                            <?php
                            
                            }
                            else{
                                break;
                            }
                                     
                            ?>
                             
                         </tr>   
                                    
                                
                                
                             <?php   
                                }
                                
                ?>
                      </tbody>     
                    </table>
                
                
                    
                </form>      
            </fieldset>
          
            
            
        
           
            </div>
       
    </body>
    
</html>
<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(235)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
 

