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

    if (isset($_POST['myClass'])) {
        $_SESSION['selectedclass'] = $_POST['myClass'];
        echo "<script>window.location.href = 'StudentClassroom.php';</script>";
    }

    if (isset($_POST['joinclass'])) {
        $newclass = mysqli_real_escape_string($conn, $_POST['newclass']);

        $sqljoin = "SELECT class_id, class_name, teacher FROM class WHERE class_id = '$newclass'";
        $resultjoin = $conn->query($sqljoin)  or trigger_error("Query Failed! SQL: $sqljoin - Error: " .mysqli_error(), E_USER_ERROR);
        if (mysqli_num_rows($resultjoin) > 0) {
            while ($rowjoin = $resultjoin->fetch_assoc()) {
                $sqlnew = "SELECT * FROM class_student where class_id='$newclass' and username='$user'";
                $resultnew = $conn->query($sqlnew)  or trigger_error("Query Failed! SQL: $sqlnew - Error: " .mysqli_error(), E_USER_ERROR);
                if (mysqli_num_rows($resultnew) == 0) {
                    $sqlinsert = "INSERT INTO class_student (class_id, username) VALUES ('$newclass', '$user');";
                    $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: " .mysqli_error(), E_USER_ERROR);

                    echo "<script>
                        alert('New Class Successfully Joined!');
                        window.location.href = 'StudentClass.php';
                    </script>";
                } else {
                    echo "<script>
                        alert('You are already a member of this class!');
                        window.location.href = 'StudentClass.php';
                    </script>";
                }
            }
        } else {
            echo "<script>
                alert('Cannot Find Class Code! Please ask your teacher!');
                window.location.href = 'StudentClass.php';
            </script>";
        }
    }

    if (isset($_POST['removeclass'])) {
        $removeclass = $_POST['removeclass'];

        $sql = "DELETE FROM class_student WHERE class_id = '$removeclass' AND username = '$user'";
        $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: " .mysqli_error(), E_USER_ERROR); {
            echo "<script>
                alert('Class Remove Successfully!');
                window.location.href = 'StudentClass.php';
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
            <img src="<?= 'data:image/jpeg;base64,' .base64_encode($userimage) ?>" alt="No image" class="profimage" id="blah" />
            <br /><br />
            <label style="font-size: 15px; font-family: Arial, Charcoal, sans-serif;">ID: <?= $user ?></label>
        </fieldset>

        <fieldset id="classlist">
            <form method="POST" action="#">
                <Br>
                <p><label>Class List</label>
                    <input type='text' name='newclass' placeholder="Enter Class Code" maxlength="25" style='margin-left:340px;' required />
                    <input type='submit' name='joinclass' value='Join' class='success' />
                </p><br><br>
            </form>

            <form method="POST" action="#">
                <table style='margin-top: 0;'>
                    <tbody>
                        <?php
                            $sql3 = "SELECT class.class_id AS 'class_id', class_name, teacher FROM class, class_student WHERE class.class_id = class_student.class_id AND username = '$user'";
                            $result3 = $conn->query($sql3) or trigger_error("Query Failed! SQL: $sql3 - Error: " .mysqli_error(), E_USER_ERROR);;
                            $num_rows = mysqli_num_rows($result3);

                            $arrCount = 0;
                        ?>
                        
                        <?php while ($rows = $result3->fetch_assoc()):  ?>
                            <tr>
                                <?php $arrCount++; ?>
                                <?php if ($arrCount <= $num_rows): ?>
                                    <td id="tdstyle">
                                        <input type="image" src="img/classroom.jpg" id="classroom" name="myClass" value="<?= $rows['class_id'] ?>" />
                                        <br />

                                        <label>Code:<?= $rows['class_id'] ?></label>
                                        <br />

                                        <label style="font-size: 12px;">Teacher: <?= $rows['teacher'] ?></label>
                                        <br />
                                        <textarea id="tbllbl" disabled><?= $rows['class_name'] ?></textarea>
                                        <br />
                                        
                                        <input type="image" onclick="return confirm('Are you sure to leave this class?')" src="img/remove.png" name="removeclass" value="<?= $rows['class_id'] ?>" style="height: 15px; width: 15px; margin-top: 50px;" /><label style="color: gray; font-size: 12px;">Remove</label>
                                    </td>
                                <?php $rows = $result3->fetch_assoc(); ?>
                                <?php else: ?>
                                    <?php break; ?>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
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
