<?php if ( $this->session->flashdata('auth')) echo $this->session->flashdata('auth'); ?>
<?php if ( ! empty($error) ) echo $error; ?>
<?php if ( ! empty($message) ) echo $message; ?>