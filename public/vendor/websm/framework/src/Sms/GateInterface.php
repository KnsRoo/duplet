<?php

namespace Websm\Framework\Sms;

interface GateInterface {

    public function send(MessageInterface $message);

}
