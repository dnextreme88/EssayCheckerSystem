<?php
    session_start();

    echo "Logout Successfully";

    session_destroy(); // Function that destroys session

    echo "<script>
        alert('Account Logged Out!');
        window.location.href = 'index.php';
    </script>";
?>