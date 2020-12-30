<?php

namespace Back\Journal;

use Websm\Framework\Router\Router;
use Core\Misc\CSV;
use Core\ModuleInterface;

class Journal extends \Websm\Framework\Response implements ModuleInterface {

    private $link;

    protected $model;
    protected $url = 'settings/journal';
    protected $title = 'Журнал';
    protected $data = [];

    public function __construct(&$props = []) { }

    public function setSettings(Array &$props = []) { }

    public function getSettings() { return []; }

    public function init($req, $next) {

        /***
        * Pushing $resp
        */
        $this->css = array_merge($this->css, [
            'css/journal.css'
        ]);

        $this->js = array_merge($this->js, [
            'js/journal.js',
            'plugins/corrector.js/corrector.min.js'
        ]);

        $router = Router::instance();

        $group = Router::group();

        $group->addGet('/export', [$this, 'export'])
            ->setName('Journal.export');

        $router->mount('/', $group);

        $next();

        $this->genContent();

    }

    public function export() {

        $start = &$_GET['date-start'];
        $end = &$_GET['date-end'];
        $users = &$_GET['users'];

        if (!$start || !$end) $this->back();

        $start = \DateTime::createFromFormat('d.m.y', $start);
        $end = \DateTime::createFromFormat('d.m.y', $end);

        if($users){
        $users = "%<b>".$users."</b>%";
        $rows = \Model\Journal::find()
            ->where('`date` >= :start', [':start' => $start->format('Y-m-d 00:00:00')])
            ->andWhere('`date` <= :end', [':end' => $end->format('Y-m-d 23:59:59')])
            ->andWhere('`message` LIKE :users', [':users' => $users])
            ->order('date DESC')
            ->genAll();
        }
        else{
        $rows = \Model\Journal::find()
            ->where('`date` >= :start', [':start' => $start->format('Y-m-d 00:00:00')])
            ->andWhere('`date` <= :end', [':end' => $end->format('Y-m-d 23:59:59')])
            ->order('date DESC')
            ->genAll();
        }
        /* print_r($rows->createCommand()); */
        /* echo "<br>"; */
        /* print_r($rows->params()); */
        /* die(); */
        $csv = new CSV;
        $csv->addRow('Дата', 'Сообщение', 'ip', 'Статус', 'Модуль');

        foreach($rows as $row) {

            $data = [];

            $data[] = $row->getDate('d.m.Y H:i:s');
            $data[] = strip_tags($row->message);
            $data[] = $row->ip;
            $data[] = $row->status;
            $data[] = $row->module;

            $csv->addRow(...$data);

        }

        header('Content-Disposition: attachment; filename="export.csv"'); 
        header('Content-type: text/csv');
        $this->send($csv);

    }

    public function genContent() {

        $this->data = \Model\Journal::find()
            ->limit(30)
            ->order(['`date` DESC'])
            ->genAll();

    }

    public function getContent(){

        return $this->render(__DIR__.'/temp/default.tpl');

    }

    public function getSettingsContent($name = '', Array $permitions) {}
}
