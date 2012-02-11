<html>
<head>

</head>
<body id="login_body">

<div align="center" id="hold_message">
	<?php //echo $message; ?>
</div>

<!-- load header if needed -->


<div id="login_box">


<!--<h2>Login</h2>-->


<?php echo form_open('auth/login'); ?>

	<p><?php echo form_label('Email'); ?></p>
	<?php echo form_input(array('name' => 'email', 'value' => set_value('email'))); ?>
 
 	<p><?php echo form_label('Password'); ?></p>
 	<?php echo form_password('password'); ?>
	
	<?php //if ( $use_captcha ) : ?>
		
		<?php //echo '<p>'.$captcha_html.'</p>'; ?>
		
		<?php //echo form_label('Enter the code as it appears'); ?>
		<?php //echo form_input('captcha'); ?>
	
	<?php //endif; ?>

	<p><?php echo form_submit('submit', 'Login'); ?></p>

<?php echo form_close(); ?> 

<br />

<p class="links"><?php echo anchor('auth/forgot_password', 'Forgot Password?'); ?></p>
<?php //if ($allow_registration OR $request_signup) : ?>
	<p class="links"><?php echo anchor('auth/register', 'Sign Up'); ?></p>
<?php //endif; ?>

</div>


<!-- load footer if needed -->


</body>
</html>