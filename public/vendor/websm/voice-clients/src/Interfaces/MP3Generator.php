<?php

namespace Websm\VoiceClients\Interfaces;

interface MP3Generator {

    /**
     * @return raw content of mp3 file
     */
    public function getMP3(String $text);
}
