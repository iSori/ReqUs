<div id="signup">
	<?php
	
	$this->load->helper("form");
	
	echo $message;
	
	echo validation_errors();
	
	echo form_open("site/add_requirement");
	
	echo form_label("ID: ", "reqid");
	$data = array(
		"name" => "reqid",
		"id" => "reqid",
		"value" => set_value("reqid")
	);
	
	echo form_input($data);
	
	echo '<br/>';
	
	echo form_label("Requirement Name: ", "reqname");
	$data = array(
		"name" => "reqname",
		"id" => "reqname",
		"value" => set_value("reqname")
	);
	
	echo form_input($data);
	
	echo '<br/>';
	
	echo form_label("Description: ", "reqdesc");
	$data = array(
		"name" => "reqdesc",
		"id" => "reqdesc",
		"value" => set_value("reqdesc")
	);
	
	echo form_input($data);
	
	echo form_submit("signUpSubmit", "Submit");
	
	echo form_close();
	?>
</div>