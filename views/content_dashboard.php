<body>
	
	
	<p class="message"><a href="<?php echo base_url() ?>site/new_requirement">Click to add a new requirement</a></p>
	
	<br />
	
	<p class="message" style="color:red"> <?php echo $message?></p>
	<p class="done" style="color:blue"> <?php echo $done?></p>
	
	<?php
		$this->load->helper("form");
		?>
		<p class="message" style="color:red">
		<?php
		echo validation_errors();
		?>
		</p>
		<?php
		foreach($requirements->result() as $req){
			echo "<br/> <br/><div class='req_wrap'>
				<div class='req_tables'>
				<div class='requirement'>
				<table id='reqTable'>
				<tr>
				<td id='reqID'>$req->idRequirement</td>
				<td id='reqName'> $req->name";
			echo "</td>
				<td id='rating'>
				<div class='starReq'>
				<span>&#9734;<span>&#9734;<span>&#9734;</span></span></span>".
				"   Total rating: ";
			echo $req_points[$req->idRequirement];
			echo "</div>
				</td>
				</tr>
				</table>
				<table id='reqTable'>
				<td>";
			echo $req->description;
			echo "</td>
				</table>
				</div>
					
					
					<div class='req_comments'>
						<table>
							<table>
								<tr>";
			foreach($comments[$req->idRequirement]->result() as $comm){
			
				echo				"<td id='comm'>$comm->comment<div class='starComm'>
										<span>&#9734;<span>&#9734;<span>&#9734;</span></span></span>
									</div></td><td id='comm'>";
			};
				
				echo form_open("site/add_comment");
				
				echo "<div class='add_comm'><p>";
				$data = array(
					  'name'        => 'comment',
					  'id'          => 'comment',
					  'value'       => 'Add your comment here',
					  'rows'   		=> '3',
					  'cols'        => '50',
					  'style'       => 'width:50%',
				);
				echo form_textarea($data);
				echo "<input type='hidden' name='requirement' id='requirement' value='$req->idRequirement'/><br>";
				echo form_submit("comment_submit", "Add comment");
				
				echo form_close();
				echo "				<br></div></td></tr>
							</table>
						</table>
					</div>
				</div>
			</div><br/> <br/>";
		};
	?>
	
	
</body>