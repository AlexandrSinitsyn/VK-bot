<?php

namespace Bot\Database;

use Bot\Entity\User;
use Bot\Entity\Homework;
use DateTime;
use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

const USERS_FILE = './users.tmp';
const HOMEWORKS_FILE = './homeworks.tmp';

class DatabaseHandler
{
    /**
     * @throws DatabaseHandlerException
     */
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Result|false'], default: 'resource|false')]
    private static function accessDb(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $query): string
    {
        $user = getenv('POSTGRES_USER');
        $password = getenv('POSTGRES_PASSWORD');
        $db = getenv('POSTGRES_DB');

        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=$db user=$user password=$password")
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query($dbconn, $query) or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[] = join(' ', $line);
        }

        pg_free_result($result);

        pg_close($dbconn);

        return join("\n", $res);
    }

    private static function getFromFile(string $path): string
    {
        $txt_file = file_get_contents($path);
        return $txt_file;
    }

    static function getUser(int $user_id): ?User
    {
        if (!key_exists($user_id, static::getAllUsers())) {
            return null;
        }
        return static::getAllUsers()[$user_id];
    }

    static function getHw(int $number): ?Homework
    {
        if (!key_exists($number, static::getAllHws())) {
            return null;
        }
        return static::getAllHws()[$number];
    }

    static function getAllUsers(): array
    {
//        return array(1 => new User('alexsin', 1, true));
//        $total = static::getFromFile(USERS_FILE);
        $total = static::accessDb('SELECT * FROM Student');
        $rows = explode("\n", $total);
        array_shift($rows);
        array_pop($rows);

        $users = array();
        foreach($rows as $row => $data) {
            $row_data = explode(' ', $data);

            $id = (int) $row_data[0];
            $name = $row_data[1];
            $isStudent = (bool) $row_data[2];

            $users[$id] = new User($name, $id, $isStudent);
        }

        return $users;
    }

    /**
     * @throws Exception
     */
    static function getAllHws(): array
    {
//        return array(1 => new Homework(1, [], new DateTime()));
//        $total = static::getFromFile(HOMEWORKS_FILE);
        $total = static::accessDb('SELECT * FROM Homework');
        $rows = explode("\n", $total);
        array_shift($rows);
        array_pop($rows);

        $hws = array();
        foreach($rows as $row => $data) {
            $row_data = explode(' ', $data);

            $id = (int) $row_data[0];
            $deadline = new DateTime($row_data[1]);
            $results = explode(',', $row_data[2]);

            $hws[$id] = new Homework($id, $results, $deadline);
        }

        return $hws;
    }

    static function saveUser(User $user): bool
    {
//        $users = static::get_all_users();
//
//        if (key_exists($user->id, $users)) {
//            return false;
//        } else {
            return file_put_contents(USERS_FILE, $user->id . ' ' . $user->name . ' ' . ($user->student ? '1' : '0') . PHP_EOL, FILE_APPEND | LOCK_EX);
//        }
    }

    static function saveHw(Homework $hw): bool
    {
//        $hws = static::get_all_hws();
//
//        if (key_exists($hw->number, $hws)) {
//            return false;
//        } else {
            return file_put_contents(HOMEWORKS_FILE, $hw->number . ' ' . $hw->deadline->format('d/m/y') . ' ' . join(',', $hw->results) . PHP_EOL, FILE_APPEND | LOCK_EX);
//        }
    }
}
