<?php

namespace Websm\Framework\Db;

class Postgres 
{
	public static function escape($binary){
		return "\\x".bin2hex($binary);
	}

	public static function unescape($binary){
		return "\\x".bin2hex(stream_get_contents($binary));
	}
}