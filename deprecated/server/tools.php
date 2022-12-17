<?php
    require "database.php";

    function api_request(string $message, string $attachment): string {
        return $conf['apiurl'].'messages.send?user_id='.$conf['user_id']
            .'&group_id='.$conf['group_id']
            .'&attachment='.urlencode($attachment)
            .'&message='.urlencode($message)
            .'&v='.$conf['v'].'&access_token='.$conf['standalone'];
    }

	function process_message(User $user, string $message): string {
        $result = "";
        if ($user->student) {
            switch (true) {
                case preg_match('/^get hw(\d+) results$/', $message, $matches):
                    $result = get_hw($matches[0])->results;
                    break;
                case preg_match('/^get hw(\d+) deadline$/', $message, $matches):
                    $result = get_hw($matches[0])->deadline;
                    break;
                default:
                    error_log('Unknown user command. Must be one of: ["get hw\d+ results", "get hw\d+ deadline"]');
            }
        } else {
            switch (true) {
                case preg_match('/^add hw(\d+): ([0-9]{2}:[0-9]{2}:[0-9]{2})$/', $message, $matches):
                    $result = add_hw($matches[0], $matches[1]);
                    break;
                case preg_match('/^check hw(\d+): ([a-zA-Z]+)->([2-5])$/', $message, $matches):
                    $result = add_result($matches[0], $matches[1], $matches[2])->deadline;
                    break;
                default:
                    error_log('Unknown user command. Must be one of: ["get hw\d+ results", "get hw\d+ deadline"]');
            }
        }

        $log = $message . '<' . $conf['user_id'] . '>:' . $result.PHP_EOL;

        file_put_contents($conf['log'], $log, FILE_APPEND | LOCK_EX);

        return $result;
	}

    function respond_to_user(string $message): string {
		$myCurl = curl_init();
		curl_setopt_array($myCurl, array(
			CURLOPT_URL => api_request($message, null),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query(array())
		));
		$response = curl_exec($myCurl);
		curl_close($myCurl);

        return $response;
	}

    function is_user_logged($user_id): bool {
        return key_exists($user_id, get_all_users());
    }

    function register_user($data): void {
//        $is_student = $data->object->body ... ;

        if (save_user(new User($data->object->$first_name, $data->object->user_id))) {
            return;
        }

        error_log('Failed to save user to database');
    }

    function add_hw(int $number, string $deadline): bool {
        try {
            return save_hw(new Homework($number, [], new DateTime($deadline)));
        } catch (Exception $e) {
            error_log('Invalid deadline input type. Expected: hh:mm:ss.' . PHP_EOL . $e);
            return false;
        }
    }

    function add_result(int $number, string $who, int $score): Homework {
        $hw = get_hw($number);

        $user = null;
        foreach (get_all_users() as $id => $u) {
            if ($u->name == $who) {
                $user = $u;
                break;
            }
        }

        if ($user == null) {
            error_log("User [$who] not found");
        }

        $hw->results[$user->id] = $score;

        return $hw;
    }
