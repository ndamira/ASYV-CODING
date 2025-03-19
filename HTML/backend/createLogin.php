<?php 
include('conn.php');

if(isset($_POST['createAccount'])){
    $first_name=$_POST['fname'];
    $last_name=$_POST['lname'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $cpassword=$_POST['cpassword'];
    
    $query="INSERT INTO `users`(`first_name`,`last_name`,`email`,`role`,`password`) VALUES ('$first_name','$last_name','$email','student','$password')";

    mysqli_query($conn,$query);
}

if(isset($_POST['login'])){
    $email=$_POST['email'];
    $password=$_POST['password'];
    $query="SELECT *FROM users WHERE `email`='$email' and `password`='$password'";
    $result=mysqli_query($conn,$query);
    if($result){
        
    }
}

?>