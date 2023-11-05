<!DOCTYPE html>
<?php
    include 'db.php';

    session_start();

    $user = $_SESSION['user'];
    $selectedClass = $_SESSION['selectedclass'];
    if (!isset($_SESSION['user'])) {
        echo "<script>window.location.href = 'index.php';</script>";
    }

    $sqlstore = "SELECT * FROM user WHERE username = '$user'";
    $resultstore = $conn->query($sqlstore) or trigger_error("Query Failed! SQL: $sqlstore - Error: " .mysqli_error(), E_USER_ERROR);
    $ln = '';
    $fn = '';
    $pass = '';
    $userimage = '';
    while ($rowstore = $resultstore->fetch_assoc()) {
        $userimage = $rowstore['image'];
        $ln = $rowstore['lastname'];
        $fn = $rowstore['firstname'];
        $pass = $rowstore['password'];
    }

    if (isset($_POST['removeclass'])) {
        $removeclass = $_POST['removeclass'];
        $sql = "DELETE FROM class_student WHERE class_id ='$selectedClass' AND username = '$user'";
        $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: " .mysqli_error(), E_USER_ERROR); {
        echo "<script>
            alert('Class left Successfully!');
            window.location.href = 'StudentClass.php';
        </script>";
        }
    }
?>
<html>

<head>
    <?php include 'headercontent.php'; ?>
</head>

<body>
    <?php include 'studentNavi.php'; ?>
    <div id="container">
        <div id="header">
            <label style="top: 5px; left: 15px; font-size: 30px; position: absolute;">Vanguard Essay Checker</label>
        </div>

        <fieldset id="teachprofile">
            <img src="<?= 'data:image/jpeg;base64,' .base64_encode($userimage) ?>" alt="No image" class="profimage" id="blah" />
            <br /><br />
            <label style="font-size: 15px; font-family: Arial, Charcoal, sans-serif;">ID: <?= $user ?></label>
        </fieldset>

        <fieldset id="myClassroom">
            <form method="POST" action="#">
                <br />&nbsp;
                <button class="success"><a href="studentclass.php" style="text-decoration: none; color: white;">Back</a></button>
                <br /><br />
                <label>Class Code: <?= $selectedClass ?></label>

                <input type="submit" name="removeclass" onclick="return confirm('Are you sure you want to leave this class?')" value="Not a member of this class?" class="success" />
                <br /><br />
            </form>

            <br /><br />

            <table style="margin-top: 0; width: 700px; text-align: left;">
                <thead>
                    <tr>
                        <th width="330" style="border-bottom: 2px solid gray;">Title</th>
                        <th width="120" style="border-bottom: 2px solid gray;">Date</th>
                        <th width="60" style="border-bottom: 2px solid gray;">Score</th>
                        <th width="50" style="border-bottom: 2px solid gray;">Answer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sqlessay2 = "SELECT ce_id, essay_title, date_assigned, score, ce_status"
                            . " FROM essay"
                            . " INNER JOIN class_essay"
                            . " ON"
                            . " class_essay.essay_id = essay.essay_id AND class_essay.class_id = '$selectedClass'"
                            . " LEFT JOIN essay_answer"
                            . " ON class_essay.essay_id = essay_answer.essay_id AND essay_answer.username = '$user'";

                        $resultessay2 = $conn->query($sqlessay2) or trigger_error("Query Failed! SQL: $sqlessay2 - Error: " .mysqli_error(), E_USER_ERROR);
                    ?>

                    <?php while ($rowessay2 = $resultessay2->fetch_assoc()): ?>
                        <tr>
                            <td style="border-bottom: 1px solid gray;"><?= $rowessay2['essay_title'] ?></td>
                            <td style="border-bottom: 1px solid gray;"><?= $rowessay2['date_assigned'] ?></td>
                            <td style="border-bottom: 1px solid gray;"><?= $rowessay2['score'] != null ? $rowessay2['score'] : 'No Answer' ?></td>
                            <td style="border-bottom: 1px solid gray;">
                                <?php if ($rowessay2['score'] == null and $rowessay2['ce_status'] != 'ENDED'): ?>
                                    <form method="POST" action="StudentAnswer.php">
                                        <input type="image" name="view" src="img/answer.png" value="<?= $rowessay2['ce_id'] ?>" style="height: 25px; width: 25px;" />
                                    </form>
                                <?php else: ?>
                                    <?= 'FINISHED' ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </fieldset>
        <fieldset id="submission">
            <?php include 'classMember.php'; ?>
        </fieldset>
    </div>
</body>
</html>
