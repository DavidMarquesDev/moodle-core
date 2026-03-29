<?php
declare(strict_types=1);

namespace mod_videoaula\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;

/**
 * @author David <github.com/DavidMarquesDev>
 */
class get_activity_data extends external_api
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
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute(int $cmid): array
    {
        global $DB;

        self::validate_parameters(self::execute_parameters(), ['cmid' => $cmid]);

        $cm = get_coursemodule_from_id('videoaula', $cmid, 0, false, MUST_EXIST);
        $context = \context_module::instance($cm->id);
        self::validate_context($context);
        require_capability('mod/videoaula:view', $context);

        $videoaula = $DB->get_record('videoaula', ['id' => $cm->instance], '*', MUST_EXIST);

        return [
            'id' => (int)$videoaula->id,
            'name' => format_string((string)$videoaula->name),
            'meetingtopic' => (string)$videoaula->meetingtopic,
            'meetingstart' => (int)$videoaula->meetingstart,
            'meetingduration' => (int)$videoaula->meetingduration,
            'meetingid' => (string)($videoaula->meetingid ?? ''),
            'joinurl' => (string)($videoaula->joinurl ?? ''),
        ];
    }

    /**
     * @return external_single_structure
     * @author David <github.com/DavidMarquesDev>
     */
    public static function execute_returns(): external_single_structure
    {
        return new external_single_structure([
            'id' => new external_value(PARAM_INT, 'ID da atividade'),
            'name' => new external_value(PARAM_TEXT, 'Nome da atividade'),
            'meetingtopic' => new external_value(PARAM_TEXT, 'Tema da reunião'),
            'meetingstart' => new external_value(PARAM_INT, 'Timestamp de início'),
            'meetingduration' => new external_value(PARAM_INT, 'Duração em minutos'),
            'meetingid' => new external_value(PARAM_TEXT, 'Identificador da reunião'),
            'joinurl' => new external_value(PARAM_RAW, 'Link de entrada'),
        ]);
    }
}
