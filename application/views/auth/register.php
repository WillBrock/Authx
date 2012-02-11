<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="<?php echo site_url(); ?>js/main.js"></script>

<link href="<?php echo site_url(); ?>css/main.css" rel="stylesheet" type="text/css" />
</head>
<body id="login_body">

<div align="center">
	<?php echo $message; ?>
</div>

<!-- load header if needed -->

<!-- logo -->
<div id="logo" align="center">
	<img src="<?php echo site_url(); ?>images/logo.jpg" />
</div>

<div id="login_box">


<?php echo form_open(''); ?> 

	<?php if ( $use_username ) : ?>
		<p><?php echo form_label('Username'); ?></p>
		<?php echo form_input(array('name' => 'username', 'value' => set_value('username'))); ?>
	<?php endif; ?>
	
	<p><?php echo form_label('Email Address'); ?></p>
	<?php echo form_input(array('name' => 'email', 'value' => set_value('email'))); ?>

	<p><?php echo form_label('Password'); ?></p>
	<?php echo form_password('password'); ?> 
	
	
	
	<p><?php echo form_label('Confirm Password'); ?></p> 
	<?php echo form_password('confirm_password'); ?> 
	
	<?php if ( $registration_token ) : ?>
		<p><?php echo form_label('Registration Token'); ?></p>
		<?php echo form_input(array('name' => 'registration_token', 'value' => set_value('registration_token'))); ?>
	<?php endif; ?>
	
		<?php if ( $use_captcha ) : ?>
			
			<?php echo '<p>'.$captcha_html.'</p>'; ?>
			
			<p><?php echo form_label('Enter the code as it appears'); ?></p>
			<?php echo form_input('captcha'); ?>
		
		<?php endif; ?>
	
	<?php echo "<p>" . form_submit('submit_it', 'Register') . "</p>"; 

echo form_close(); 
?>

</div>


</body>
</html>