<!DOCTYPE html>
<?php
    include 'db.php';
    include 'navi.php';
    session_start();

    $user = $_SESSION['user'];
    if (!isset($_SESSION['user'])) {
        echo "<script>window.location.href = 'index.php';</script>";
    }

    $sqlstore = "SELECT * FROM user WHERE username = '$user'";
    $resultstore = $conn->query($sqlstore);
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

    $edit_title = '';
    $edit_content = '';
    $edit_mws = 0;
    $edit_gs = 0;
    $essayid = $_SESSION['edit_id'];
    $sqlstore = "SELECT essay_id, essay_title, essay_content, mws, gs FROM essay WHERE essay_id = '$essayid'";
    $resultstore = $conn->query($sqlstore);
    while ($rowstore = $resultstore->fetch_assoc()) {
        $edit_title = $rowstore['essay_title'];
        $edit_content = $rowstore['essay_content'];
        $edit_mws = $rowstore['mws'];
        $edit_gs = $rowstore['gs'];
    }

    if (isset($_POST['submitessay'])) {
        date_default_timezone_set('Asia/Singapore');

        $essaytitle = mysqli_real_escape_string($conn, $_POST['essaytitle']);
        $essaycontent = mysqli_real_escape_string($conn, $_POST['essaycontent']);
        $mws = $_POST['mws'];
        $gs = $_POST['gs'];
        $sqlinsert = "UPDATE essay SET essay_title = '$essaytitle', essay_content = '$essaycontent', mws = '$mws', gs = '$gs' WHERE essay_id = '$essayid'";
        $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: " .mysqli_error(), E_USER_ERROR);

        echo "<script>
            window.location.href = 'editEssay.php';
            alert('Essay Updated Successfully!');
        </script>";
    }

    if (isset($_POST['add'])) {
        $kw = $_POST['kw'];
        $kws = $_POST['kws'];
        $sqlstore = "SELECT * FROM essay_keyword WHERE essay_id = '$essayid' AND essay_kw = '$kw'";
        $resultstore = $conn->query($sqlstore);
        $rowcount = mysqli_num_rows($resultstore);
        if ($rowcount > 0) {
            echo "<script>
                window.location.href = 'editWordWeight.php';
                alert('Keyword Already Defined!');
            </script>";
        } else {
            $sqlinsert = "INSERT INTO essay_keyword (essay_id, essay_kw, kw_score) VALUES ('$essayid', '$kw', '$kws')";
            $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: " .mysqli_error(), E_USER_ERROR);

            echo "<script>
                window.location.href = 'editWordWeight.php';
                alert('Keyword Added Successfully!');
            </script>";
        }
    }

    if (isset($_POST['essayremove'])) {
        $remove_id = $_POST['essayremove'];
        $sqlinsert = "DELETE FROM essay_keyword WHERE essay_kw_id = '$remove_id'";
        $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: " .mysqli_error(), E_USER_ERROR);

        echo "<script>window.location.href = 'editWordWeight.php';</script>";
    }
?>
<html>

<head>
    <?php include 'headercontent.php'; ?>
</head>

<body>
    <div id="container">
        <div id="header">
            <label style="top: 5px; left: 15px; font-size: 30px; position: absolute;">Vanguard Essay Checker</label>
        </div>

        <fieldset id="teachprofile">
            <img src="<?= 'data:image/jpeg;base64,' .base64_encode($userimage) ?>" alt="No image" class="profimage" id="blah" />
            <br /><br />
            <label style="font-size: 15px;font-family: Arial, Charcoal, sans-serif;">ID: <?= $user ?></label>
        </fieldset>

        <fieldset id="myClassroom" style="height: auto;">
            <br />

            <form method="POST" action="#">
                <br />&nbsp;
                <button class="success"><a href="createEssay.php" style="text-decoration: none; color: white;">Back</a></button>
                <br /><br />

                <label>Essay Title:</label>
                <br /><br />
                <input type="text" name="essaytitle" placeholder="Essay Title" value="<?= $edit_title ?>" maxlength="500" style="width: 750px;" disabled />
                <br /><br />

                <label>Keyword: </label>
                <input type="text" name="kw" id="kw" required />

                <label>Score: </label>
                <input type="number" name="kws" id="kws" value="1" required />
                <input type="submit" class="success" name="add" value="Add Keyword" />
                <br /><br />
            </form>

            <form method="POST" action="#">
                <table>
                    <thead>
                        <tr style="text-align: left;">
                            <th>Keyword</th>
                            <th>Score</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <?php
                        $sqlclass = "SELECT * FROM essay_keyword WHERE essay_id = '$essayid'";
                        $resultclass = $conn->query($sqlclass) or trigger_error("Query Failed! SQL: $sqlclass - Error: " .mysqli_error(), E_USER_ERROR);
                    ?>

                    <?php while ($rowclass = $resultclass->fetch_assoc()): ?>
                        <tr>
                            <td style="font-size: 14px; padding: 3px; width: 300px; border-bottom: 1px solid gray;"><?= $rowclass['essay_kw'] ?></td>
                            <td style="font-size: 14px; padding: 3px; width: 100px; border-bottom: 1px solid gray;"><?= $rowclass['kw_score'] ?></td>
                            <td style="border-bottom: 1px solid gray;"><input type="image" src="img/remove.png" onclick="return confirm('Are you sure to delete this keyword?')" name="essayremove" value="<?= $rowclass['essay_kw_id'] ?>" style="width: 13px; height: 13px;" /></td>
                            <br />
                        </tr>
                    <?php endwhile; ?>
                </table>
            </form>
        </fieldset>

        <?php include 'essayAnswered.php'; ?>

        <fieldset id="footer">
            <br /><br /><br /><br /><br />
            <label>Essay Checker (Virtual Classroom) &copy; 2017</label>
        </fieldset>
    </div>
</body>
</html>
