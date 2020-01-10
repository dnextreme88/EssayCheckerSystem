<?php
 session_start();

  echo "Logout Successfully ";
  session_destroy();   // function that Destroys Session 
   echo "<script>
                        alert('Account Logged Out!');
                        window.location.href='index.php';
                        </script>";
?>