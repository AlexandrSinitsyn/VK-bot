<?php
$users = array(1 => new User('alexsin', 1, true));
$hws = array(1 => new Homework(1, [], new DateTime()));

function get_user(int $user_id): User|false {
    return get_all_users()[$user_id];
}

function get_hw(int $number): Homework|false {
    return get_all_hws()[$number];
}

function get_all_users(): array {
    global $users;
    return $users;
}

function get_all_hws(): array {
    global  $hws;
    return $hws;
}

function save_user(User $user): bool {
    global $users;

//    if (key_exists($user->id, $users)) {
//        return false;
//    } else {
        $users[$user->id] = $user;
        return true;
//    }
}

function save_hw(Homework $hw): bool {
    global  $hws;
    $hws[$hw->number] = $hw;
    return true;
}
?>
