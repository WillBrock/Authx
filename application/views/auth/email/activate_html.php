<?php 
/**
 * Variables available:
 * 
 * $activation_key
 * $email
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Verify Registration</title>
</head>
<body>

	<p>Thank you for signing up. Please click the link below to confirm your registration.</p>
	
	<p><?php echo site_url('auth/confirm_registration/'.$activation_key); ?></p>

</body>
</html>