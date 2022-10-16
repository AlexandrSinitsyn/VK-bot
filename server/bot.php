<?php
	require "config.php";
	require "tools.php";
	
	$data = json_decode(file_get_contents('php://input'));
	$user_id = $data->object->user_id;
	$conf["user_id"] = $user_id;
	
	$message = $data->object->body;
	
	$user_info = json_decode(file_get_contents($conf['apiurl'].'users.get?user_id='.$conf["user_id"].'&v='.$conf['v'].'&access_token='.$conf['standalone']));
	$user_name = $user_info->$response[0]->$first_name;
	
	switch ($data->type) {
		case 'confirmation':
			echo $conf['contorm_token'];
			break;
		case 'message_new':
			$response = process_message($message);
			file_put_contents($conf['log'], $response);
			
			respond_to_user($response);
			
			echo 'ok';
			break;
	}
?>