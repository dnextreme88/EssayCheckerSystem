<?php
    include 'headercontent.php';

    $user = $_SESSION['user'];
?>

<fieldset id="submission">
    <label><b>Who answered this essay?</b></label>
    <br /><br />

    <form method="POST" action="#">
        <table>
            <tbody>
                <?php
                    $sqlclass = "SELECT username, score, date_submitted FROM essay_answer WHERE essay_id = '$essayid' ORDER BY answer_id DESC";
                    $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: " .mysqli_error(), E_USER_ERROR);
                ?>

                <?php while ($rowclass = $resultclass->fetch_assoc()): ?>
                    <tr>
                        <td style="font-size: 11px; padding: 3px; width: 200px; border-bottom: 1px solid gray;">
                            <?= $rowclass['username'] ?> got <?= $rowclass['score'] ?> point(s) last <?= $rowclass['date_submitted'] ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
</fieldset>
