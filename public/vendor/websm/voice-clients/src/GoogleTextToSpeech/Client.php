<?php

namespace Websm\VoiceClients\GoogleTextToSpeech;

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

use Websm\VoiceClients\Interfaces\MP3Generator;

class Client implements MP3Generator {

    private $params = [
        'lang' => 'ru-RU',
        'credentials' => [],
    ];

    public function __construct(Array $params = []) {

        $this->params = array_merge($this->params, $params);
    }

    public function getMP3(String $text) {


        $client = new TextToSpeechClient([
            'credentials' => $this->params['credentials'],
        ]);

        $synthesisInputText = (new SynthesisInput())
            ->setText($text);

        $voice = (new VoiceSelectionParams())
            ->setLanguageCode($this->params['lang'])
            ->setSsmlGender(SsmlVoiceGender::FEMALE);

        $effectsProfileId = "telephony-class-application";

        $audioConfig = (new AudioConfig())
            ->setAudioEncoding(AudioEncoding::MP3)
            ->setEffectsProfileId(array($effectsProfileId));

        $response = $client->synthesizeSpeech($synthesisInputText, $voice, $audioConfig);
        $audioContent = $response->getAudioContent();

        return $audioContent;
    }
}
