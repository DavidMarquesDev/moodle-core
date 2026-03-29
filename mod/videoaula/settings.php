<?php
declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(
        new admin_setting_configtext(
            'videoaula/zoomapikey',
            get_string('zoomapikey', 'videoaula'),
            get_string('zoomapikey_desc', 'videoaula'),
            '',
            PARAM_RAW_TRIMMED
        )
    );
    $settings->add(
        new admin_setting_configtext(
            'videoaula/zoomapiendpoint',
            get_string('zoomapiendpoint', 'videoaula'),
            get_string('zoomapiendpoint_desc', 'videoaula'),
            'https://api.zoom.us/v2',
            PARAM_URL
        )
    );
}
