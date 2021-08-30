<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .meet-area{
            width: 100%;
            height: 500px;
            display: flex;
            justify-content: center;
        }
        .right{
            width: 50%;
            height: 500px;
            /* background-color: red; */
        }
        .left{
            width: 50%;
            height: 500px;
            /* background-color: purple; */
        }
    </style>
</head>
<body>

<h1>Welcome <?php echo $_SESSION['name']; ?></h1>
<div id="alert">

</div>
<?php

$conn = mysqli_connect("remotemysql.com","oEBLrW5Q8N","AOWaq9ylmk","oEBLrW5Q8N") or die("not connected");

$sql = "select * from data";
$run = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($run))
{
    echo "<span>{$row['name']}</span> <button class='call' data-id={$row['name']}>Call</button><br>";
}

?>

<div class="meet-area">
        <!-- Remote Video Element-->
        <div class="left">
        <video id="remote-video"></video>
        </div>

        <!-- Local Video Element-->
        <div class="right">
        <video id="local-video"></video>
        </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js"></script>

<script>
    $(document).ready(function(){

    const PRE = "DELTA"
    const SUF = "MEET"
    var room_id;
    var getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    var local_stream;


        function calling(){
            $.ajax({
                url:'fetch.php',
                method:'GET',
                success:function(data){
                    document.getElementById('alert').innerHTML = data;
                    
                }
            });
        }

        calling();

        $(document).on("click","#videocaller",function(){
            console.log("Joining Room")
            var uid = $(this).data('id');
            let room = uid;
        
            room_id = PRE+room+SUF;
            
            let peer = new Peer()
            peer.on('open', (id)=>{
                console.log("Connected with Id: "+id)
                getUserMedia({video: true, audio: true}, (stream)=>{
                    local_stream = stream;
                    setLocalStream(local_stream)
                    notify("Joining peer")
                    let call = peer.call(room_id, stream)
                    call.on('stream', (stream)=>{
                        setRemoteStream(stream);
                    })
                }, (err)=>{
                    console.log(err)
                });

            });

                    function setLocalStream(stream){
                        
                        let video = document.getElementById("local-video");
                        video.srcObject = stream;
                        video.muted = true;
                        video.play();
                    }
                    function setRemoteStream(stream){
                    
                        let video = document.getElementById("remote-video");
                        video.srcObject = stream;
                        video.play();
                    }
                    function notify(msg){
                    let notification = document.getElementById("notification")
                    notification.innerHTML = msg
                    notification.hidden = false
                    setTimeout(()=>{
                        notification.hidden = true;
                    }, 3000);
                    }
        });

        $(document).on("click",".call",function(){
            var data = $(this).data('id');
            $.ajax({
                url:'call.php',
                method:'POST',
                data:{data:data},
                success:function(res){
                    console.log("Creating Room")
                    let room = data;
                    
                    room_id = PRE+room+SUF;
                    let peer = new Peer(room_id)
                    peer.on('open', (id)=>{
                        console.log("Peer Connected with ID: ", id)
                        
                        getUserMedia({video: true, audio: true}, (stream)=>{
                            local_stream = stream;
                            setLocalStream(local_stream)
                        },(err)=>{
                            console.log(err)
                        })
                        notify("Waiting for peer to join.");
                    })
                    peer.on('call',(call)=>{
                        call.answer(local_stream);
                        call.on('stream',(stream)=>{
                            setRemoteStream(stream)
                        });
                    });
                    
                    function setLocalStream(stream){
                        
                        let video = document.getElementById("local-video");
                        video.srcObject = stream;
                        video.muted = true;
                        video.play();
                    }
                    function setRemoteStream(stream){
                    
                        let video = document.getElementById("remote-video");
                        video.srcObject = stream;
                        video.play();
                    }
                    function notify(msg){
                    let notification = document.getElementById("notification")
                    notification.innerHTML = msg
                    notification.hidden = false
                    setTimeout(()=>{
                        notification.hidden = true;
                    }, 3000);
                    }
                }
            });
        });



    });
</script>
</body>
</html>
