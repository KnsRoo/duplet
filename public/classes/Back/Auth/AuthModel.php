<?php

namespace Back\Auth;

use Core\User;

class AuthModel{

    private $link;
    private $memcach;

    public static function isLocked() {

        $count = ForBan::find(['ip' => self::getIp()])
            ->andWhere(['trys' => 0])
            ->andWhere('`banTimeEnd` >= :date',
                [':date' => (int)date('U')])
            ->limit(1)
            ->count();

        return $count ?: false;

    }

    public static function decrTry() {

        $result = ForBan::find(['ip' => self::getIp()])
            ->andWhere('`trys` > 0')
            ->limit(1)
            ->get();

        if ($result->isNew()) {

            $result->ip = self::getIp();
            $result->trys = 2;
            $result->save();

        }
        else {

            $result->trys--;
            $result->save();

        }

    }

    public static function deAuth(){

        !isset($_SESSION) && session_start();
        unset($_SESSION['CoreUser']);

    }

    public static function setBan() {

        $result = ForBan::find(['ip' => self::getIp(), 'trys' => 0])
            ->limit(1)
            ->get();

        if ($result->isNew()) return false;

        $diff = (int)$result->banTimeEnd - (int)$result->banTimeStart;
        $diff = $diff ? $diff + rand(60,300) : 150;
        $banTimeStart = date('U');
        $banTimeEnd = date('U') + $diff * 2;

        $result->banTimeStart = $banTimeStart;
        $result->banTimeEnd = $banTimeEnd;
        $result->save();

    }

    public static function rmBan() {

        return ForBan::query()
            ->delete()
            ->where(['ip' => self::getIp()])
            ->execute();

    }

    public static function setCurentSession(User $user){

        $memcach = new \Memcached();
        $memcach->addServer('localhost', 0);
        $memcach->set($_SERVER['HTTP_HOST'].':'.$user->login, session_id(), 86400); //24 hous
        $memcach->quit();

    }

    public static function isCurrentSession(User $user){

        $memcach = new \Memcached();
        $memcach->addServer('localhost', 0);
        $sesion = $memcach->get($_SERVER['HTTP_HOST'].':'.$user->login);
        $memcach->quit();
        return $sesion == session_id();

    }

    public static function getIp() {

        $remoteAddr = isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            ? $_SERVER['HTTP_X_FORWARDED_FOR']
            : $_SERVER['REMOTE_ADDR'];

        return $remoteAddr;

    }

    public static function getPass() {

        $password = &$_POST['password'];
        return $password;

    }

    public static function set(User $user){

        !isset($_SESSION) && session_start();
        return $_SESSION['CoreUser'] = (Array)$user;

    }

    public static function &get(){

        !isset($_SESSION) && session_start();
        return $_SESSION['CoreUser'];

    }

    public static function isAuth(){

        !isset($_SESSION) && session_start();
        return isset($_SESSION['CoreUser']);

    }

    // =================================================

    public function __construct_(\Mysqli &$_link){

        !isset($_SESSION) && session_start();
        $this->link=$_link;
        $this->memcach = new \Memcached();
        $this->memcach->addServer('localhost', 0);

    }

    public function deAuth_(){
        unset($_SESSION['CoreUser']);
    }

    public function set_(User $user){
        return $_SESSION['CoreUser'] = (Array)$user;
    }

    public function &get_(){
        return $_SESSION['CoreUser'];
    }

    public function old_get(){
        return isset($_SESSION['CoreUser']) ? $_SESSION['CoreUser'] : [];
    }

    public function getPass_(){
        return (isset($_POST['password']) && $_POST['password']) ? $_POST['password'] : '';
    }

    public function setCurentSession_(User $user){
        $this->memcach->set($_SERVER['HTTP_HOST'].':'.$user->login, session_id(), 86400); //24 hous
    }

    public function isCurrentSession_(User $user){
        $sesion = $this->memcach->get($_SERVER['HTTP_HOST'].':'.$user->login);
        return $sesion == session_id();
    }

    public function isAuth_(){
        return isset($_SESSION['CoreUser']);
    }

    public function isLocked_(){
        $q=$this->link->query('SELECT * FROM `forBan` WHERE `ip`="'.$this->res($_SERVER['REMOTE_ADDR']).'" AND `trys`=0 AND `banTimeEnd`>='.(int)date('U').' LIMIT 1');
        return $q ? $q->num_rows : false;
    }

    public function isForgot(){
        return (isset($_POST['login']) && isset($_POST['forgot']) && $_POST['login']) ? $_POST['login'] : false;
    }

    public function isEntry(){
        return (isset($_POST['login']) && isset($_POST['password']));
    }

    public function isExit(){
        return isset($_POST['exit']);
    }

    public function decrTry_(){
        $this->link->query('SELECT `id` FROM `forBan` WHERE `ip`="'.$this->res($_SERVER['REMOTE_ADDR']).'" LIMIT 1')->num_rows>0 ?
        $this->link->query('UPDATE `forBan` SET `trys`=`trys`-1 WHERE `ip`="'.$this->res($_SERVER['REMOTE_ADDR']).'" AND `trys`>0 LIMIT 1') :
        $this->link->query('INSERT INTO `forBan` (`ip`,`trys`) VALUES ("'.$this->res($_SERVER['REMOTE_ADDR']).'", 2)');
    }

    public function setBan_(){
        $q=$this->link->query('SELECT * FROM `forBan` WHERE `ip`="'.$this->res($_SERVER['REMOTE_ADDR']).'" AND `trys`=0 LIMIT 1');
        if($q->num_rows==0)return false;
        $f=$q->fetch_assoc();
        $diff=(int)$f['banTimeEnd']-(int)$f['banTimeStart'];
        $diff = $diff ? $diff+rand(60,300) : 150;
        $banTimeStart=date('U');
        $banTimeEnd=date('U') + $diff * 2;
        $this->link->query('UPDATE `forBan` SET `banTimeStart`="'.$this->res($banTimeStart).'", `banTimeEnd`="'.$this->res($banTimeEnd).'" WHERE `ip`="'.$this->res($_SERVER['REMOTE_ADDR']).'" AND `trys`=0 LIMIT 1');
    }

    public function rmBan_(){
        $this->link->query('DELETE FROM `forBan` WHERE `ip`="'.$this->res($_SERVER['REMOTE_ADDR']).'"');
    }

    private function res($str=''){
        return $this->link ? $this->link->real_escape_string($str) : '';
    }

}
