<?php

namespace Websm\Framework\Mail;

include_once 'Mail.php';
include_once 'Mail/mime.php';

use Mail;
use Mail_mime as MailMime;

class HTMLMessage {

    const MIME_PARAMS = [
        'text_encoding' => '8bit',
        'text_charset' => 'UTF-8',
        'html_charset' => 'UTF-8',
        'head_charset' => 'UTF-8',
    ];

    private $from = '';
    private $to = '';
    private $subject = 'Empty subject';
    private $body = 'Empty body';
    private $attachments = [];
    private $headers = [];

    public function addfile($file, $name = null) {

        if (!$name) $name = pathinfo($file)['basename'];

        $this->attachments[$name] = $file;

        return $this;

    }

    public function setHeaders(Array $headers = []) {

        $this->headers = $headers;
        return $this;

    }

    public function setHeader($key, $value) {

        $this->headers[$key] = $value;
        return $this;

    }

    public function setFrom($from) {

        $this->from = $from;
        return $this;

    }

    public function setTo($to) {

        $this->to = $to;
        return $this;

    }

    public function setUnsubscribe($unsubscribe) {

        $this->headers['List-Unsubscribe'] = $unsubscribe;
        return $this;

    }

    public function setSubject($subject) {

        $this->subject = $subject;
        return $this;

    }

    public function setBody($body = '') {

        $this->body = $body;
        return $this;

    }

    /**
     * send 
     *
     * Отправляет сообщение.
     * 
     * @access public
     * @return boolean
     *
     * @code

    $message = new HTMLMessage;

    $message->setFrom('Name<from@example.com>')
        ->setTo('<to@example.com>')
        ->setSubject('Test subject')
        ->addfile('test.txt')
        ->setBody('<h1>Test message!</h1>');

    $message->send();

    * @endcode
     */
    public function send() {

        $mime = new MailMime(['eol' => "\n"]);
        $mime->setHTMLBody($this->body);

        foreach ($this->attachments as $name => $file)
            $mime->addAttachment($file, 'application/octet-stream', $name);

        $body = $mime->get(self::MIME_PARAMS);

        $headers = $mime->headers([
            'From' => $this->from,
            'Subject' => $this->subject,
        ] + $this->headers);

        $mail = Mail::factory('mail');
        $mail->send($this->to, $headers, $body);

        return true;

    }

}
