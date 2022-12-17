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
    private static function accessDb(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $query,
                                     array $params = array()): string
    {
        $user = getenv('POSTGRES_USER');
        $password = getenv('POSTGRES_PASSWORD');
        $db = getenv('POSTGRES_DB');

        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=$db user=$user password=$password")
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, $query, $params) or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[] = join(' ', $line);
        }

        pg_free_result($result);

        pg_close($dbconn);

        return join("\n", $res);
    }

    private static function getFromFile(string $path): array
    {
        $txt_file = file_get_contents($path);
        $rows = explode("\n", $txt_file);
        array_shift($rows);
        array_pop($rows);

        return $rows;
    }

    public static function getUser(int $user_id): ?User
    {
        if (!key_exists($user_id, static::getAllUsers())) {
            return null;
        }
        return static::getAllUsers()[$user_id];
    }

    public static function getHw(int $number): ?Homework
    {
        if (!key_exists($number, static::getAllHws())) {
            return null;
        }
        return static::getAllHws()[$number];
    }

    public static function getAllUsers(): array
    {
        $total = static::accessDb('SELECT * FROM Student');
        $rows = explode("\n", $total);

        return DbParser::parseUsers($rows);
    }

    /**
     * @throws Exception
     */
    public static function getAllHws(): array
    {
        $total = static::accessDb('SELECT * FROM Homework');
        $rows = explode("\n", $total);

        return DbParser::parseHomeworks($rows);
    }

    private static function saveToFile(string $path, string $data): bool
    {
        return file_put_contents($path, $data . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public static function saveUser(User $user): bool
    {
//        $users = static::get_all_users();
//
//        if (key_exists($user->id, $users)) {
//            return false;
//        } else {
//            return static::saveToFile(USERS_FILE, $user->id . ' ' . $user->name . ' ' . ($user->student ? '1' : '0'));
//        }
        return boolval(static::accessDb('INSERT INTO Student VALUES ($1, $2, $3)', array($user->id, $user->name, $user->student)));
    }

    public static function saveHw(Homework $hw): bool
    {
//        $hws = static::get_all_hws();
//
//        if (key_exists($hw->number, $hws)) {
//            return false;
//        } else {
//            return static::saveToFile(HOMEWORKS_FILE, $hw->number . ' ' . $hw->deadline->format('d/m/y') . ' ' . join(',', $hw->results));
//        }

        return boolval(static::accessDb('INSERT INTO Homework VALUES ($1, $2)', array($hw->number, $hw->deadline->format('Y-m-d'))));
    }
}
