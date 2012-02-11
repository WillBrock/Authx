<?php 
/**
 * Variables available:
 * 
 * $id - users id
 * $token
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reset Password</title>
</head>
<body>

	<p>You have requested to reset your password. Click the link below to reset.</p>
	
	<p><?php echo site_url("auth/reset_password/$id/$token"); ?></p>
	
	<p>If you did not request to change your password then just ignore this message.</p>

</body>
</html>