<?php
declare(strict_types=1);

use core_completion\api as completion_api;

defined('MOODLE_INTERNAL') || die();

/**
 * @param string $feature
 * @return bool|string|null
 * @author David <github.com/DavidMarquesDev>
 */
function videoaula_supports(string $feature)
{
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_OTHER;
        default:
            return null;
    }
}

/**
 * @param stdClass $data
 * @param mod_videoaula_mod_form $mform
 * @return int
 * @throws dml_exception
 * @author David <github.com/DavidMarquesDev>
 */
function videoaula_add_instance(stdClass $data, mod_videoaula_mod_form $mform): int
{
    global $DB;

    $data->timecreated = time();
    $data->timemodified = time();
    $data->id = $DB->insert_record('videoaula', $data);

    $completiontimeexpected = !empty($data->completionexpected) ? (int)$data->completionexpected : null;
    completion_api::update_completion_date_event((int)$data->coursemodule, 'videoaula', (int)$data->id, $completiontimeexpected);

    return (int)$data->id;
}

/**
 * @param stdClass $data
 * @param mod_videoaula_mod_form $mform
 * @return bool
 * @throws dml_exception
 * @author David <github.com/DavidMarquesDev>
 */
function videoaula_update_instance(stdClass $data, mod_videoaula_mod_form $mform): bool
{
    global $DB;

    $data->id = $data->instance;
    $data->timemodified = time();
    $DB->update_record('videoaula', $data);

    $completiontimeexpected = !empty($data->completionexpected) ? (int)$data->completionexpected : null;
    completion_api::update_completion_date_event((int)$data->coursemodule, 'videoaula', (int)$data->id, $completiontimeexpected);

    return true;
}

/**
 * @param int $id
 * @return bool
 * @throws dml_exception
 * @author David <github.com/DavidMarquesDev>
 */
function videoaula_delete_instance(int $id): bool
{
    global $DB;

    $instance = $DB->get_record('videoaula', ['id' => $id]);
    if (!$instance) {
        return false;
    }

    $cm = get_coursemodule_from_instance('videoaula', $id, $instance->course, false, MUST_EXIST);
    completion_api::update_completion_date_event((int)$cm->id, 'videoaula', (int)$instance->id, null);

    $DB->delete_records('videoaula', ['id' => $id]);
    return true;
}
