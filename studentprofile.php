<!DOCTYPE html>
<?php
    include 'db.php';

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

    include 'studentNavi.php';
    include 'StudentScore.php';

    if (isset($_POST['uploadimage'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $sql = "UPDATE user SET image = '{$image}' WHERE username = '$user'";
        $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: " .mysqli_error(), E_USER_ERROR);

        echo "<script>window.location.href = 'studentprofile.php';</script>";
    }

    if (isset($_POST['update'])) {
        $last = mysqli_real_escape_string($conn, $_POST['ln']);
        $first = mysqli_real_escape_string($conn, $_POST['fn']);

        $sql = "UPDATE user SET lastname='$last',firstname='$first' WHERE username = '$user'";
        $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: " .mysqli_error(), E_USER_ERROR);

        echo "<script>
            alert('Name Updated Successfully!');
            window.location.href = 'studentprofile.php';
        </script>";
    }

    if (isset($_POST['change'])) {
        $op = mysqli_real_escape_string($conn, $_POST['op']);
        $np = mysqli_real_escape_string($conn, $_POST['np']);
        $cp = mysqli_real_escape_string($conn, $_POST['cp']);

        if ($_POST['op'] == $pass and ($np == $cp) and ($np != '')) {
            $sql = "UPDATE user SET password='$np' WHERE username = '$user'";
            $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: " .mysqli_error(), E_USER_ERROR);
            echo "<script>
                alert('Password Updated Successfully!');
                window.location.href = 'studentprofile.php';
            </script>";
        } else {
            echo "<script>
                alert('Incorrect Old Password or New and Confirm Password do not match!');
                window.location.href = 'studentprofile.php';
            </script>";
        }
    }
?>
<html>

<head>
    <title>Essay Checker</title>
    <link rel="stylesheet" type="text/css" href="css/maindesign.css">
    <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
    <meta charset=utf-8 />
</head>

<body>
    <div id="container">
        <div id="header">
            <label style="top: 5px; left: 15px; font-size: 30px; position: absolute;">Vanguard Essay Checker</label>
        </div>

        <fieldset id="teachprofile">
            <form method="POST" action="#" enctype="multipart/form-data">
                <img src="<?= 'data:image/jpeg;base64,' .base64_encode($userimage) ?>" alt="No image" class="profimage" id="blah" />
                <input type="file" name="image" id="image" onchange="readURL(this);" style="position: absolute; margin-left: -120px;" required />
                <br /><br />

                <input type="submit" value="Upload Image" name="uploadimage" class="success" style="width: 220px; margin-top: 10px;" />
            </form>
        </fieldset>

        <fieldset id="inform">
            <form method="POST" action="#">
                <br />
                <label>First Name: </label>
                <br /><br />
                <input type="text" name="fn" maxlength="50" value="<?= $fn ?>" required />
                <br /><br />

                <label>Last Name: </label>
                <br /><br />
                <input type="text" name="ln" maxlength="50" value="<?= $ln ?>" required />
                <br /><br />
                <input type="submit" value="Save" name="update" class="success" style="margin-top: 12px;" />
            </form>
        </fieldset>

        <fieldset id="note">
            <label>NOTE : The Administrator has the rights to suspend dummy accounts!</label><br />
        </fieldset>

        <fieldset id="pass">
            <form method="POST" action="#">
                <br />
                <label>Update Password</label>
                <br /><br />

                <label>Old Password:</label>
                <input type="password" name="op" maxlength="50" style="width: 300px; margin-left: 31px;" required />
                <br /><br />

                <label>New Password:</label>
                <input type="password" name="np" maxlength="50" style="width: 300px; margin-left: 23px;" required />
                <br /><br />

                <label>Confirm Password:</label>
                <input type="password" name="cp" maxlength="50" style="width: 300px;" />
                <br /><br />

                <input type="submit" value="Change Password" name="change" class="success" required />
            </form>
        </fieldset>

        <fieldset id="footer">
            <label>Essay Checker (Virtual Classroom) &copy; 2017</label>
        </fieldset>
    </div>
</body>
</html>

<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result).width(235).height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
