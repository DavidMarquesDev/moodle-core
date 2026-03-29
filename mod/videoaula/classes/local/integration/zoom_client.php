<?php
declare(strict_types=1);

namespace mod_videoaula\local\integration;

use core\exception\moodle_exception;

/**
 * @author David <github.com/DavidMarquesDev>
 */
class zoom_client
{
    /**
     * @var string
     */
    private string $endpoint;

    /**
     * @var string
     */
    private string $apikey;

    /**
     * @param string $endpoint
     * @param string $apikey
     * @author David <github.com/DavidMarquesDev>
     */
    public function __construct(string $endpoint, string $apikey)
    {
        $this->endpoint = rtrim($endpoint, '/');
        $this->apikey = $apikey;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws moodle_exception
     * @author David <github.com/DavidMarquesDev>
     */
    public function create_meeting(array $payload): array
    {
        $url = $this->endpoint . '/users/me/meetings';
        $curl = new \curl();
        $headers = [
            'Authorization: Bearer ' . $this->apikey,
            'Content-Type: application/json',
        ];

        $response = $curl->post($url, json_encode($payload, JSON_THROW_ON_ERROR), ['CURLOPT_HTTPHEADER' => $headers]);
        $statuscode = (int)$curl->get_info()['http_code'];

        if ($statuscode < 200 || $statuscode >= 300) {
            throw new moodle_exception('errorproviderconfig', 'videoaula');
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded)) {
            throw new moodle_exception('errorproviderconfig', 'videoaula');
        }

        return $decoded;
    }
}
