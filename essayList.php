<?php        include 'headercontent.php'; 
        
        
        $user = $_SESSION['user'];
?>
<fieldset id="submission">    
                




                        <label><b>Essay List : </b></label><br>
<br> 
<form method="POST" action="#">
<table >
                     <tbody>
                  <?php
                        $sqlclass = "select essay_title,essay_id from essay where username = '$user' and essay_status = 'ACTIVE' order by essay_id desc";
                        $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: ".mysqli_error(), E_USER_ERROR);
                        
                        while($rowclass = $resultclass->fetch_assoc()) {
                        ?>
                         <tr>
                             
                             <td style='font-size: 11px;padding:3px;width:200px;border-bottom: 1px solid gray;'><button type="submit" class="linkbutton" name="btnEdit" value="<?php echo $rowclass['essay_id']; ?>"><?php echo $rowclass['essay_title']; ?></button></td>
                             <td style='border-bottom: 1px solid gray;'><input type="image" src="img/remove.png" onclick="return confirm('Are you sure to delete this essay?')" name="essayremove" value="<?php echo $rowclass['essay_id']; ?>" style="width:13px;height: 13px"/></td>
                             
                         </tr>
                 
                 <?php
                        }
                        ?>
                     </tbody>
                 </table>
    </form>
                
               
                </fieldset> 
