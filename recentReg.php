<?php
    include 'headercontent.php';

    $user = $_SESSION['user'];
?>

<fieldset id="submission">
    <label><b>Recent Registration: </b></label>
    <br /><br />

    <table>
        <tbody>
            <?php
                $sqlclass = "SELECT username, role FROM user WHERE username != 'admin' LIMIT 30";
                $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: " . mysqli_error(), E_USER_ERROR);
            ?>

            <?php while ($rowclass = $resultclass->fetch_assoc()): ?>
                <tr>
                    <td style="font-size: 11px; padding: 3px; border-bottom: 1px solid gray;"><?= $rowclass['username'] ?> registered to your website as a <?= $rowclass['role'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</fieldset>
