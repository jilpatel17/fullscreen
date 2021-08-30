<?php
session_start();
$conn = mysqli_connect("localhost","root","","student") or die("not connected");

if(isset($_POST['submit']))
{
    $name= $_POST['name'];
    $pass= $_POST['pass'];

    $sql = "select * from data where name='$name' and pass='$pass'";
    $run = mysqli_query($conn,$sql);
    $c = mysqli_num_rows($run);
    if($c>0)
    {
        $_SESSION['name'] = $name;
        header('location:home.php?name='.$name);
    }
    else{
        echo " invalid";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="Name :"><br>
        <input type="password" name="pass" placeholder="Password :"><br>
        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>