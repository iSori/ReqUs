<div class="content">
	<div class="center">
	<?php
	
	echo "<pre>";
	foreach($users->result() as $row){
		echo $row->name ." has ";
		$username = $row->username;
		echo  $scores[$username] ." points.<br/><br/>";
	}
	echo "<pre>";
	
	?>
	</div>
</div>