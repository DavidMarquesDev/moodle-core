<?php
declare(strict_types=1);

namespace block_meu_dashboard\external;

use block_meu_dashboard\local\factory\dashboard_service_factory;
use context_system;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;
use dml_exception;

/**
 * Endpoint de série temporal de mensagens.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class get_messages_series extends external_api
{
    /**
     * @return external_function_parameters
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute_parameters(): external_function_parameters
    {
        return new external_function_parameters([
            'userid' => new external_value(PARAM_INT, 'ID do usuário alvo', VALUE_DEFAULT, 0),
            'perioddays' => new external_value(PARAM_INT, 'Período em dias', VALUE_DEFAULT, 30),
        ]);
    }

    /**
     * @param int $userid
     * @param int $perioddays
     * @return array<int, array<string, int|string>>
     * @throws dml_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute(int $userid = 0, int $perioddays = 30): array
    {
        global $USER;

        ['userid' => $userid, 'perioddays' => $perioddays] = self::validate_parameters(self::execute_parameters(), [
            'userid' => $userid,
            'perioddays' => $perioddays,
        ]);

        require_login();
        $systemcontext = context_system::instance();
        $context = \context::instance_by_id($systemcontext->id);
        self::validate_context($context);

        $targetuserid = $userid > 0 ? $userid : (int)$USER->id;
        if ($targetuserid !== (int)$USER->id) {
            require_capability('moodle/user:viewdetails', $context);
        }

        $service = dashboard_service_factory::create();
        return $service->get_messages_series($targetuserid, $perioddays);
    }

    /**
     * @return external_multiple_structure
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute_returns(): external_multiple_structure
    {
        return new external_multiple_structure(
            new external_single_structure([
                'day' => new external_value(PARAM_TEXT, 'Dia no formato YYYY-MM-DD'),
                'count' => new external_value(PARAM_INT, 'Quantidade de mensagens'),
            ])
        );
    }
}
