<!DOCTYPE html>
<?php
    include 'db.php';
    // session_start();

    if (isset($_POST['login'])) {
        $user = mysqli_real_escape_string($conn, $_POST['userid']);
        $pass = mysqli_real_escape_string($conn, $_POST['pass']);

        $_SESSION['user'] = $user;
        $sql = "SELECT * FROM user WHERE username = '$user' AND password = '$pass' AND status = 'ACTIVE'";
        $result = $conn->query($sql) or die(mysqli_error());

        if (mysqli_num_rows($result)) {
            $_SESSION['user'] = $user;

            while ($row = $result->fetch_assoc()) {
                if ($row['role'] == 'STUDENT' and $row['status'] == 'ACTIVE') {
                    echo "<script>
                        alert('Login Successful " .$user. "');
                        window.location.href = 'studentprofile.php';
                    </script>";
                }

                if ($row['role'] == 'TEACHER' and $row['status'] == 'ACTIVE') {
                    echo "<script>
                        alert('Login Successful');
                        window.location.href = 'teacherprofile.php';
                    </script>";
                }
                if ($row['role'] == 'ADMIN') {
                    echo "<script>
                        alert('Login Successful');
                        window.location.href = 'userManagement.php';
                    </script>";
                }
            }
        } else {
            echo "<script>
                alert('Incorrect Username or Password!');
                window.location.href = 'index.php';
            </script>";
        }
    }

    if (isset($_POST['register'])) {
        $newuser = mysqli_real_escape_string($conn, $_POST['newuser']);
        $newpass = mysqli_real_escape_string($conn, $_POST['newpass']);
        $conpass = mysqli_real_escape_string($conn, $_POST['confirmpass']);
        $role = $_POST['role'];
        $sql = "SELECT * FROM user WHERE username = '$newuser'";
        $result = $conn->query($sql) or die(mysqli_error());

        if (mysqli_num_rows($result)) {
            echo  "<script>
                alert('Username already registered!');
                window.location.href = 'index.php';
            </script>";
        } else {
            if ($newpass == $conpass and $newuser != '' and $newpass != '') {
                $_SESSION['user'] = $newuser;
                $sqlinsert = "INSERT INTO user (username, password, role, status) VALUES ('$newuser', '$newpass', '$role', 'ACTIVE');";
                $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: " . mysqli_error(), E_USER_ERROR);

                if ($role == 'STUDENT') {
                    echo "<script>window.location.href = 'studentprofile.php';</script>";
                } else {
                    echo "<script>window.location.href = 'teacherprofile.php';</script>";
                }
            } else {
                echo "<script>
                    alert('Password do not match or some fields are blank!');
                    window.location.href = 'index.php';
                </script>";
            }
        }
    }
?>
<html>

<head>
    <title>Essay Checker</title>
    <link rel="stylesheet" type="text/css" href="css/maindesign.css" />
</head>

<body background="bg.jpg">
    <div id="container">
        <div id="header">
            <form method="POST" action="#">
                <label style="top: 5px; left: 15px; font-size: 30px; position: absolute;">Vanguard Essay Checker</label>

                <div id="login">
                    <img src="img/user.png" id="iconimage" />
                    <input type="text" name="userid" placeholder="Username" maxlength="50" />
                    <img src="img/padlock.png" id="iconimage" style="left: 215px;" />
                    <input type="password" name="pass" maxlength="50" placeholder="Password" />
                    <input type="submit" value="Login" name="login" class="success" />
                </div>
            </form>
        </div>

        <fieldset id="infodiv">
            <label style="font-size: 30px;">Welcome to Vanguard <br />Essay Checker</label>
            <br /><br />
            <label>the best place to connect teacher and student thoughts</label>
            <br />
        </fieldset>

        <fieldset id="register">
            <form method="POST" action="#">
                <label>Easy and Fast Registration</label>
                <br /><br />

                <label>Username</label>
                <br />
                <input type="text" name="newuser" maxlength="50" placeholder="Username" style="width: 250px; padding: 5px;" />
                <br /><br />

                <label>Password</label>
                <br />
                <input type="password" name="newpass" maxlength="50" placeholder="Password" style="width: 250px; padding: 5px;" />
                <br /><br />

                <label>Confirm Password</label>
                <br />
                <input type="password" name="confirmpass" maxlength="50" placeholder="Confirm Password" style="width: 250px; padding: 5px;" />
                <br /><br />

                <input type="radio" name="role" value="STUDENT" checked />I'm a Student
                <input type="radio" name="role" value="TEACHER" />I'm a Teacher
                <br /><br />

                <input type="submit" value="Register" name="register" class="reg" />
            </form>
        </fieldset>
    </div>
</body>
</html>
