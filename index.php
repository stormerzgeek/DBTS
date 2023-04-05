<?php include ( "db.php" ); ?>
<?php 
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$user = "";
    $uname_db="";
    $wallet_gov="";
}
else {
	$user = $_SESSION['user_login'];
	$result = mysqli_query($con,"SELECT * FROM user WHERE id='$user'");
		$get_user_email = mysqli_fetch_assoc($result);
			$uname_db = $get_user_email['firstName'];
            $wallet_gov = $get_user_email['Wallet_address'];
}   
?>
<!DOCTYPE html>
<html>

<head>
    <title>Direct Benefit Transfer System</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/web3/1.2.7-rc.0/web3.min.js"></script>

    <?php
            $con = mysqli_connect("localhost","root","") or die("Error ".mysqli_error($con));
            mysqli_select_db($con,'blockchain') or die("cannot select DB"); ?>
</head>

<body>
    <?php include ( "mainheader.php" ); ?>
    <div class="names" style="text-align: center;">
        <p style="margin-top:200px;" class="titles">LOGIN TO USE THE FEATURES</p><br><br>
    </div>
</body>

</html>