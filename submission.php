<?php        include 'headercontent.php'; 
        
        
        $user = $_SESSION['user'];
?>
<fieldset id="submission">    
                




                        <label><b>Recent Submissions : </b></label><br>
<br> 
<table>
                     <tbody>
                  <?php
                        $sqlclass = "select essay_answer.essay_id as 'essaysub',firstname,lastname from essay_answer,user,essay where  essay_answer.essay_id = essay.essay_id and essay_answer.username= user.username and essay.username ='$user' order by answer_id desc LIMIT 15";
                        $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: ".mysqli_error(), E_USER_ERROR); ;
                        
                        while($rowclass = $resultclass->fetch_assoc()) {
                        ?>
                         <tr>
                             
                             <td style='font-size: 11px;padding:3px;border-bottom: 1px solid gray'><?php echo $rowclass['firstname'].' '.$rowclass['lastname']; ?> answered your essay.</td>
                             
                         </tr>
                 
                 <?php
                        }
                        ?>
                     </tbody>
                 </table>
                
               
                </fieldset> 
