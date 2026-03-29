<?php
declare(strict_types=1);

use core\output\html_writer;
use core\url;
use core_table\output\html_table;

require('../../config.php');

$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
require_course_login($course);

$PAGE->set_url('/mod/videoaula/index.php', ['id' => $course->id]);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('modulenameplural', 'videoaula'));
$PAGE->set_heading(format_string($course->fullname));

$instances = get_all_instances_in_course('videoaula', $course);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('modulenameplural', 'videoaula'));

if (!$instances) {
    echo $OUTPUT->notification(get_string('novideoaulas', 'videoaula'));
    echo $OUTPUT->footer();
    exit;
}

$table = new html_table();
$table->head = [
    get_string('name'),
    get_string('meetingtopic', 'videoaula'),
    get_string('meetingstart', 'videoaula'),
];

foreach ($instances as $instance) {
    $url = new url('/mod/videoaula/view.php', ['id' => $instance->coursemodule]);
    $table->data[] = [
        html_writer::link($url, format_string($instance->name)),
        format_string((string)$instance->meetingtopic),
        userdate((int)$instance->meetingstart),
    ];
}

echo html_writer::table($table);
echo $OUTPUT->footer();
