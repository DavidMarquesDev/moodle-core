<?php
declare(strict_types=1);

use core\output\notification;

defined('MOODLE_INTERNAL') || die();

/**
 * Bloco de dashboard customizado para estudantes.
 *
 * @package block_meu_dashboard
 * @author David <github.com/DavidMarquesDev>
 */
class block_meu_dashboard extends block_base
{
    /**
     * Inicializa título do bloco.
     *
     * @return void
     * @author David <github.com/DavidMarquesDev>
     */
    public function init(): void
    {
        $this->title = get_string('pluginname', 'block_meu_dashboard');
    }

    /**
     * Define formatos onde o bloco pode ser exibido.
     *
     * @return array<string, bool>
     * @author David <github.com/DavidMarquesDev>
     */
    public function applicable_formats(): array
    {
        return [
            'my' => true,
            'course-view' => true,
            'site' => true,
        ];
    }

    /**
     * Indica que o bloco possui configuração global.
     *
     * @return bool
     * @author David <github.com/DavidMarquesDev>
     */
    public function has_config(): bool
    {
        return false;
    }

    /**
     * Renderiza o conteúdo do bloco.
     *
     * @return stdClass
     * @author David <github.com/DavidMarquesDev>
     */
    public function get_content(): \stdClass
    {
        global $OUTPUT, $PAGE, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        if (!isloggedin() || isguestuser()) {
            $this->content = new \stdClass();
            $this->content->text = $OUTPUT->notification(
                get_string('loginrequired', 'block_meu_dashboard'),
                notification::NOTIFY_INFO
            );
            $this->content->footer = '';
            return $this->content;
        }

        $context = [
            'title' => get_string('dashboardtitle', 'block_meu_dashboard'),
            'metricsloading' => get_string('loading', 'block_meu_dashboard'),
            'messagesloading' => get_string('loading', 'block_meu_dashboard'),
            'chartloading' => get_string('loading', 'block_meu_dashboard'),
            'emptylabel' => get_string('nomessages', 'block_meu_dashboard'),
        ];

        $PAGE->requires->js_call_amd('block_meu_dashboard/dashboard', 'init', [
            'userid' => (int)$USER->id,
        ]);

        $this->content = new \stdClass();
        $this->content->text = $OUTPUT->render_from_template('block_meu_dashboard/dashboard', $context);
        $this->content->footer = '';

        return $this->content;
    }
}
