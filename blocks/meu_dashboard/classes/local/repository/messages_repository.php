<?php
declare(strict_types=1);

namespace block_meu_dashboard\local\repository;

use dml_exception;
use moodle_database;

/**
 * Repositório de dados de mensagens.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class messages_repository implements messages_repository_interface
{
    /**
     * @var moodle_database
     */
    private moodle_database $db;

    /**
     * @param moodle_database $db
     * @author David <github.com/DavidMarquesDev>
     */
    public function __construct(moodle_database $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     * @throws dml_exception
     */
    public function get_recent_messages(int $userid, int $limit): array
    {
        if (!$this->db->get_manager()->table_exists('local_hello_messages')) {
            return [];
        }

        $records = $this->db->get_records(
            'local_hello_messages',
            ['userid' => $userid],
            'timecreated DESC',
            'id, userid, message, timecreated',
            0,
            $limit
        );

        return array_map(
            static fn(object $record): array => [
                'id' => (int)$record->id,
                'userid' => (int)$record->userid,
                'message' => (string)$record->message,
                'timecreated' => (int)$record->timecreated,
            ],
            array_values($records)
        );
    }

    /**
     * @inheritDoc
     * @throws dml_exception
     */
    public function get_messages_series(int $userid, int $perioddays): array
    {
        if (!$this->db->get_manager()->table_exists('local_hello_messages')) {
            return [];
        }

        $start = strtotime('-' . max(1, $perioddays) . ' days');
        $records = $this->db->get_records_select(
            'local_hello_messages',
            'userid = :userid AND timecreated >= :starttime',
            ['userid' => $userid, 'starttime' => $start],
            'timecreated ASC',
            'timecreated'
        );

        $grouped = [];
        foreach ($records as $record) {
            $day = userdate((int)$record->timecreated, '%Y-%m-%d', 99, false);
            $grouped[$day] = ($grouped[$day] ?? 0) + 1;
        }

        $points = [];
        foreach ($grouped as $day => $count) {
            $points[] = [
                'day' => (string)$day,
                'count' => (int)$count,
            ];
        }

        return $points;
    }

    /**
     * @inheritDoc
     * @throws dml_exception
     */
    public function count_messages(int $userid): int
    {
        if (!$this->db->get_manager()->table_exists('local_hello_messages')) {
            return 0;
        }

        return (int)$this->db->count_records('local_hello_messages', ['userid' => $userid]);
    }
}
