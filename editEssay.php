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
                <br />&nbsp;<button class="success"><a href="createEssay.php" style="text-decoration: none; color: white;">Back</a></button>
                <br /><br />

                <label>Essay Title:</label>
                <br /><br />
                <input type="text" name="essaytitle" placeholder="Essay Title" value="<?= $edit_title ?>" maxlength="500" style="width: 750px;" required />
                <br /><br />

                <label>Your Essay for this title:</label>
                <br /><br />
                <textarea name="essaycontent" placeholder="Maximum of 1500 Characters!" maxlength="1500" style="width: 745px; height: 150px; background: white; border-radius: 5px;" required><?= $edit_content ?></textarea>
                <br />

                <label>Match Word Score Percentage:</label>
                <br />
                <input type="number" name="mws" id="mws" value="<?= $edit_mws ?>" style="width: 80px;" /> %
                <br />

                <label>Grammar & Spelling Score Percentage:</label>
                <br />
                <input type="number" name="gs" id="gs" value="<?= $edit_gs ?>" style="width: 80px;" /> %
                <br /><br />

                <input type="submit" name="submitessay" value="Update Essay" class="success" style="margin-left: 590px; position: absolute;" />
            </form>

            <form method="POST" action="editWordWeight.php">
                <input type="submit" name="editww" value="Edit Word Weights" class="success" style="margin-left: 395px;" />
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
