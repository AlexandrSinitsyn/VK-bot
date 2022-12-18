<?php

namespace Bot\Repository\Impl;

use Bot\Database\DatabaseHandlerException;
use Bot\Repository\ResultRepository;

class ResultRepositoryImpl implements ResultRepository
{
    public function getAllResultByHomework(int $homeworkId): array
    {
        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'SELECT * FROM Result WHERE HwId=$1', array($homeworkId))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[(int) $line['studentid']] = (int) $line['mark'];
        }

        pg_free_result($result);

        pg_close($dbconn);

        return $res;
    }

    public function getAllResultByStudent(int $userId): array
    {
        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'SELECT * FROM Result WHERE StudentId=$1', array($userId))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[(int) $line['hwid']] = (int) $line['mark'];
        }

        pg_free_result($result);

        pg_close($dbconn);

        return $res;
    }

    public function getResultByHwAndStudent(int $homeworkId, int $userId): ?int
    {
        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'SELECT * FROM Result WHERE HwId=$1 AND StudentId=$2', array($homeworkId, $userId))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[] = (int) $line['mark'];
        }

        pg_free_result($result);

        pg_close($dbconn);

        return empty($res) ? null : $res[0];
    }

    public function saveResult(int $homeworkId, int $studentId, int $mark): bool
    {
        if ($this->getResultByHwAndStudent($homeworkId, $studentId) != null) {
            return false;
        }

        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'INSERT INTO Result VALUES ($1, $2, $3)', array($homeworkId, $studentId, $mark))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[(int) $line['studentid']] = (int) $line['mark'];
        }

        pg_free_result($result);

        pg_close($dbconn);

        return true;
    }
}