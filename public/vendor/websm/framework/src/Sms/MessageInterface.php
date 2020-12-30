<?php

namespace Websm\Framework\Sms;

interface MessageInterface {

    public function getAlias();

    public function getPhone();

    public function getBody();

}
