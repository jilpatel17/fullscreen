<?php
session_start();
$conn = mysqli_connect("localhost","root","","student") or die("not connected");
$name1= $_SESSION['name'];

$sql = "select * from data where name='$name1'";
$run = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($run);

if($row['call'] == "")
{

}
else
{

    
    $output = "<h2>{$row['call']} calling you <span><button id='videocaller' data-id={$row['name']}>Accept</button></span></h2>";
    echo $output;
}


?>