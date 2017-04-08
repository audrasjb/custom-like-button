<?php
function hasheAlreadyVoted($post_id) {
	global $wpdb;
	$mydbname = $wpdb->prefix . 'ltp_datas';
	
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	
	$hashealreadyvoted = $wpdb->get_var("SELECT value FROM " . $mydbname . " WHERE post_id = '$post_id' AND user_id = '$user_id'");

	return $hashealreadyvoted;
}

function ltpAjaxCallback() {
	global $wpdb;
	$mydbname = $wpdb->prefix . 'ltp_datas';
	
	$post_id = (int)$_REQUEST['post_id'];
	$task = $_REQUEST['task'];
	$hashealreadyvoted = hasheAlreadyVoted($post_id);
	$loginplease = get_option('ltp_login_message');
	$thanksforlike = get_option('ltp_thanks_message');
	$alreadylikes = get_option('ltp_already_liked_message');
	$unlikeprevious = get_option('ltp_unlike_previous');
	$unlikemessage = get_option('ltp_unlike_message');
	
	if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ltp_vote_nonce' ) ) {
		$error = 1;
		$msg = 'Invalid Access';
	} else {
		if(is_user_logged_in()){
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
			
			if ($task == 'like') {
				if ($hashealreadyvoted == 0) {
					$query = "INSERT INTO " . $mydbname . " (`post_id`, `value`, `user_id`, `date_time`) VALUES ('" . $post_id . "', '1', '" . $user_id . "', '" . date( 'Y-m-d H:i:s' ) . "');";
					$sqlchangesee = 1;
				} else {
					if($unlikeprevious){
						$query = "DELETE FROM " . $mydbname . " WHERE post_id = '$post_id' AND user_id = '$user_id'";
						$sqlchangesee = 1;
						$temp = true;
					} else {
						$error =1;
						$msg = $alreadylikes;
					}
				}
			}
			
			if($sqlchangesee == 1) {
				$success = $wpdb->query($query);
				if ($success) {
					$error = 0;
					if($temp){
						$msg = $unlikemessage;
					} else {
						$msg = $thanksforlike;
					}
				}
				else {
					$error = 1;
					$msg = 'Could not process your vote';
				}
			}
		} else {
			$error = 0;
			$msg = $loginplease;
		}
	}
	
	$ltp_alllikes = ltp_likecount($post_id);
	$ltp_like_users = GetAllUserslikethePost($post_id);
	$ltp_user_like_data = showLikedUsers($ltp_like_users);

	if ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
		$result = array(
					"msg" => $msg,
					"like" => $ltp_alllikes,
					"alllikeusers" => $ltp_user_like_data,
				);
		
		echo json_encode($result);
	} else {
		header( "location:" . $_SERVER["HTTP_REFERER"] );
	}
	exit;
}

add_action( 'wp_ajax_ltp_ajax_process', 'ltpAjaxCallback' );
add_action( 'wp_ajax_nopriv_ltp_ajax_process', 'ltpAjaxCallback' );
?>