<?php
session_start();
$conn = mysqli_connect("remotemysql.com","oEBLrW5Q8N","AOWaq9ylmk","oEBLrW5Q8N") or die("not connected");
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
