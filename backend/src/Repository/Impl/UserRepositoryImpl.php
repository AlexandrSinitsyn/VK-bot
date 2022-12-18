<?php

namespace Bot\Repository\Impl;

use Bot\Database\DatabaseHandlerException;
use Bot\Entity\User;
use Bot\Repository\UserRepository;

class UserRepositoryImpl implements UserRepository
{
    /**
     * @throws DatabaseHandlerException
     */
    public function getAllUsers(): array
    {
        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'SELECT * FROM Student', array())
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[] = new User($line['name'], (int) $line['id'], (bool) $line['isstudent']);
        }

        pg_free_result($result);

        pg_close($dbconn);

        return $res;
    }

    public function getUserById(int $id): ?User
    {
        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'SELECT * FROM Student WHERE Id=$1', array($id))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            var_dump($line);
            error_log(var_export($line, true));
            $res[] = new User($line['name'], (int) $line['id'], (bool) $line['isstudent']);
        }

        pg_free_result($result);

        pg_close($dbconn);

        return empty($res) ? null : $res[0];
    }

    /**
     * @throws DatabaseHandlerException
     */
    public function saveUser(User $user): bool
    {
        if ($this->getUserById($user->id) != null) {
            return false;
        }

        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'INSERT INTO Student VALUES ($1, $2, $3)', array($user->id, $user->name, $user->student ? '1' : '0'))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        pg_free_result($result);

        pg_close($dbconn);

        return true;
    }
}