<?php

	namespace Back\Files;

use Model\FileMan\File;

class Picture {

		public static function get($id = '', $size = '150x150') {

			$picture = File::find(['id' => $id])->get();
			
			if($picture->isPicture())
				return $picture->getPicture($size);
			else
				return '';

		}

	}
