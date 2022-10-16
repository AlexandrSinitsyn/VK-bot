<?php
	$user_id = '???';
	
	$app_id = 51450467;
	
	$standalone = "???"; // access token
	$group_token = 'e7a8b2c5a5edfe5c72e802d28035968c74552afbc11d38e1743c6fe3ec18550259d580befbb50fa220067';
	$conf = [
		'standalone' => $standalone,
		'group_token' => $group_token,
		'contorm_token' => '146da7c7',
		'user_id' => $user_id,
		'group_id' => '216521057',
		'apiurl' => 'https://api.vk.com/method/',
		'path' => substr($_SERVER['PHP_SELF'], 0, -2),
		'log' => 'log.txt',
		'random_id' => mt_rand(0000000000, 999999999999),
		'v' => '5.131'
	];

    class User {
        public string $name;
        public int $id;
        public bool $student;

        public function __construct(string $name, int $id, bool $student = true) {
            $this->name = $name;
            $this->id = $id;
            $this->student = $student;
        }
    }

    class Homework {
        public int $number;
        public array $results;
        public DateTime $deadline;

        public function __construct(int $number, array $results, DateTime $deadline) {
            $this->number = $number;
            $this->results = $results;
            $this->deadline = $deadline;
        }
    }
?>
