
<?php
if(isset($_POST['studentRemove'])){
   $removeclass = $_POST['studentRemove'];
    
    
    $sql= "delete from class_student where cs_id='$removeclass'";
    $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
  
    }

?>


<label><b>Teacher : </b></label><br>
 <?php
                        $sqlteach = "select firstname,lastname from user,class where teacher = username and class_id = $selectedClass";
                        $resultteach  = $conn->query($sqlteach ) or trigger_error("Query Failed! SQL: $sqlteach  - Error: ".mysqli_error(), E_USER_ERROR); ;
                        
                        while($rowteach  = $resultteach ->fetch_assoc()) {
                        ?>

                        <label><?php echo $rowteach['firstname'].' '.$rowteach['lastname'] ?></label>
                 
                 <?php
                        }
                         
                         
                         ?>
                        <br><br>
                        <label><b>Members of this Class : </b></label><br>
<br> 
<form method="POST" action="#">
<table>
                     <tbody>
                  <?php
                        $sqlclass = "select cs_id,image,user.username as 'userid',firstname,lastname from user, class_student where user.username = class_student.username and  class_student.class_id = '$selectedClass'";
                        $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: ".mysqli_error(), E_USER_ERROR); ;
                        
                        while($rowclass = $resultclass->fetch_assoc()) {
                        ?>
                         <tr>
                             <td><img src="<?php  echo 'data:image/jpeg;base64,'.base64_encode( $rowclass['image'] ) ?>" style="width:40px;height: 40px"</td>
                             <td style='font-size: 11px;width:150px;'><?php echo $rowclass['firstname'].' '.$rowclass['lastname']; ?></td>
                            
                             <?php $sqluser = "select role from user where username='$user'";
                                   $resultuser = $conn->query($sqluser) or trigger_error("Query Failed! SQL: $sqluser - Error: ".mysqli_error(), E_USER_ERROR); ;
                                 while($rowuser = $resultuser->fetch_assoc()) {
                                  if($rowuser['role'] == 'TEACHER'){
                                     ?>
                             <td ><input type="image" src="img/remove.png" onclick="return confirm('Are you sure to remove this student from your class?')" name="studentRemove" value="<?php echo $rowclass['cs_id']; ?>" style="width:15px;height: 15px"/></td>
                                  <?php }
                                  
                                  } ?>
                         </tr>
                 
                 <?php
                        }
                        ?>
                     </tbody>
                 </table>
    </form>