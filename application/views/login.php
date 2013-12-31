<div class="center">
	<?php
	
	$this->load->helper("form");
	
	echo $message;
	
	echo validation_errors();
	
	echo form_open("site/login_send");
	
	echo form_label("Username: ", "username");
	$data = array(
		"name" => "username",
		"id" => "username",
		"value" => set_value("username")
	);
	
	
	echo form_input($data);
	
	echo '<br/>Password:';
	echo form_password('password');
	echo '<br/>';
	
	
	echo form_submit("login_submit", "Login");
	
	echo form_close();
	?>
</div>