<html>
<head>
<link href="<?php echo site_url(); ?>css/main.css" rel="stylesheet" type="text/css" />
</head>
<body id="login_body">

<div align="center">
	<?php echo validation_errors(); ?>
</div>

<!-- load header if needed -->

<!-- logo -->
<div id="logo" align="center">
	<img src="<?php echo site_url(); ?>images/logo.jpg" />
</div>

<div id="login_box">

<?php echo form_open(''); ?>

	<?php echo form_label('New Password:'); ?>
	<?php echo form_password('pass'); ?>

	<?php echo form_label('Confirm Password:'); ?>
	<?php echo form_password('confirm_pass'); ?>
	
	<?php echo form_submit('submit', 'Submit'); ?>	
	
<?php echo form_close(); ?>


</div>

</body>
</html>