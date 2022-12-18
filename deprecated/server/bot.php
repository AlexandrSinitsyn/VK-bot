<?php
	require_once 'config.php';
	require_once 'tools.php';

	$data = json_decode(file_get_contents('php://input'));
	$user_id = $data->object->user_id;
	$conf['user_id'] = $user_id;

	$user_info = json_decode(file_get_contents(api_request(null, null)));
	$user_name = $user_info->$response[0]->$first_name;


    if (!is_user_logged($user_id)) {
        register_user($data);
    }

    $user = get_user($user_id);

    $message = $data->object->body;

	switch ($data->type) {
		case 'confirmation':
			echo $conf['contorm_token'];
			break;
		case 'message_new':
			$response = process_message($user, $message);

			respond_to_user($response);

			echo 'ok';
			break;
	}
?>