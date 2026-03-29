<?php
declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

$functions = [
    'mod_videoaula_create_meeting' => [
        'classname' => 'mod_videoaula\\external\\create_meeting',
        'description' => 'Cria reunião ao vivo para uma atividade videoaula.',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'mod/videoaula:manage',
    ],
    'mod_videoaula_get_activity_data' => [
        'classname' => 'mod_videoaula\\external\\get_activity_data',
        'description' => 'Retorna metadados da atividade videoaula.',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => 'mod/videoaula:view',
    ],
];
