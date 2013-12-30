<div id="signup">
	<?php
	
	$this->load->helper("form");
	
	echo $message;
	
	echo validation_errors();
	
	echo form_open("site/signup_send");
	
	echo form_label("Name: ", "fullName");
	$data = array(
		"name" => "fullName",
		"id" => "fullName",
		"value" => set_value("fullName")
	);
	
	echo form_input($data);
	
	echo '<br/>';
	
	echo form_label("Username: ", "username");
	$data = array(
		"name" => "username",
		"id" => "username",
		"value" => set_value("username")
	);
	
	echo form_input($data);
	
	echo '<br/>';
	
	echo form_label("Email: ", "email");
	$data = array(
		"name" => "email",
		"id" => "email",
		"value" => set_value("email")
	);
	
	echo form_input($data);
	
	echo '<br/>';
	
	echo form_label("Password: ", "password");
	$data = array(
		"name" => "password",
		"id" => "password",
	);
	
	echo form_password($data);

	echo '<br/>';
	
	echo form_label("Confirm Password: ", "cpassword");
	$data = array(
		"name" => "cpassword",
		"id" => "cpassword",
	);
	
	echo form_password($data);
	
	echo '<br/>';
	
	echo form_submit("signUpSubmit", "Submit");
	
	echo form_close();
	?>
</div>