<?php

namespace Bot\Repository\Impl;

use Bot\Database\DatabaseHandlerException;
use Bot\Entity\HomeworkSolution;
use Bot\Repository\HomeworkSolutionRepository;

class HomeworkSolutionRepositoryImpl implements HomeworkSolutionRepository
{
    /**
     * @throws DatabaseHandlerException
     */
    public function getAllHomeworkSolutions(): array
    {
        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'SELECT * FROM Solution', array())
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[] = new HomeworkSolution((int) $line['hwid'], (int) $line['studentid'], $line['text']);
        }

        pg_free_result($result);

        pg_close($dbconn);

        return $res;
    }

    public function getHomeworkSolutionById(int $hwId, int $userId): ?HomeworkSolution
    {
        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'SELECT * FROM Solution WHERE HwId=$1 AND StudentId=$2', array($hwId, $userId))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        $res = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $res[] = new HomeworkSolution((int) $line['hwid'], (int) $line['studentid'], $line['text']);
        }

        pg_free_result($result);

        pg_close($dbconn);

        return empty($res) ? null : $res[0];
    }

    /**
     * @throws DatabaseHandlerException
     */
    public function saveHomeworkSolution(HomeworkSolution $solution): bool
    {
        if ($this->getHomeworkSolutionById($solution->homeworkId, $solution->userId) != null) {
            return false;
        }

        $dbconn = pg_connect("host=172.17.0.1 port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD)
            or throw new DatabaseHandlerException('Failed to connect: ' . pg_last_error());

        $result = pg_query_params($dbconn, 'INSERT INTO Solution VALUES ($1, $2, $3)', array($solution->homeworkId, $solution->userId, $solution->text))
            or throw new DatabaseHandlerException('Query failed: ' . pg_last_error());

        pg_free_result($result);

        pg_close($dbconn);

        return true;
    }
}