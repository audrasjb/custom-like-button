var $ltp = jQuery.noConflict();

likeThisPost = {

	ajaxLike: function() {
		var that = this;
		$ltp(".likebutton").on("click", function(e){
			e.preventDefault();
			var task = $ltp(this).attr("data-task");
				post_id = $ltp(this).attr("data-post_id"),
				nonce = $ltp(this).attr("data-nonce");

			$ltp(".no-msg-" + post_id).html("").addClass("ajax-loader").show();
			$ltp(".likebutton, .likeusers").addClass('opac');

			$ltp.ajax({
				type : "post",
				dataType : "json",
				url : ltpajax.ajax_url,
				data : {action: "ltp_ajax_process", task : task, post_id : post_id, nonce: nonce},
				success: function(response) {
					$ltp(".likebutton, .likeusers").removeClass('opac');
					$ltp(".likeusers-" + post_id).html(response.alllikeusers);
					$ltp(".no-like-" + post_id).html(response.like);
					$ltp(".no-msg-" + post_id).stop().removeClass("ajax-loader").html(response.msg).fadeIn(800).delay(2000).fadeOut(800);
					that.normalSlickers();
				}
			});
		});
	},
	normalSlickers: function() {
		$ltp(".likeotherslink").hover(function() {
			$ltp(".alllistusers").toggle();
			$ltp(".arrow-up").toggle();
		});
		$ltp(".likeotherslink, .showlikemore").click(function() {
			$ltp("#light, #fade").show();
		});
		$ltp("#fade").click(function() {
			$ltp("#light, #fade").hide();
		});
		$ltp(document).keyup(function(e) {
			if (e.keyCode == 27 && $ltp("#light").css("display") == "block") {
				$ltp("#light, #fade").hide();
			}
		});
	},
	mobilepopupHeight: function() {
		$ltp(window).resize(function(){
			if($ltp(window).innerWidth()>=768){
				$ltp('.alllistusers').css({'top':'25px'});
			} else {
				var lightHeight = $ltp('.like-box').height();
				$ltp('.alllistusers').css({'top':lightHeight});
				$ltp('.arrow-up').css({'top':lightHeight-5});
			}
		});
	}
};

$ltp(document).ready(function(){
	likeThisPost.ajaxLike();
	likeThisPost.normalSlickers();
	likeThisPost.mobilepopupHeight();
	
	
});
