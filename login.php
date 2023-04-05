<?php include ( "db.php" ); ?>
<?php session_start(); ?>
<?php
ob_start();
if (!isset($_SESSION['user_login'])) {
}
else {
	header("location: index.php");
}
$emails = "";
$passs = "";
if (isset($_POST['login'])) {
	if (isset($_POST['email']) && isset($_POST['password'])) {
		$user_login = mysqli_real_escape_string($con,$_POST['email']);
		$user_login = mb_convert_case($user_login, MB_CASE_LOWER, "UTF-8");	
		$password_login = mysqli_real_escape_string($con,$_POST['password']);		
		$num = 0;
		$password_login_md5 = md5($password_login);
		$result = mysqli_query($con,"SELECT * FROM user WHERE (email='$user_login') AND password='$password_login_md5'");
		$num = mysqli_num_rows($result);
		$govresult = mysqli_query($con,"SELECT * FROM user WHERE (email='$user_login') AND password='$password_login_md5' AND Wallet_address='0xc6C7b469701A027C905B20f5C31fa1D6F3068f42'");
		$govnum = mysqli_num_rows($govresult);
		$get_user_email = mysqli_fetch_assoc($result);
        if(isset($get_user_email['id'])){
		$get_user_uname_db = $get_user_email['id'];
		if ($num>0) {
			if($govnum>0){
				$_SESSION['user_login'] = $get_user_uname_db;
				setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
            	header('location: govindex.php');
				exit();
			}
			$_SESSION['user_login'] = $get_user_uname_db;
			setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
            header('location: farmindex.php');
			exit();
		}else {
			$emails = $user_login;
			$passs = $password_login;
			$error_message = '<br><br>
				<div class="maincontent_text" style="text-align: center; font-size: 18px;">
				<font face="bookman">Email or Password incorrect.<br>
				</font></div>';
		    }
        }else {
			$emails = $user_login;
			$passs = $password_login;
			$error_message = '<br><br>
				<div class="maincontent_text" style="text-align: center; font-size: 18px;">
				<font face="bookman">Email or Password incorrect.<br>
				</font></div>';
		    }
        	
		}
	}
?>

<!doctype html>
<html>
<head>
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/x-icon" href="images/icon.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
    <?php include ( "mainheader.php" ); ?>
		<div class="holecontainer" style="float: right; margin-right: 36%; padding-top: 110px;">
			<div class="container">
				<div>
					<div>
						<div class="signupform_content">
                            <h2>Login Form</h2>
							<div class="signupform_text"></div>
							    <div>
								<form action="" method="POST" class="registration">
									<div class="signup_form">
										<?php
											echo '
										<div>
											<td>
												<input name="email" placeholder="Enter Your Email" required="required" class="email signupbox" type="email" size="30" value="'.$emails.'">
											</td>
										</div>
										<div>
											<td>
												<input name="password" id="password-1" required="required"  placeholder="Enter Password" class="password signupbox " type="password" size="30" value="'.$passs.'">
											</td>
										</div>
										<div>
											<input name="login" class="uisignupbutton signupbutton" type="submit" value="Log In">
										</div>
										';
										  ?>
										<div class="signup_error_msg">
											<?php 
												if (isset($error_message)) {echo $error_message;}
											?>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
