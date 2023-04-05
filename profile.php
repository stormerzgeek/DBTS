<?php include ( "db.php" ); ?>
<?php 

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header("location: login.php");
}
else {
	$user = $_SESSION['user_login'];
	$result = mysqli_query($con,"SELECT * FROM user WHERE id='$user'");
		$get_user_email = mysqli_fetch_assoc($result);
			$uname_db = $get_user_email['firstName'];
			$uemail_db = $get_user_email['email'];
			$upass = $get_user_email['password'];
			$umob_db = $get_user_email['mobile'];
			$uadd_db = $get_user_email['Wallet_address'];
}

if (isset($_REQUEST['uid'])) {
	$user2 = mysqli_real_escape_string($con,$_REQUEST['uid']);
	if($user != $user2){
		header('location: index.php');
	}
}else {
	header('location: index.php');
}

if (isset($_POST['changesettings'])) {
$email = $_POST['email'];
$opass = $_POST['opass'];
$npass = $_POST['npass'];
$npass1 = $_POST['npass1'];
	try {
		if(empty($_POST['email'])) {
			throw new Exception('Email can not be empty');
		}
			if(isset($opass) && isset($npass) && isset($npass1) && ($opass != "" && $npass != "" && $npass1 != "")){
				if( md5($opass) == $upass){
					if($npass == $npass1){
						$npass = md5($npass);
						mysqli_query($con,"UPDATE user SET password='$npass' WHERE id='$user'");
						$success_message = '
						<div class="signupform_text" style="font-size: 18px; text-align: center;">
							Password changed.
						</div>';
					}else {
					$success_message = '
						<div class="signupform_text" style=" color: red; font-size: 18px; text-align: center;">
							New Password is not matching.
						</div>';
					}
				}else {
				$success_message = '
					<div class="signupform_text" style=" color: red; font-size: 18px; text-align: center;">
						Old Password is wrong.
					</div>';
				}
			}else {
				$success_message = '
					<div class="signupform_text" style=" color: red; font-size: 18px; text-align: center;">
						Fill the fields properly.
					</div>';
				}

			if($uemail_db != $email) {
				if(mysqli_query($con,"UPDATE user SET  email='$email' WHERE id='$user'")){
					$success_message = '
					<div class="signupform_text" style="font-size: 18px; text-align: center;">
						Settings change successfull.
					</div>';
				}
			}

	}
	catch(Exception $e) {
		$error_message = $e->getMessage();
	}
}


?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/x-icon" href="images/icon.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php
            $con = mysqli_connect("localhost","root","") or die("Error ".mysqli_error($con));
            mysqli_select_db($con,'pcbuilder') or die("cannot select DB"); ?>
    </head>
    <body>
    <?php include ( "mainheader.php" ); ?>
		<?php echo"<h1 class='titles' style='text-align:left'>Welcome User, $uname_db</h1><br><br>";?>
        <div>
		<div style="width: 901px; margin: 0px 50px;">
			<ul>
				<li style="float: left;list-style-type: none;">
					<div class="holecontainer">
						<form action="" method="POST" class="registration">
							<div class="container signupform_content ">
								<div style="text-align: left;font-size: 25px;font-family: Poppins;font-style: normal;font-weight: 600;color: #7C63F7;margin: 0 0 5px 0;">
									<td	>Change Password:</td>
								</div>
								<div>
									<td><input class="email signupbox" type="password" name="opass" placeholder="Old Password"></td>
								</div>
								<div>
									<td><input class="email signupbox" type="password" name="npass" placeholder="New Password"></td>
								</div>
								<div>
									<td><input class="email signupbox" type="password" name="npass1" placeholder="Repeat Password"></td>
								</div>
								<div style="text-align: left;font-size: 25px;font-family: Poppins;font-style: normal;font-weight: 600;color: #7C63F7;margin: 0 0 5px 0;">
									<td >Change Email:</td>
								</div>
								<div>
									<td><?php echo '<input class="email signupbox" required type="email" name="email" placeholder="New Email" value="'.$uemail_db.'">'; ?></td>
								</div>
								<div>
									<td><input class="uisignupbutton signupbutton" type="submit" name="changesettings" value="Update Settings"></td>
								</div>
								<div>
									<?php if (isset($success_message)) {echo $success_message;} ?>
								</div>
							</div>
						</form>
					</div>
				</li>
			</ul>
		</div>
	</div>

	
</body>
</html>