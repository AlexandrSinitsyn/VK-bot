<?php

namespace Bot\Repository\Impl;

use Bot\Exceptions\DatabaseHandlerException;
use Bot\Entity\Homework;
use Bot\Repository\Impl\AbstractRepositoryImpl;
use Bot\Repository\HomeworkRepository;
use DateTime;

class HomeworkRepositoryImpl implements HomeworkRepository
{
    /**
     * @throws DatabaseHandlerException
     */
    public function getAllHomeworks(): array
    {
        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " Homework=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'SELECT * FROM Homework', array())
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[] = new Homework($line['number'], array(), new DateTime($line['deadline']));
        }

        pg_free_result($result);

        pg_close($dbconn);

        return $res;
    }

    public function getHomeworkById(int $number): ?Homework
    {
        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'SELECT * FROM Homework WHERE Number=$1', array($number))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[] = new Homework($line['number'], array(), new DateTime($line['deadline']));
        }

        pg_free_result($result);

        pg_close($dbconn);

        return empty($res) ? null : $res[0];
    }

    /**
     * @throws DatabaseHandlerException
     */
    public function saveHomework(Homework $hw): bool
    {
        if ($this->getHomeworkById($hw->number) != null) {
            return false;
        }

        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'INSERT INTO Homework VALUES ($1, $2)', array($hw->number, $hw->deadline->format('Y-m-d')))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        pg_free_result($result);

        pg_close($dbconn);

        return true;
    }
}