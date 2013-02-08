<?php

$video_name = $_GET['video_name'];
$URL_MAP = array(
        'EVM' =>'http://www.youtube.com/embed/AeafohFbc7U',
        'Kanban_Project_Creation' => 'http://www.youtube.com/embed/mtwZJabG3AY'
);

$VIDEO_URL =  $URL_MAP[$video_name];
        
echo( "<iframe name='video_frame' id='video_frame' width='420' height='315' src='".$VIDEO_URL."' frameborder='0'></iframe>");
?>