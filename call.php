<?php
session_start();
$conn = mysqli_connect("localhost","root","","student") or die("not connected");
$name = $_POST['data'];
$name1= $_SESSION['name'];

$sql = "update data set `call`='$name1' where name='$name'";
$run = mysqli_query($conn,$sql);

if($run)
{
   
}
else
{
    echo "something went wronge";
}



?>