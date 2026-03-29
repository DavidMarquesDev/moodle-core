<?php
declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

/**
 * Define a estrutura de backup da atividade videoaula.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class backup_videoaula_activity_structure_step extends backup_activity_structure_step
{
    /**
     * @return backup_nested_element
     * @author David <github.com/DavidMarquesDev>
     */
    protected function define_structure(): backup_nested_element
    {
        $videoaula = new backup_nested_element('videoaula', ['id'], [
            'name',
            'intro',
            'introformat',
            'meetingtopic',
            'meetingduration',
            'meetingstart',
            'meetingid',
            'joinurl',
            'hosturl',
            'timecreated',
            'timemodified',
        ]);

        $videoaula->set_source_table('videoaula', ['id' => backup::VAR_ACTIVITYID]);
        $videoaula->annotate_files('mod_videoaula', 'intro', null);

        return $this->prepare_activity_structure($videoaula);
    }
}
