<?php
    if (isset($_POST['student_remove'])) {
        $remove_class = $_POST['student_remove'];

        $sql = "DELETE FROM class_student WHERE cs_id = '$remove_class'";
        $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: " .mysqli_error(), E_USER_ERROR);
    }
?>

<label><b>Teacher:</b></label><br />

<?php
   $sql_teacher = "SELECT firstname, lastname FROM user, class WHERE teacher = username AND class_id = $selectedClass";
   $result_teacher = $conn->query($sql_teacher) or trigger_error("Query Failed! SQL: $sql_teacher - Error: " .mysqli_error(), E_USER_ERROR);
?>

<?php while ($row_teacher = $result_teacher->fetch_assoc()): ?>
   <label><?= $row_teacher['firstname']. ' ' .$row_teacher['lastname'] ?></label>
<?php endwhile; ?>

<br /><br />
<label><b>Members of this Class:</b></label>
<br /><br />

<form method="POST" action="#">
   <table>
      <tbody>
         <?php
            $sqlclass = "SELECT cs_id, image, user.username AS 'userid', firstname, lastname FROM user, class_student WHERE user.username = class_student.username AND class_student.class_id = '$selectedClass'";
            $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: " .mysqli_error(), E_USER_ERROR);
         ?>

         <?php while ($rowclass = $resultclass->fetch_assoc()): ?>
            <tr>
               <td><img src="<?= 'data:image/jpeg;base64,' .base64_encode($rowclass['image']) ?>" style="width: 40px; height: 40px;" /></td>
               <td style="font-size: 11px; width: 150px;"><?= $rowclass['firstname']. ' ' .$rowclass['lastname'] ?></td>

               <?php
                  $sqluser = "SELECT role FROM user WHERE username = '$user'";
                  $resultuser = $conn->query($sqluser) or trigger_error("Query Failed! SQL: $sqluser - Error: " .mysqli_error(), E_USER_ERROR);
               ?>
               <?php while ($rowuser = $resultuser->fetch_assoc()): ?>
                  <?php if ($rowuser['role'] == 'TEACHER'): ?>
                        <td><input type="image" src="img/remove.png" onclick="return confirm('Are you sure to remove this student from your class?')" name="student_remove" value="<?= $rowclass['cs_id'] ?>" style="width: 15px; height: 15px;" /></td>
                  <?php endif; ?>
               <?php endwhile; ?>
            </tr>
         <?php endwhile; ?>
      </tbody>
   </table>
</form>
