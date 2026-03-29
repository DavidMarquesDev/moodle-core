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
 * Endpoint de mensagens recentes para o dashboard.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class get_recent_messages extends external_api
{
    /**
     * @return external_function_parameters
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute_parameters(): external_function_parameters
    {
        return new external_function_parameters([
            'userid' => new external_value(PARAM_INT, 'ID do usuário alvo', VALUE_DEFAULT, 0),
            'limit' => new external_value(PARAM_INT, 'Quantidade máxima de mensagens', VALUE_DEFAULT, 10),
        ]);
    }

    /**
     * @param int $userid
     * @param int $limit
     * @return array<int, array<string, int|string>>
     * @throws dml_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute(int $userid = 0, int $limit = 10): array
    {
        global $USER;

        ['userid' => $userid, 'limit' => $limit] = self::validate_parameters(self::execute_parameters(), [
            'userid' => $userid,
            'limit' => $limit,
        ]);

        require_login();
        $context = context_system::instance();
        self::validate_context($context);

        $targetuserid = $userid > 0 ? $userid : (int)$USER->id;
        if ($targetuserid !== (int)$USER->id) {
            require_capability('moodle/user:viewdetails', $context);
        }

        $service = dashboard_service_factory::create();
        return $service->get_recent_messages($targetuserid, $limit);
    }

    /**
     * @return external_multiple_structure
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute_returns(): external_multiple_structure
    {
        return new external_multiple_structure(
            new external_single_structure([
                'id' => new external_value(PARAM_INT, 'ID da mensagem'),
                'userid' => new external_value(PARAM_INT, 'ID do usuário'),
                'message' => new external_value(PARAM_TEXT, 'Conteúdo da mensagem'),
                'timecreated' => new external_value(PARAM_INT, 'Timestamp de criação'),
            ])
        );
    }
}
