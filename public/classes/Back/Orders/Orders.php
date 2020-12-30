<?php

namespace Back\Orders;

use Websm\Framework\Router\Router;
use Websm\Framework\Notify;
use Websm\Framework\Db\Qb;
use Websm\Framework\JWT\Client as JWTClient;

use Core\ModuleInterface;

use Websm\Framework\Router\Request\Query;
use Websm\Framework\Mail\HTMLMessage;
use Websm\Framework\Sms;

use Websm\Framework\CSV;

class Orders extends \Websm\Framework\Response implements ModuleInterface
{

    protected $url = 'orders';
    protected $title = 'Заказы';
    protected $orders;

    private $content = '';

    public $permitions = [
        'send-mail' => 'off',
        'send-sms' => 'off',
    ];

    public function __construct()
    {
    }

    public function init($req, $next)
    {
        $this->js = [
            'js/orders.bundle.js',
            'js/auth.js',
            'plugins/ckeditor/ckeditor.js',
        ];

        $this->css = [
            'css/orders.css'
        ];

        $router = Router::instance();

        $router->mount('/', $this->getRoutes());

        $next();
    }
    public function getRoutes()
    {
        $group = Router::group();

        $api = new API\V1\Controller;
        $group->mount('/api', $api->getRoutes());

        $group->addGet('/', [$this, 'defaultAction']);

        $group->addGet('/export-csv', [$this, 'generateNewCsv'])
            ->setName('order:export-csv');

        return $group;
    }

    public function defaultAction()
    {
        $data = [];

        $orders = \Model\Order::find()
            ->order(['number DESC'])
            ->genAll();

        $data['orders'] = $orders;
        $data['exportAction'] = Router::byName('order:export-csv')
            ->getAbsolutePath();

        $this->content = $this->render(__DIR__ . '/temp/default.tpl', $data);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setSettings(Array &$props = [])
    {
        $this->permitions = array_merge($this->permitions, $props);
    }

    public function getSettings()
    {
        return $this->permitions;
    }

    public function getSettingsContent($name = '', Array $permitions)
    {
        return $this->render(__DIR__.'/temp/settings.tpl', $permitions);
    }

    public function generateNewCsv($req)
    {
        if (!isset($req->query['year']) || !isset($req->query['month'])) {
            Notify::push('Выберите месяц и год для выгрузки', 'err');
            $this->location('/admin/orders');
        }

        $year = $req->query['year'];
        $month = $req->query['month'];

        $orders = \Model\Order::find()
            ->andWhere("MONTH(CAST(JSON_UNQUOTE(JSON_EXTRACT(JSON_EXTRACT(props, '$.\"Дата\"'), '$.value')) AS DATETIME)) = :month", ['month' => $month])
            ->andWhere("YEAR(CAST(JSON_UNQUOTE(JSON_EXTRACT(JSON_EXTRACT(props, '$.\"Дата\"'), '$.value')) AS DATETIME)) = :year", ['year' => $year])
            ->getAll();

        if (!$orders) {
            Notify::push('Нет заказов за выбранный период', 'err');
            $this->location('/admin/orders');
        }

        $values = [
            'kol_zak' => 0,
            'summa' => 0,
            'skid' => 0,
        ];

        foreach ($orders as $order) {
            $props = json_decode($order->props, true);

            ++$values['kol_zak'];

            foreach ($props['Товары']['value'] as $product)
                $values['summa'] += $product['count'] * $product['price'];
        }

        $monthToString = [
            1 => 'январь',
            2 => 'февраль',
            3 => 'март',
            4 => 'апрель',
            5 => 'май',
            6 => 'июнь',
            7 => 'июль',
            8 => 'август',
            9 => 'сентябрь',
            10 => 'октябрь',
            11 => 'ноябрь',
            12 => 'декабрь',
        ];

        $csv = new CSV;
        $csv->addRow(
            'kol_zak',
            'summa',
            'skid',
            $monthToString[$month] . ' ' . $year,
        );

        $data = [
            $values['kol_zak'],
            $values['summa'],
            $values['skid'],
            $monthToString[$month] . ' ' . $year,
        ];

        $csv->addRow(...$data);

        header('Content-Disposition: attachment; filename="export.csv"');
        header('Content-type: text/csv');
        $this->send($csv);
    }

    public function generateCsv()
    {
        $rows = \Model\Order::find()
            ->genAll();

        $csv = new CSV;
        $csv->addRow(
            'Дата и время',
            /* 'Код операции', */
            'Заказчик',
            'Сумма',
            /* 'Способ оплаты', */
            /* 'Способ получения', */
            /* 'Статус', */
            /* 'Телефон', */
            /* 'E-mail', */
            /* 'Комментарий', */
            /* 'Товары', */
        );

        foreach ($rows as $row) {

            $data = [];

            $props = json_decode($row->props, true);

            $date = $props['Дата']['value'];
            $code = $props['Код']['value'];
            $customer = $props['Фамилия']['value'] . ' ' . $props['Имя']['value'] . ' ' . $props['Отчество']['value'];

            $products = $props['Товары']['value'];
            $summ = 0;

            foreach ($products as $product) {
                $summ += $product['count'] * $product['price'];
            }

            $paymentMethod = $props['Способ оплаты']['value'];
            $methodOfObtaining = $props['Способ получения']['value'];
            $status = $props['Статус']['value'];
            $phone = $props['Телефоны']['value'][0];
            $email = $props['Электронные почты']['value'][0];
            $comment = $props['Коментарий']['value'] ?? '';
            $products = json_encode($products);

            $data[] = $date;
            /* $data[] = $code; */
            $data[] = $customer;
            $data[] = $summ;
            /* $data[] = $paymentMethod; */
            /* $data[] = $methodOfObtaining; */
            /* $data[] = $status; */
            /* $data[] = $phone; */
            /* $data[] = $email; */
            /* $data[] = $comment; */
            /* $data[] = $products; */

            $csv->addRow(...$data);
        }

        header('Content-Disposition: attachment; filename="export.csv"');
        header('Content-type: text/csv');
        $this->send($csv);
    }
}
