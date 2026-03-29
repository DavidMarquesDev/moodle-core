<?php
declare(strict_types=1);

namespace block_meu_dashboard\external;

use block_meu_dashboard\local\factory\dashboard_service_factory;
use context_system;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;
use dml_exception;

/**
 * Endpoint de métricas do dashboard.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class get_dashboard_data extends external_api
{
    /**
     * @return external_function_parameters
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute_parameters(): external_function_parameters
    {
        return new external_function_parameters([
            'userid' => new external_value(PARAM_INT, 'ID do usuário alvo', VALUE_DEFAULT, 0),
        ]);
    }

    /**
     * @param int $userid
     * @return array<string, int|float>
     * @throws dml_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute(int $userid = 0): array
    {
        global $USER;

        ['userid' => $userid] = self::validate_parameters(self::execute_parameters(), ['userid' => $userid]);

        require_login();
        $context = context_system::instance();
        self::validate_context($context);

        $targetuserid = $userid > 0 ? $userid : (int)$USER->id;
        if ($targetuserid !== (int)$USER->id) {
            require_capability('moodle/user:viewdetails', $context);
        }

        $service = dashboard_service_factory::create();
        return $service->get_metrics($targetuserid);
    }

    /**
     * @return external_single_structure
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute_returns(): external_single_structure
    {
        return new external_single_structure([
            'totalcourses' => new external_value(PARAM_INT, 'Total de cursos ativos'),
            'completedcourses' => new external_value(PARAM_INT, 'Total de cursos concluídos'),
            'completionpercent' => new external_value(PARAM_FLOAT, 'Percentual de conclusão'),
            'messagescount' => new external_value(PARAM_INT, 'Total de mensagens no local_hello'),
        ]);
    }
}
