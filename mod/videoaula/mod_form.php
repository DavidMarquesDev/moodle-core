<?php
declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * @author David <github.com/DavidMarquesDev>
 */
class mod_videoaula_mod_form extends moodleform_mod
{
    /**
     * @return void
     * @author David <github.com/DavidMarquesDev>
     */
    public function definition(): void
    {
        $mform = $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $this->standard_intro_elements();

        $mform->addElement('text', 'meetingtopic', get_string('meetingtopic', 'videoaula'), ['size' => '64']);
        $mform->setType('meetingtopic', PARAM_TEXT);
        $mform->addRule('meetingtopic', null, 'required', null, 'client');

        $mform->addElement('duration', 'meetingduration', get_string('meetingduration', 'videoaula'), ['optional' => false]);
        $mform->addRule('meetingduration', null, 'required', null, 'client');

        $mform->addElement('date_time_selector', 'meetingstart', get_string('meetingstart', 'videoaula'));
        $mform->addRule('meetingstart', null, 'required', null, 'client');

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
}
