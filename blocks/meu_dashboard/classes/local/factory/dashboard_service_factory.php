<?php
declare(strict_types=1);

namespace block_meu_dashboard\local\factory;

use block_meu_dashboard\local\repository\messages_repository;
use block_meu_dashboard\local\service\dashboard_service;

/**
 * Factory para criação do serviço de dashboard.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class dashboard_service_factory
{
    /**
     * @return dashboard_service
     * @author David <github.com/DavidMarquesDev>
     */
    public static function create(): dashboard_service
    {
        global $DB;

        return new dashboard_service($DB, new messages_repository($DB));
    }
}
