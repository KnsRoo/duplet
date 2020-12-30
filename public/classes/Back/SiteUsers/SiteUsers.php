<?php

namespace Back\SiteUsers;

use Websm\Framework\Response;
use Websm\Framework\Notify;
use Websm\Framework\Router\Router;
use Core\ModuleInterface;
use Core\Misc\Pager\Pager;
use Core\Misc\CSV;
use Back\SiteUsers\Search;

use Model\User as UserModel;
use Model\Catalog\Group;
use Websm\Framework\Mail\HTMLMessage;
use Websm\Framework\Di;

class SiteUsers extends Response implements ModuleInterface {

    const TEMPLATES = __DIR__ . '/temp/';

    public $permitions = [
        'creating' => 'off',
        'deleting' => 'off',
        'editing' => 'off',
    ];

    protected $href = 'user';
    protected $title = 'Пользователи сайта';

    protected $pageNumber = 1;
    protected $countItems = 20;
    protected $pageUrlTemplate = '/page-:page/?';

    private $message = [
        'status' => 'ok',
        'notify' => [],
        'content' => '',
    ];

    public function __construct(&$props = []) {

        $this->di = Di\Container::instance();

    }

    public function setSettings(Array &$props = []) {

        $this->permitions = array_merge($this->permitions, $props);

    }

    public function getSettings() { return $this->permitions; }

    public function getRoutes() {

        $group = Router::group();

        $api = new API\Controller;
        $group->mount('/api', $api->getRoutes());

        $group->addGet('/', [ $this, 'getContent' ]);

        $group->addGet('/export-csv', [ $this, 'generateCsv' ]);

        $group->addPost('/mailing', [ $this, 'mailing' ]);

        return $group;

    }

    public function init($req, $next) {

        $this->css = [
            'css/user.css',
            'css/filesMin.css',
        ];

        $this->js = array_merge($this->js, [
            //'js/plugins/popup/popup.min.js',
            'plugins/ckeditor/ckeditor.js',
            //'js/filesMin.js',
            'js/adminUsers.js',
        ]);

        $route = Router::instance();

        $route->mount('/', $this->getRoutes());

        $next();

    }

    public function getContent(){
        
        return $this->render(self::TEMPLATES . 'default.tpl');

    }

    public function getSettingsContent($name = '', Array $permitions) {

        return $this->render(self::TEMPLATES . 'settings.tpl', $permitions);

    }

    private function getLastChildGroup(Array $group){
        $buff = [];
        foreach($group as $g){
            $subGroups = Group::find([ 'cid' => $g->id ])
                ->order('`sort` ASC')
                ->getAll();

            if($subGroups){
                $buff = array_merge($buff, $this->getLastChildGroup($subGroups));
            }
            else{
                $buff[] = $g;
            }
        }
        return $buff;
    }

    private function getGroupPath($group, $gPath = '', $endId = null){

        $pGroup = Group::find([ 'id' => $group->cid ])
            ->get();
        if($pGroup->id === $endId)
            return trim($gPath);
        else
            return $this->getGroupPath($pGroup, $pGroup->title."/".$gPath);

    }
    
    public function generateCsv(){

//test

        $rows = UserModel::find()
            ->order('`creation_date` DESC')
            ->genAll();
        
        $csv = new CSV;
        $csv->addRow(
            'Номер телефона', 
            'Имя', 
            'Фамилия', 
            'Отчество', 
            'Email', 
            'Дата рождения', 
            'Дата создания', 
            'Дата обновления', 
            'Id групп', 
            'Id цепочек'
        );

        $crypt = $this->di->get('crypt');
        
        foreach($rows as $row) {

            $data = [];

            $props = json_decode($row->props, true);

            $data[] = $crypt->decrypt($row->phone);
            $data[] = $props['name'] ?? '';
            $data[] = $props['surname'] ?? '';
            $data[] = $props['patronymic'] ?? '';
            $data[] = $crypt->decrypt($row->email);
            $data[] = $props['birthDate'] ?? '';
            $data[] = $row->creation_date;
            $data[] = $row->update_date;
            $data[] = isset($props['groupsIds']) ? json_encode($props['groupsIds']) : '';
            $data[] = isset($props['chainsIds']) ? json_encode($props['chainsIds']) : '';
            
            $csv->addRow(...$data);

        }

        header('Content-Disposition: attachment; filename="export.csv"'); 
        header('Content-type: text/csv');
        $this->send($csv);

    }

    public function mailing(){

        if(!isset($_POST['title']) || !isset($_POST['text'])){

            Notify::push('Проверьте правильность заполнения полей', 'err');
            $this->location('/admin/siteusers');

        }

        $title = htmlspecialchars($_POST['title']);
        $text = $_POST['text'];

        $domain = $_SERVER['SERVER_NAME'];

        $users = UserModel::find()
            ->genAll();

        $crypt = $this->di->get('crypt');

        foreach($users as $user){

            if(isset($user->email)){

                $email = $crypt->decrypt($user->email);
                $this->sendMail($email, $title, $text, $domain);

            }

        }

        Notify::push('Письма успешно отправлены', 'ok');
        $this->location('/admin/siteusers');
    }

    private function sendMail($email, $title, $text, $domain){

        $body = "<h3>".$title."</h3><p>".$text."</p>";

        $mail = new HTMLMessage;
        $mail->setFrom('<noreply@mail.websm.io>')
             ->setTo('<' . $email . '>')
             ->setSubject('Сообщение с сайта '.$domain)
             ->setBody($body);

        $mail->send();

    }

}
