<?php        include 'headercontent.php'; 
        
        
        $user = $_SESSION['user'];
?>
<fieldset id="submission">    
                




                        <label><b>Who answered this essay? </b></label><br>
<br> 
<form method="POST" action="#">
<table >
                     <tbody>
                  <?php
                        $sqlclass = "select username,score,date_submitted from essay_answer where essay_id ='$essayid' order by answer_id desc";
                        $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: ".mysqli_error(), E_USER_ERROR);
                        
                        while($rowclass = $resultclass->fetch_assoc()) {
                        ?>
                         <tr>
                             
                             <td style='font-size: 11px;padding:3px;width:200px;border-bottom: 1px solid gray;'>
                                 <?php echo $rowclass['username']; ?> got <?php echo $rowclass['score']; ?> point(s) last <?php echo $rowclass['date_submitted']; ?>
                             </td>
                             
                         </tr>
                 
                 <?php
                        }
                        ?>
                     </tbody>
                 </table>
    </form>
                
               
                </fieldset> 
