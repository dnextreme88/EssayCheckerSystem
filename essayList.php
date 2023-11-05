<?php
    include 'headercontent.php';

    $user = $_SESSION['user'];
?>

<fieldset id="submission">
    <label><b>Essay List: </b></label>
    <br /><br />

    <form method="POST" action="#">
        <table>
            <tbody>
                <?php
                    $sqlclass = "SELECT essay_title, essay_id FROM essay WHERE username = '$user' AND essay_status = 'ACTIVE' ORDER BY essay_id DESC";
                    $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: " .mysqli_error(), E_USER_ERROR);
                ?>

                <?php while ($rowclass = $resultclass->fetch_assoc()): ?>
                    <tr>
                        <td style="font-size: 11px; padding: 3px; width: 200px; border-bottom: 1px solid gray;"><button type="submit" class="linkbutton" name="btnEdit" value="<?= $rowclass['essay_id'] ?>"><?= $rowclass['essay_title'] ?></button></td>
                        <td style="border-bottom: 1px solid gray;"><input type="image" src="img/remove.png" onclick="return confirm('Are you sure to delete this essay?')" name="essayremove" value="<?= $rowclass['essay_id'] ?>" style="width: 13px; height: 13px;" /></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
</fieldset>
