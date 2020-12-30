<?php

namespace Websm\Framework\Sms;

use Websm\Framework\Exceptions\HTTP as HTTPException;

class SmsSenderClient implements GateInterface {

    const PATH_TEMPLATE = "/sender";

    private $url;

    public function __construct(string $origin) {

        $this->url = $origin . self::PATH_TEMPLATE;
    }

    public function send(MessageInterface $message) {

        $msg = $message->getBody();
        $to = $message->getPhone();

        $body = json_encode([
            'to' => (String)$to,
            'msg' => (String)$msg,
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST' );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body))
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

        curl_close($ch);

        if ($status == '200') return;
        else throw new HTTPException('error while sending message', $status);
    }
}
