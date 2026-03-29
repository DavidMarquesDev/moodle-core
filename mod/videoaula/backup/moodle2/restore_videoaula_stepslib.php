<?php
declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

/**
 * Define a estrutura de restore da atividade videoaula.
 *
 * @author David <github.com/DavidMarquesDev>
 */
class restore_videoaula_activity_structure_step extends restore_activity_structure_step
{
    /**
     * @return array<int, restore_path_element>
     * @author David <github.com/DavidMarquesDev>
     */
    protected function define_structure(): array
    {
        $paths = [];
        $paths[] = new restore_path_element('videoaula', '/activity/videoaula');

        return $this->prepare_activity_structure($paths);
    }

    /**
     * @param array<string, mixed> $data
     * @return void
     * @throws dml_exception
     * @author David <github.com/DavidMarquesDev>
     */
    protected function process_videoaula(array $data): void
    {
        global $DB;

        $dataobject = (object)$data;
        $dataobject->course = $this->get_courseid();

        $newitemid = $DB->insert_record('videoaula', $dataobject);
        $this->apply_activity_instance($newitemid);
    }

    /**
     * @return void
     * @author David <github.com/DavidMarquesDev>
     */
    protected function after_execute(): void
    {
        $this->add_related_files('mod_videoaula', 'intro', null);
    }
}
