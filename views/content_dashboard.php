<body>
	
	
	<p class="message"><a href="<?php echo base_url() ?>site/new_requirement">Click to add a new requirement</a></p>
	
	<br />
	
	<p class="message" style="color:red"> <?php echo $message?></p>
	
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
				$this->load->helper("form");
				echo validation_errors();
				
				echo form_open("site/add_comment");
				
				echo "<p>Add comment: <p>";
				echo form_input('comment');
				echo "<input type='hidden' name='requirement' id='requirement' value='$req->idRequirement'/>";
				echo form_submit("comment_submit", "Add comment");
				
				echo form_close();
				echo "				</td></tr>
							</table>
						</table>
					</div>
				</div>
			</div><br/> <br/>";
		};
	?>
	
	
</body>