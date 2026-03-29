<?php
declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/videoaula/backup/moodle2/restore_videoaula_stepslib.php');

/**
 * Define as etapas de restore da atividade videoaula.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class restore_videoaula_activity_task extends restore_activity_task
{
    /**
     * @return void
     * @author David <github.com/DavidMarquesDev>
     */
    protected function define_my_settings(): void
    {
    }

    /**
     * @return void
     * @author David <github.com/DavidMarquesDev>
     */
    protected function define_my_steps(): void
    {
        $stepclass = 'restore_videoaula_activity_structure_step';
        $this->add_step(new $stepclass('videoaula_structure', 'videoaula.xml'));
    }

    /**
     * @return array<int, restore_decode_content>
     * @author David <github.com/DavidMarquesDev>
     */
    public static function define_decode_contents(): array
    {
        return [
            new restore_decode_content('videoaula', ['intro'], 'videoaula'),
        ];
    }

    /**
     * @return array<int, restore_decode_rule>
     * @author David <github.com/DavidMarquesDev>
     */
    public static function define_decode_rules(): array
    {
        return [
            new restore_decode_rule('VIDEOAULAINDEX', '/mod/videoaula/index.php?id=$1', 'course'),
            new restore_decode_rule('VIDEOAULAVIEWBYID', '/mod/videoaula/view.php?id=$1', 'course_module'),
        ];
    }
}
