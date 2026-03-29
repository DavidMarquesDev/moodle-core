<?php
declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

$functions = [
    'block_meu_dashboard_get_dashboard_data' => [
        'classname' => 'block_meu_dashboard\\external\\get_dashboard_data',
        'description' => 'Retorna métricas do dashboard do aluno.',
        'type' => 'read',
        'ajax' => true,
    ],
    'block_meu_dashboard_get_recent_messages' => [
        'classname' => 'block_meu_dashboard\\external\\get_recent_messages',
        'description' => 'Retorna mensagens recentes do plugin local_hello.',
        'type' => 'read',
        'ajax' => true,
    ],
    'block_meu_dashboard_get_messages_series' => [
        'classname' => 'block_meu_dashboard\\external\\get_messages_series',
        'description' => 'Retorna série diária de mensagens do plugin local_hello.',
        'type' => 'read',
        'ajax' => true,
    ],
];
