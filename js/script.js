$(function() {
		$(".req_tables").mouseover(function() {
				$(".req_comments", this).slideDown();
		});
		
		$(".req_tables").mouseleave(function() {
			 $(".req_comments", this).slideUp();
		});
});