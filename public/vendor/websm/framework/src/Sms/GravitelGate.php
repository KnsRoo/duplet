<?php

namespace Websm\Framework\Sms;

class GravitelGate implements GateInterface {

    const BODY_TEMPLATE = '%s -d "login=%s&password=%s&telnum=%%2b%s&sms_message=%s" %s';

    private $url = 'https://doreg.gravitel.ru/sms.php?cmd=send';
    private $curl = '/usr/local/bin/curl';
    private $login = '';
    private $password = '';

    public function __construct($login, $password) {

        $this->login = $login;
        $this->password = $password;

    }

    public function send(MessageInterface $message) {

        $body = $message->getBody(); 
        preg_match_all('/.{1,140}/ums', $body, $chunks);

        foreach ($chunks[0] as $chunk) {

            $cmd = sprintf(
                self::BODY_TEMPLATE,
                $this->curl,
                $this->login,
                $this->password,
                $message->getPhone(),
                urlencode($chunk),
                escapeshellarg($this->url)
            );

            exec($cmd);

        }

    }

}
