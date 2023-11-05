<?php include 'headercontent.php'; ?>

<fieldset id="submission">
    <label>Scores</label>
    <br /><br />

    <table>
        <tbody>
            <?php
                $sqlessayscore = "SELECT DISTINCT score, essay_title"
                    . " FROM essay_answer, essay, class_essay WHERE essay_answer.username = '$user'"
                    . " AND essay_answer.essay_id = essay.essay_id AND class_essay.essay_id = essay_answer.essay_id AND class_essay.essay_id = essay.essay_id ORDER BY answer_id DESC LIMIT 15";
                $resultessayscore = $conn->query($sqlessayscore);
            ?>

            <?php while ($rowessayscore = $resultessayscore->fetch_assoc()): ?>
                <tr>
                    <td style="font-size: 11px; padding: 3px; border-bottom: 1px solid gray;">You got <?= $rowessayscore['score'] ?> point(s) in "<?= $rowessayscore['essay_title'] ?>"</td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</fieldset>
