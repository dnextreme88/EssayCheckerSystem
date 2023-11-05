<!DOCTYPE html>
<?php
    include 'db.php';

    session_start();

    $user = $_SESSION['user'];
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

    if (isset($_POST['save'])) {
        $tempUser = $_POST['save'];
        $tempPass = $_POST['pass'][$tempUser];
        $tempLn = $_POST['ln'][$tempUser];
        $tempFn = $_POST['fn'][$tempUser];
        $tempStat = $_POST['stat'][$tempUser];

        $sqlinsert = "UPDATE user SET password = '$tempPass', lastname = '$tempLn', firstname = '$tempFn', status = '$tempStat' WHERE username = '$tempUser'";
        $result = $conn->query($sqlinsert) or trigger_error("Query Failed! SQL: $sqlinsert - Error: " .mysqli_error(), E_USER_ERROR);

        echo "<script>alert('User Info Updated!');</script>";
    }
?>
<html>

<head>
    <?php include 'headercontent.php'; ?>
</head>

<body>
    <?php include 'adminNavi.php'; ?>

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
            <br /><br />
            <label>Users List</label>
            <br />

            <form method="POST" action="#">
                <table style="margin-top: 0; width: 1000px; text-align: left;">
                    <thead>
                        <tr>
                            <th width="150" style="border: 1px solid gray; background: white; color: black; padding: 5px;">Username</th>
                            <th width="150" style="border: 1px solid gray; background: white; color: black; padding: 5px;">Password</th>
                            <th width="250" style="border: 1px solid gray; background: white; color: black; padding: 5px;">Last Name</th>
                            <th width="250" style="border: 1px solid gray; background: white; color: black; padding: 5px;">First Name</th>
                            <th width="50" style="border: 1px solid gray; background: white; color: black; padding: 5px;">Status</th>
                            <th width="50" style="border: 1px solid gray; background: white; color: black; padding: 5px;">Save</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            $sqluser = "SELECT * FROM user WHERE username != 'admin'";
                            $resultuser = $conn->query($sqluser) or trigger_error("Query Failed! SQL: $sqluser - Error: " .mysqli_error(), E_USER_ERROR);
                        ?>

                        <?php while ($rowuser = $resultuser->fetch_assoc()): ?>
                            <?php
                                $user1 = $rowuser['username'];
                                $ln1 = $rowuser['lastname'];
                                $fn1 = $rowuser['firstname'];
                                $pass1 = $rowuser['password'];
                                $status1 = $rowuser['status'];
                            ?>

                            <tr>
                                <td style="border: 1px solid gray; background: white; color: black; padding: 5px;"><?= $user1 ?></td>
                                <td style="border: 1px solid gray; background: white; color: black; padding: 5px;"><input type="password" name="pass[<?= $user1 ?>]" value="<?= $pass1 ?>" /></td>
                                <td style="border: 1px solid gray; background: white; color: black; padding: 5px;"><input type="text" name="ln[<?= $user1 ?>]" value="<?= $ln1 ?>" /></td>
                                <td style="border: 1px solid gray; background: white; color: black; padding: 5px;"><input type="text" name="fn[<?= $user1 ?>]" value="<?= $fn1 ?>" /></td>
                                <td style="border: 1px solid gray; background: white; color: black; padding: 5px;"><input type="text" name="stat[<?= $user1 ?>]" value="<?= $status1 ?>" /></td>
                                <td style="border: 1px solid gray; background: white; color: black; padding: 5px;"><input type="image" name="save" src="img/save.png" value="<?= $user1; ?>" style="height: 20px; width: 20px;"></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
        </fieldset>
    </div>
</body>
</html>
