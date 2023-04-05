<?php
    $con = mysqli_connect("localhost","root","") or die("Error ".mysqli_error($con));
    mysqli_select_db($con,'blockchain') or die("cannot select DB"); 
?>