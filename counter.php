<?php
	function ltp_likecount($post_id) {
		global $wpdb;
		$mydbname = $wpdb->prefix . 'ltp_datas';
		
		$ltp_alllikes = $wpdb->get_var("SELECT SUM(value) FROM " . $mydbname . " WHERE post_id = '$post_id' AND value >= 0");
		
		if(!$ltp_alllikes) {
			$ltp_alllikes = 0;
		}
		else {
			$ltp_alllikes = $ltp_alllikes;
		}
		return $ltp_alllikes;
	}

	function GetAllUserslikethePost($post_id) {
		global $wpdb;
		$mydbname = $wpdb->prefix . 'ltp_datas';
	
		$ltp_like_users = $wpdb->get_col("SELECT user_id FROM " . $mydbname . " WHERE post_id = '$post_id' ORDER BY `id` DESC ");
		return $ltp_like_users;
	}
	
	function showLikedUsers($ltp_like_users){

		$numItems = count($ltp_like_users);
		$totalminustwousers = $numItems - 3;
		$i_totalvalue = 0;

		if($numItems <=3) {
			foreach ($ltp_like_users as $ltp_each_like_users) {
				$like_users_ltp = get_userdata($ltp_each_like_users);
				$like_users_ltp_display_name = $like_users_ltp->display_name;
				$ltp_new_all_users .= $like_users_ltp_display_name;

				if(++$i_totalvalue === $numItems) {
					$ltp_new_all_users .= '<span class="this-con"> likes this.</span>';
				} else {
					$ltp_new_all_users .= ', ';
				}
			}
		} elseif ($numItems >3) {
			$ltp_new_all_users .= '<div class="showlittlemoreusers">';

			foreach ($ltp_like_users as $ltp_each_like_users) {
				$like_users_ltp = get_userdata($ltp_each_like_users);
				$like_users_ltp_display_name = $like_users_ltp->display_name;
				if( ($i_totalvalue == 0) || ($i_totalvalue == 1) || ($i_totalvalue == 2) ) {
					$ltp_new_all_users .= $like_users_ltp_display_name;
					if($i_totalvalue != 2) {
						$ltp_new_all_users .= ', ';
					}
				}
				$i_totalvalue++;
			}
			$ltp_new_all_users .= '<span class="this-and"> and </span><div class="likeotherslink">' . $totalminustwousers . '<span class="this-and"> others </span><span class="arrow-up"></span>';
			$i_totalvalue = 0;
			$ltp_new_all_users .= '<ul class="alllistusers">';
			foreach ($ltp_like_users as $ltp_each_like_users) {
				$like_users_ltp = get_userdata($ltp_each_like_users);
				$like_users_ltp_display_name = $like_users_ltp->display_name;
				if( ($i_totalvalue != 0) && ($i_totalvalue != 1) && ($i_totalvalue != 2) ) {
					if( $i_totalvalue <=7 ) {
						$ltp_new_all_users .= '<li class="singleuserslist">' . $like_users_ltp_display_name . '</li>';
					} else {
						if( $numItems !=8 ) {
							$ltp_new_all_users .= '<li class="singleuserslist">' . '<span class="this-and">and </span><span class="showlikemore">more</span>...' . '</li>';
						}
						break;
					}
				}
				$i_totalvalue++;
			}
			$ltp_new_all_users .= '</ul></div><span class="this-con"> likes this.</span></div><div id="light" class="white_content"><h2>People Who Like This</h2><ul>';

			$pop_totalvalue = 0;
			foreach ($ltp_like_users as $ltp_each_like_users) {
				if ($pop_totalvalue <=99) {
				$like_users_ltp = get_userdata($ltp_each_like_users);
				$like_users_ltp_display_name = $like_users_ltp->display_name;
				$ltp_new_all_users .= '<li class="popupuserslist">' . get_avatar( $like_users_ltp->ID ) . '<span class="popupusersnames">' .$like_users_ltp_display_name . '</span></li>';
				$pop_totalvalue++;
				} else {
					$ltp_new_all_users .= '<li class="popupuserslist">and more</li>';
					break;
				}
			}
			$ltp_new_all_users .= '</ul></div><div id="fade" class="black_overlay"></div>';
		}
		return $ltp_new_all_users;
	}
?>