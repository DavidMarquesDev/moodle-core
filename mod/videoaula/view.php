<?php
declare(strict_types=1);

require('../../config.php');

$id = optional_param('id', 0, PARAM_INT);
$v = optional_param('v', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('videoaula', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
    $videoaula = $DB->get_record('videoaula', ['id' => $cm->instance], '*', MUST_EXIST);
} else {
    $videoaula = $DB->get_record('videoaula', ['id' => $v], '*', MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $videoaula->course], '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('videoaula', $videoaula->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/videoaula:view', $context);

$PAGE->set_url('/mod/videoaula/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($videoaula->name));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($videoaula->name));

if (trim((string)$videoaula->intro) !== '') {
    echo $OUTPUT->box(format_module_intro('videoaula', $videoaula, $cm->id), 'generalbox mod_introbox');
}

echo $OUTPUT->notification(get_string('integrationready', 'videoaula'), \core\output\notification::NOTIFY_SUCCESS);
echo $OUTPUT->single_button(
    new moodle_url('/mod/videoaula/view.php', ['id' => $cm->id, 'createmeeting' => 1, 'sesskey' => sesskey()]),
    get_string('createmeeting', 'videoaula')
);

if (optional_param('createmeeting', 0, PARAM_BOOL)) {
    require_sesskey();
    require_capability('mod/videoaula:manage', $context);
    $service = \mod_videoaula\local\service\meeting_service::create_default();
    $meeting = $service->create_course_meeting($videoaula, $USER);
    echo $OUTPUT->box_start('generalbox');
    echo html_writer::tag('p', get_string('meetingidlabel', 'videoaula', $meeting['id']));
    echo html_writer::link($meeting['joinurl'], get_string('joinmeeting', 'videoaula'));
    echo $OUTPUT->box_end();
}

echo $OUTPUT->footer();
