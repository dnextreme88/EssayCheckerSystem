<?php
    include 'headercontent.php';
    $user = $_SESSION['user'];
?>

<fieldset id="submission">
    <label><b>Recent Submissions: </b></label>
    <br /><br />

    <table>
        <tbody>
            <?php
                $sqlclass = "SELECT essay_answer.essay_id AS 'essaysub', firstname, lastname FROM essay_answer, user, essay WHERE essay_answer.essay_id = essay.essay_id AND essay_answer.username = user.username AND essay.username = '$user' ORDER BY answer_id DESC LIMIT 15";
                $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: " .mysqli_error(), E_USER_ERROR);
            ?>

            <?php while ($rowclass = $resultclass->fetch_assoc()): ?>
                <tr>
                    <td style="font-size: 11px; padding: 3px; border-bottom: 1px solid gray;"><?= $rowclass['firstname']. ' ' .$rowclass['lastname'] ?> answered your essay.</td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</fieldset>
