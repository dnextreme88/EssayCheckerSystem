<!DOCTYPE html>
<?php
    include 'db.php';
    include 'navi.php';
    session_start();

    $user = $_SESSION['user'];
    if (!isset($_SESSION['user'])) {
        echo "<script>window.location.href = 'index.php';</script>";
    }

    include 'essayList.php';
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

    if (isset($_POST['submitessay'])) {
        date_default_timezone_set('Asia/Singapore');

        $essaytitle = mysqli_real_escape_string($conn, $_POST['essaytitle']);
        $essaycontent = mysqli_real_escape_string($conn, $_POST['essaycontent']);
        $mws = $_POST['mws'];
        $gs = $_POST['gs'];
        $date = date('m/d/Y H:i');
        $sqlinsert = "INSERT INTO essay (essay_title, essay_content, username, date_created, essay_status, mws, gs) VALUES ('$essaytitle', '$essaycontent', '$user', '$date', 'ACTIVE', '$mws', '$gs');";
        $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: " .mysqli_error(), E_USER_ERROR);

        echo "<script>
            alert('Essay Created Successfully!');
            window.location.href = 'createEssay.php';
        </script>";
    }

    if (isset($_POST['btnEdit'])) {
        $_SESSION['edit_id'] = $_POST['btnEdit'];

        echo "<script>window.location.href = 'editEssay.php';</script>";
    }

    if (isset($_POST['essayremove'])) {
        $remove_id = $_POST['essayremove'];
        $sqlinsert = "UPDATE essay SET essay_status = 'REMOVED' WHERE essay_id = '$remove_id'";
        $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: " .mysqli_error(), E_USER_ERROR);

        echo "<script>window.location.href = 'createEssay.php';</script>";
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
            <label style="font-size: 15px; font-family: Arial, Charcoal, sans-serif;">ID: <?= $user ?></label>
        </fieldset>

        <fieldset id="myClassroom" style="height: auto;">
            <br />

            <form method="POST" action="#">
                <label>Essay Title:</label>
                <br /><br />
                <input type="text" name="essaytitle" placeholder="Essay Title" maxlength="500" style="width: 750px;" required />
                <br /><br />

                <label>Your Essay for this title:</label>
                <br /><br />
                <textarea name="essaycontent" placeholder="Maximum of 1500 Characters!" maxlength="1500" style="width: 745px; height: 150px; background: white; border-radius: 5px;" required></textarea>
                <br />

                <label>Match Word Score Percentage:</label>
                <br />
                <input type="number" name="mws" id="mws" style="width: 80px;" /> %
                <br />

                <label>Grammar & Spelling Score Percentage:</label>
                <br />
                <input type="number" name="gs" id="gs" style="width: 80px;" /> %
                <br />

                <input type="submit" name="submitessay" value="Create Essay" class="success" style="margin-left: 595px;" />
            </form>
        </fieldset>

        <fieldset id="footer">
            <label>Essay Checker (Virtual Classroom) &copy; 2017</label>
        </fieldset>
    </div>
</body>
</html>
