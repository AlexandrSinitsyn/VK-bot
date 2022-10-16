<?php
	function process_message($message) {
		return "Got: " . $message;
	}
	
	function respond_to_user($message) {
		$myCurl = curl_init();
		curl_setopt_array($myCurl, array(
			CURLOPT_URL => $conf['apiurl'].'messages.send?user_id='.$conf["user_id"].'&group_id='.$conf['group_id'].'&attachment='
				.'&message='.urlencode($message)
				.'&v='.$conf['v'].'&access_token='.$conf['standalone'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query(array())
		));
		$response = curl_exec($myCurl);
		curl_close($myCurl);
	}
?>
