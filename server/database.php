<?php
function get_user(int $user_id): User|false {
    return get_all_users()[$user_id];
}

function get_hw(int $number): Homework|false {
    return get_all_hws()[$number];
}

function get_all_users(): array {
    return array(1 => new User('alexsin', 1, true));
}

function get_all_hws(): array {
    return array(1 => new Homework(1, [], new DateTime()));
}

function save_user(User $user): bool {
    return true;
}

function save_hw(Homework $hw): bool {
    return true;
}
?>
