<?php
declare(strict_types=1);

namespace mod_videoaula\local\service;

use mod_videoaula\local\integration\zoom_client;
use core\exception\moodle_exception;
use stdClass;

/**
 * @author David <github.com/DavidMarquesDev>
 */
class meeting_service
{
    /**
     * @var zoom_client
     */
    private zoom_client $client;

    /**
     * @param zoom_client $client
     * @author David <github.com/DavidMarquesDev>
     */
    public function __construct(zoom_client $client)
    {
        $this->client = $client;
    }

    /**
     * @return self
     * @throws moodle_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public static function create_default(): self
    {
        $endpoint = (string)get_config('videoaula', 'zoomapiendpoint');
        $apikey = (string)get_config('videoaula', 'zoomapikey');
        if ($endpoint === '' || $apikey === '') {
            throw new moodle_exception('errorproviderconfig', 'videoaula');
        }

        return new self(new zoom_client($endpoint, $apikey));
    }

    /**
     * @param stdClass $videoaula
     * @param stdClass $user
     * @return array<string, mixed>
     * @throws moodle_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public function create_course_meeting(stdClass $videoaula, stdClass $user): array
    {
        global $DB;

        $payload = [
            'topic' => (string)$videoaula->meetingtopic,
            'type' => 2,
            'start_time' => gmdate('Y-m-d\TH:i:s\Z', (int)$videoaula->meetingstart),
            'duration' => (int)$videoaula->meetingduration,
            'agenda' => format_string((string)$videoaula->name),
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => false,
                'mute_upon_entry' => true,
            ],
        ];

        $response = $this->client->create_meeting($payload);

        $videoaula->meetingid = (string)($response['id'] ?? '');
        $videoaula->joinurl = (string)($response['join_url'] ?? '');
        $videoaula->hosturl = (string)($response['start_url'] ?? '');
        $videoaula->timemodified = time();
        $DB->update_record('videoaula', $videoaula);

        return [
            'id' => $videoaula->meetingid,
            'joinurl' => $videoaula->joinurl,
            'hosturl' => $videoaula->hosturl,
            'requestedby' => (int)$user->id,
        ];
    }
}
