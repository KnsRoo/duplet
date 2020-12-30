<?php

namespace Back\Files;
use Model\FileMan\Folder;
use Model\FileMan\File;

class Connection extends \Websm\Framework\Db\Connection{

    public function __construct($params){

        parent::__construct($params);
        $this->initDb();

    }

    public function initDb(){

        $this->exec('

            PRAGMA foreign_keys = "1";

            CREATE TABLE '.Folder::getTable().' (id VARCHAR (60), cid VARCHAR (60) REFERENCES '.Folder::getTable().' (id) ON DELETE CASCADE ON UPDATE CASCADE, title TEXT, date INTEGER, keywords TEXT, user TEXT, PRIMARY KEY (id), FOREIGN KEY (cid) REFERENCES '.Folder::getTable().' (id) ON DELETE CASCADE);

            CREATE TABLE '.File::getTable().' (id VARCHAR (60) PRIMARY KEY, cid VARCHAR (60), title TEXT, size INTEGER, type TEXT, ext TEXT, date INTEGER, text_date TEXT, keywords TEXT, user TEXT, no_del VARCHAR (60) REFERENCES '.Folder::getTable().' (id) ON DELETE SET NULL);

        ');
    
    }

}
