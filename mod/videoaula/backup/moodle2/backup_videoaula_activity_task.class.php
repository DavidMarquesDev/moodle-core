<?php
declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/videoaula/backup/moodle2/backup_videoaula_stepslib.php');

/**
 * Define as etapas de backup da atividade videoaula.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class backup_videoaula_activity_task extends backup_activity_task
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
        $stepclass = 'backup_videoaula_activity_structure_step';
        $this->add_step(new $stepclass('videoaula_structure', 'videoaula.xml'));
    }

    /**
     * @param string $content
     * @return string
     * @author David <github.com/DavidMarquesDev>
     */
    public static function encode_content_links($content): string
    {
        global $CFG;

        $base = preg_quote($CFG->wwwroot . '/mod/videoaula', '#');

        $pattern = '#(' . $base . '/index\.php\?id=)([0-9]+)#';
        $content = preg_replace($pattern, '$@VIDEOAULAINDEX*$2@$', $content);

        $pattern = '#(' . $base . '/view\.php\?id=)([0-9]+)#';
        $content = preg_replace($pattern, '$@VIDEOAULAVIEWBYID*$2@$', $content);

        return (string)$content;
    }
}
