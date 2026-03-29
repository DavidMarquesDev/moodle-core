<?php
declare(strict_types=1);

namespace mod_videoaula\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;
use mod_videoaula\local\service\meeting_service;

/**
 * @author David <github.com/DavidMarquesDev>
 */
class create_meeting extends external_api
{
    /**
     * @return external_function_parameters
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute_parameters(): external_function_parameters
    {
        return new external_function_parameters([
            'cmid' => new external_value(PARAM_INT, 'ID do course module'),
        ]);
    }

    /**
     * @param int $cmid
     * @return array<string, mixed>
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute(int $cmid): array
    {
        global $DB, $USER;

        self::validate_parameters(self::execute_parameters(), ['cmid' => $cmid]);

        $cm = get_coursemodule_from_id('videoaula', $cmid, 0, false, MUST_EXIST);
        $modulecontext = \context_module::instance($cm->id);
        $context = \context::instance_by_id($modulecontext->id);
        self::validate_context($context);
        require_capability('mod/videoaula:manage', $context);

        $videoaula = $DB->get_record('videoaula', ['id' => $cm->instance], '*', MUST_EXIST);
        $service = meeting_service::create_default();
        return $service->create_course_meeting($videoaula, $USER);
    }

    /**
     * @return external_single_structure
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute_returns(): external_single_structure
    {
        return new external_single_structure([
            'id' => new external_value(PARAM_TEXT, 'ID da reunião'),
            'joinurl' => new external_value(PARAM_URL, 'URL de entrada'),
            'hosturl' => new external_value(PARAM_URL, 'URL do anfitrião'),
            'requestedby' => new external_value(PARAM_INT, 'ID do usuário solicitante'),
        ]);
    }
}
