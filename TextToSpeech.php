<?php

/**
 * Created by PhpStorm.
 * User: Rezaur
 * Date: 5/17/2017
 * Time: 1:03 AM
 */

use \Curl\Curl;

final class TextToSpeech
{
    private static $inst;
    private $curl;
    public $voice = 'en-US_AllisonVoice';
    const TEXT_TO_SPEECH_URI = 'https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize';
    const CONTENT_TYPE = 'application/json';
    const IBM_RECOGNIZE_URI = 'v1/recognize';
    const IBM_OBSERVE_RESULT_URI = 'observe_result';
    const DEFAULT_REQUEST_METHOD = 'POST';

    /**
     * TextToSpeech constructor.
     *
     * @internal param $voice
     * @throws \ErrorException
     */
    private function __construct()
    {
        $username = getenv('TEXT_TO_SPEECH_USERNAME');
        $password = getenv('TEXT_TO_SPEECH_PASSWORD');
        $curl = new Curl();
        $curl->setUrl(self::TEXT_TO_SPEECH_URI);
        $curl->setBasicAuthentication($username, $password);
        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('Accept', 'audio/wav');
        $curl->head(self::TEXT_TO_SPEECH_URI, array('voice'=>$this->voice));

        $this->curl = $curl;
    }

    /**
     * @param $text
     *
     * @return string
     */

    public function post($text)
    {
        $this->curl->post(self::TEXT_TO_SPEECH_URI, $text);
        $aud_data = $this->curl->response;
        $name = uniqid('WS_', true) . '.wav';
        file_put_contents('./public/audios'.$name, $aud_data);
        return $name;
    }

    /**
     * @return null|\TextToSpeech
     * @throws \ErrorException
     */
    public static function getInstance()
    {
        if (self::$inst === null) {
            self::$inst = new TextToSpeech();
        }
        return self::$inst;
    }
}