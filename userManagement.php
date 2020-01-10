<!DOCTYPE html>
<?php
include 'db.php';

include 'adminNavi.php';
session_start(); 
  
        $user = $_SESSION['user'];
        if( !isset($_SESSION['user']) ){
         echo "<script>
       
        window.location.href='index.php';
        </script>";
        }
     include 'recentReg.php';   
   $sqlstore = "SELECT * FROM user where username='$user'";
    $resultstore = $conn->query($sqlstore);
    $ln='';
    $fn='';
    $pass ='';
    $userimage='';
    while($rowstore = $resultstore->fetch_assoc()) {
     $userimage= $rowstore['image'];   
         $ln= $rowstore['lastname'];                     
         $fn= $rowstore['firstname'];
         $pass =$rowstore['password'];
    }      

 if(isset($_POST['uploadimage'])){
    
    
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    
    $sql= "update user set image='{$image}' where username='$user'";
    $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
     echo "<script>
       
        window.location.href='userManagement.php';
        </script>";
       
    
 
   
    
}
 if(isset($_POST['update'])){
    $last=mysqli_real_escape_string($conn,$_POST['ln']);
    $first=mysqli_real_escape_string($conn,$_POST['fn']);
    
    $sql= "update user set lastname='$last',firstname='$first' where username='$user'";
    $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
     echo "<script>
       alert('Name Updated Successfully!');
        window.location.href='userManagement.php';
        </script>";
 }
 
  if(isset($_POST['change'])){
    $op=mysqli_real_escape_string($conn,$_POST['op']);
    $np=mysqli_real_escape_string($conn,$_POST['np']);
    $cp=mysqli_real_escape_string($conn,$_POST['cp']);
    
    if($_POST['op'] == $pass and ($np == $cp) and ($np != '')){
    $sql= "update user set password='$np' where username='$user'";
    $result = $conn->query($sql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);
     echo "<script>
       alert('Password Updated Successfully!');
        window.location.href='userManagement.php';
        </script>";
    }
    else{
       echo "<script>
       alert('Incorrect Old Password or New and Confirm Password do not match!');
        window.location.href='userManagement.php';
        </script>";  
        
    }
 }

?>
<html  >
    <head>
        <title>Essay Checker</title>
        <link rel="stylesheet" type="text/css" href="css/maindesign.css">
         <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<meta charset=utf-8 />
       
 
    </head>
    <body>
        <div id="container" >
            <div id="header">
              
                <label style="top:5px;left:15px;font-size: 30px;position:absolute">Vanguard Essay Checker</label> 
               
                
            </div>
            
            <fieldset id="teachprofile">
                  <form method="post" action="#" enctype="multipart/form-data">
                      <img src='<?php  echo 'data:image/jpeg;base64,'.base64_encode( $userimage ) ?>' alt='no image' class='profimage' id="blah"/>
                      <input type="file" name="image" id="image"   onchange="readURL(this);" required/><br><br>
                        <input type ="submit" value="Upload Image" name="uploadimage" class="success" style="width: 235px;">
                            
                    
                </form>
               
            </fieldset>
            
          
            
            <fieldset id="inform">
                <form method="post" action="#">
                <br>
                <label>First Name : </label><br><br>
                <input type="text" name="fn" maxlength="50" value="<?php echo $fn; ?>" required/><br><br>
                
                <label>Last Name : </label><br><br>
                <input type="text" name="ln" maxlength="50" value="<?php echo $ln; ?>" required/><br><br>
                <input type="submit" value="Update Name" name="update" class="success" style="margin-top:12px;"/>
                </form>
            </fieldset>
              <fieldset id="note">
                <label>Welcome Administrator! </label><br>
                </fieldset>  
             <fieldset id="pass">
                 <form method="post" action="#">
                 <br>
                 <label>Update Password </label><br><br>
                <label>Old Password: </label>
                <input type="password" name="op" maxlength="50" style="width:300px;margin-left:31px;" required=""/><Br><br>
                <label>New Password: </label>
                <input type="password" name="np" maxlength="50" style="width:300px;margin-left:23px;" required=""/><Br><br>
                <label>Confirm Password: </label>
                <input type="password" name="cp" maxlength="50" style="width:300px;"/><Br><br>
                <input type="submit" value="Change Password" name="change" class="success" required=""/>
                </form>
                </fieldset> 
             
               
           <fieldset id="footer">     
            <label>Essay Checker (Virtual Classroom) ©2017</label>
                
               
                </fieldset> 
            </div>
       
    </body>
    
</html>
<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(235)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
 

