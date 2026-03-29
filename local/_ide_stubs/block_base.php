<?php
declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

/**
 * Stub de análise estática para block_base.
 *
 * @author David <github.com/DavidMarquesDev>
 */
abstract class block_base
{
    /**
     * @var string
     */
    public $title = '';

    /**
     * @var \stdClass|null
     */
    public $content = null;
}
