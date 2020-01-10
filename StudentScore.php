<?php        include 'headercontent.php'; ?>
<fieldset id="submission">    
    <label>Scores</label><br><br>
                  <table>
                     <tbody>
                <?php 
                $sqlessayscore = "SELECT distinct score,essay_title"
                        . " FROM essay_answer,essay,class_essay where essay_answer.username='$user'"
                        . " and essay_answer.essay_id = essay.essay_id and class_essay.essay_id = essay_answer.essay_id  and class_essay.essay_id = essay.essay_id order  by answer_id desc Limit 15";
                $resultessayscore = $conn->query($sqlessayscore);
    
    while($rowessayscore = $resultessayscore->fetch_assoc()) {
    ?>
                
                         <tr>
                             <td style='font-size: 11px;padding:3px;border-bottom: 1px solid gray'>You got <?php echo $rowessayscore['score']; ?> point(s) in "<?php echo $rowessayscore['essay_title'];  ?>"   </td>  
                         </tr>
                               
                 
    <?php
         
    }
                
                ?>
               </tbody>
                     
                 </table> 
                </fieldset> 
