<div class="content">
	<div class="center">
	<?php
	
	echo "<pre>";
	echo 'Username: '. $user->username;
	echo '</br>Name: '. $user->name;
	echo '</br>Requirements created: '. $scores['requirements'];
	echo '</br>Comments posted: '. $scores['comments'];
	echo '</br>Evaluated requirements: '. $scores['eval_requirements'];
	echo '</br>Evaluated comments: '. $scores['eval_comments'];
	echo '</br>Total score: '. $scores['score'];
	echo "<pre>";
	
	?>
	</div>
</div>