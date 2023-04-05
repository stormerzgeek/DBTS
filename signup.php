<?php include ( "db.php" ); ?>
<?php
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
}
else {
	header("location: index.php");
}

$u_fname = "";
$u_lname = "";
$u_email = "";
$u_mobile = "";
$u_waladdress = "";
$u_pass = "";

if (isset($_POST['signup'])) {
//declere veriable
$u_fname = $_POST['first_name'];
$u_lname = $_POST['last_name'];
$u_email = $_POST['email'];
$u_mobile = $_POST['mobile'];
$u_waladdress = $_POST['signupaddress'];
$u_pass = $_POST['password'];
//triming name
$_POST['first_name'] = trim($_POST['first_name']);
$_POST['last_name'] = trim($_POST['last_name']);
	try {
		if(empty($_POST['first_name'])) {
			throw new Exception('Fullname can not be empty');
			
		}
		if (is_numeric($_POST['first_name'][0])) {
			throw new Exception('Please write your correct name!');

		}
		if(empty($_POST['last_name'])) {
			throw new Exception('Lastname can not be empty');
			
		}
		if (is_numeric($_POST['last_name'][0])) {
			throw new Exception('lastname first character must be a letter!');

		}
		if(empty($_POST['email'])) {
			throw new Exception('Email can not be empty');
			
		}
		if(empty($_POST['mobile'])) {
			throw new Exception('Mobile can not be empty');
			
		}
		if(empty($_POST['password'])) {
			throw new Exception('Password can not be empty');
			
		}
		if(empty($_POST['signupaddress'])) {
			throw new Exception('Wallet Address can not be empty');
			
		}

		
		$check = 0;
		$e_check = mysqli_query($con,"SELECT email FROM `user` WHERE email='$u_email'");
		$email_check = mysqli_num_rows($e_check);
		$w_check = mysqli_query($con,"SELECT Wallet_address FROM `user` WHERE Wallet_address='$u_waladdress'");
		$wallet_check = mysqli_num_rows($w_check);
		if (strlen($_POST['first_name']) >2 && strlen($_POST['first_name']) <16 ) {
			if ($check == 0 ) {
				if ($email_check == 0) {
					if ($wallet_check == 0 ) {
						if (strlen($_POST['password']) >1 ) {
							$d = date("Y-m-d");
							$_POST['first_name'] = ucwords($_POST['first_name']);
							$_POST['last_name'] = ucwords($_POST['last_name']);
							$_POST['last_name'] = ucwords($_POST['last_name']);
							$_POST['email'] = mb_convert_case($u_email, MB_CASE_LOWER, "UTF-8");
							$_POST['password'] = md5($_POST['password']);
							$insertquery = "INSERT INTO user (firstName,lastName,email,mobile,Wallet_address,password) VALUES ('$_POST[first_name]','$_POST[last_name]','$_POST[email]','$_POST[mobile]','$_POST[signupaddress]','$_POST[password]')";
							$result = mysqli_query($con,$insertquery);
							$success_message = '<div class="signupform_content"><h2><font face="bookman">Registration successful!</font></h2></div>';
						}else {
							throw new Exception('Make strong password!');
						}
					}else{
						throw new Exception('Wallet Already Registered');
					}
				}else{
					throw new Exception('Email already taken!');
				}
			}else {
				throw new Exception('Username already taken!');
			}
		}else {
			throw new Exception('Firstname must be 2-15 characters!');
		}
	}
	catch(Exception $e) {
		$error_message = $e->getMessage();
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
        <?php 
			if(isset($success_message)) {echo $success_message;}
			else {
				echo '
					<div class="holecontainer" style="float: right; margin-right: 36%; padding-top: 26px;">
						<div class="container">
							<div>
								<div>
									<div class="signupform_content">
										<h2>Sign Up Form</h2>
										<div class="signupform_text"></div>
										<div>
											<form action="" method="POST" class="registration">
												<div class="signup_form">
													<div>
														<td >
															<input name="first_name" id="first_name" placeholder="First Name" required="required" class="first_name signupbox" type="text" size="30" value="'.$u_fname.'" >
														</td>
													</div>
													<div>
														<td >
															<input name="last_name" id="last_name" placeholder="Last Name" required="required" class="last_name signupbox" type="text" size="30" value="'.$u_lname.'" >
														</td>
													</div>
													<div>
														<td>
															<input name="email" placeholder="Enter Your Email" required="required" class="email signupbox" type="email" size="30" value="'.$u_email.'">
														</td>										
                                                    </div>
													<div>
														<td>
															<input name="mobile" placeholder="Enter Your Mobile" required="required" class="email signupbox" type="text" size="30" value="'.$u_mobile.'">
														</td>
													</div>
													<div>
														<td>
															<input name="signupaddress" placeholder="Write Your Wallet Address" required="required" class="email signupbox" type="text" size="30" value="'.$u_waladdress.'">
														</td>
													</div>
													<div>
														<td>
															<input name="password" id="password-1" required="required"  placeholder="Enter New Password" class="password signupbox " type="password" size="30" value="'.$u_pass.'">
														</td>
													</div>
													<div>
														<input name="signup" class="uisignupbutton signupbutton" type="submit" value="Sign Me Up!">
													</div>
													<div class="signup_error_msg">';
															if (isset($error_message)) {echo $error_message;}
													echo'</div>
												</div>
											</form>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				';
			}

		 ?>
	</body>
</html>
