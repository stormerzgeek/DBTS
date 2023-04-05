<?php
session_start();
session_destroy();
setcookie('user_login', '', 0, "/");
header("Location: index.php");
?>