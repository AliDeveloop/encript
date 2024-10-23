<?php
$link=mysqli_connect('localhost','root','','uni-project');
if(mysqli_connect_errno()){
    exit('Error connecting to sql database'.mysqli_connect_error());
}
mysqli_query($link,"SET CHARACTER SET UTF8")
?> 