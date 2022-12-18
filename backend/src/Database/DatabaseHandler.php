<?php

namespace Bot\Database;

use Bot\Entity\HomeworkSolution;
use Bot\Entity\User;
use Bot\Entity\Homework;
use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class DatabaseHandler
{
    /**
     * @throws DatabaseHandlerException
     */
    private static function accessDb(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $query,
                                     array $params = array()): array
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

        return $res;
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
        return DbParser::parseUsers(static::accessDb('SELECT * FROM Student'));
    }

    /**
     * @throws Exception
     */
    public static function getAllHws(): array
    {
        return DbParser::parseHomeworks(static::accessDb('SELECT * FROM Homework'));
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

        static::accessDb('INSERT INTO Student VALUES ($1, $2, $3)', array($user->id, $user->name, $user->student ? '1' : '0'));

        return true;
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

        static::accessDb('INSERT INTO Homework VALUES ($1, $2)', array($hw->number, $hw->deadline->format('Y-m-d')));

        return true;
    }

    public static function checkHw(int $number, int $studentId, int $mark): bool
    {
        $hw = static::getHw($number);
        $hw->results[] = $mark;
        return self::saveHw($hw);
    }

    public static function getAllSolutions(): array
    {
        return DbParser::parseHomeworkSolutions(static::accessDb('SELECT * FROM Solution'));
    }

    public static function saveSolution(HomeworkSolution $solution): bool
    {
//        return file_put_contents(HOMEWORK_SOLUTIONS_FILE, $solution->homeworkId . ' ' . $solution->userId . ' ' . $solution->text . PHP_EOL, FILE_APPEND | LOCK_EX);
        static::accessDb('INSERT INTO Solution VALUES ($1, $2, $3)', array($solution->homeworkId, $solution->userId, $solution->text));

        return true;
    }

    public static function getSolution(int $homeworkId, int $userId): ?HomeworkSolution
    {
        $found = array_filter(static::getAllSolutions(),
            fn(HomeworkSolution $s) => $s->homeworkId == $homeworkId && $s->userId == $userId);

        return empty($found) ? null : end($found);
    }
}
