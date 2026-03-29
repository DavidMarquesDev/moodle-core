<?php
declare(strict_types=1);

namespace block_meu_dashboard\local\repository;

/**
 * Contrato para consultas de mensagens usadas no dashboard.
 *
 * @author David <github.com/DavidMarquesDev>
 */
interface messages_repository_interface
{
    /**
     * Retorna mensagens recentes do usuário.
     *
     * @param int $userid
     * @param int $limit
     * @return array<int, array<string, int|string>>
     * @author David <github.com/DavidMarquesDev>
     */
    public function get_recent_messages(int $userid, int $limit): array;

    /**
     * Retorna série diária de mensagens por período.
     *
     * @param int $userid
     * @param int $perioddays
     * @return array<int, array<string, int|string>>
     * @author David <github.com/DavidMarquesDev>
     */
    public function get_messages_series(int $userid, int $perioddays): array;

    /**
     * Retorna total de mensagens para o usuário.
     *
     * @param int $userid
     * @return int
     * @author David <github.com/DavidMarquesDev>
     */
    public function count_messages(int $userid): int;
}
