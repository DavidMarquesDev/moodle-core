<?php
declare(strict_types=1);

namespace block_meu_dashboard\local\service;

use block_meu_dashboard\local\repository\messages_repository_interface;
use dml_exception;
use moodle_database;

/**
 * Serviço de regras de negócio do dashboard.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class dashboard_service
{
    /**
     * @var moodle_database
     */
    private moodle_database $db;

    /**
     * @var messages_repository_interface
     */
    private messages_repository_interface $messagesrepository;

    /**
     * @param moodle_database $db
     * @param messages_repository_interface $messagesrepository
     * @author David <github.com/DavidMarquesDev>
     */
    public function __construct(moodle_database $db, messages_repository_interface $messagesrepository)
    {
        $this->db = $db;
        $this->messagesrepository = $messagesrepository;
    }

    /**
     * Retorna métricas principais do dashboard.
     *
     * @param int $userid
     * @return array<string, int|float>
     * @throws dml_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public function get_metrics(int $userid): array
    {
        $sqlcourses = "SELECT COUNT(DISTINCT c.id)
                         FROM {course} c
                         JOIN {enrol} e ON e.courseid = c.id
                         JOIN {user_enrolments} ue ON ue.enrolid = e.id
                        WHERE ue.userid = :userid
                          AND ue.status = 0
                          AND e.status = 0
                          AND c.id <> :siteid";
        $totalcourses = (int)$this->db->count_records_sql($sqlcourses, [
            'userid' => $userid,
            'siteid' => SITEID,
        ]);

        $completedcourses = (int)$this->db->count_records_select(
            'course_completions',
            'userid = :userid AND timecompleted IS NOT NULL',
            ['userid' => $userid]
        );

        $completionpercent = $totalcourses > 0 ? round(($completedcourses / $totalcourses) * 100, 2) : 0.0;
        $messagescount = $this->messagesrepository->count_messages($userid);

        return [
            'totalcourses' => $totalcourses,
            'completedcourses' => $completedcourses,
            'completionpercent' => $completionpercent,
            'messagescount' => $messagescount,
        ];
    }

    /**
     * @param int $userid
     * @param int $limit
     * @return array<int, array<string, int|string>>
     * @throws dml_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public function get_recent_messages(int $userid, int $limit = 10): array
    {
        return $this->messagesrepository->get_recent_messages($userid, max(1, $limit));
    }

    /**
     * @param int $userid
     * @param int $perioddays
     * @return array<int, array<string, int|string>>
     * @throws dml_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public function get_messages_series(int $userid, int $perioddays = 30): array
    {
        return $this->messagesrepository->get_messages_series($userid, max(1, $perioddays));
    }
}
