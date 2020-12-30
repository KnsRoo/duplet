<?php

namespace Websm\Framework\Sms;

class Tele2Gate implements GateInterface {

    const PARAMS_TEMPLATE = '?operation=send&login=%s&password=%s&msisdn=%s&shortcode=%s&text=%s';

    private $url = 'https://newbsms.tele2.ru/api/send';
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

            $params = sprintf(
                self::PARAMS_TEMPLATE,
                $this->login,
                $this->password,
                $message->getPhone(),
                urlencode($message->getAlias()),
                urlencode($chunk)
            );

            file_get_contents($this->url.$params);

        }

    }

}
