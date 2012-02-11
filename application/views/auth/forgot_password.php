<html>
<head>
<link href="<?php echo site_url(); ?>css/main.css" rel="stylesheet" type="text/css" />
</head>
<body id="login_body">

<div align="center">
<?php if ( $this->session->flashdata('forgot_password')) echo $this->session->flashdata('forgot_password'); ?>
	<?php echo validation_errors(); ?>
	<?php if (!empty($message)) echo $message; ?>
	<?php if (!empty($error)) echo $error; ?>
</div>

<!-- load header if needed -->

<!-- logo -->
<div id="logo" align="center">
	<img src="<?php echo site_url(); ?>images/logo.jpg" />
</div>

<div id="login_box">


<?php echo form_open(''); ?>

<?php echo form_label('Enter your email address'); ?>
<?php echo form_input(array('name' => 'email', 'value' => set_value('email'))); ?>

<?php echo form_submit('submit', 'Submit'); ?>

<?php echo form_close(); ?>

</div>

</body>
</html>