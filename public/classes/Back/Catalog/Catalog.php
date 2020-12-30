<?php

namespace Back\Catalog;

use Back\Files\Picture;
use Model\Tags\Tags;
use Model\Journal;
use Model\Catalog\Group;
use Model\Catalog\Product;
use Model\FileMan\File;

use Websm\Framework\Chpu;
use Websm\Framework\Db\Qb;
use Websm\Framework\Notify;
use Websm\Framework\Sort as NewSort;
use Websm\Framework\Router\Router;

use Core\Users;
use Core\ModuleInterface;
use Core\Misc\Pager\Pager;
use Core\Misc\NewPath\NewPath;

class Catalog extends \Websm\Framework\Response implements ModuleInterface  {

    public $permitions = [];

    protected $model;
    protected $cat = null;
    protected $catalog = null;
    protected $product;
    protected $path;
    protected $parent = null;
    protected $updateProduct;
    protected $url = 'catalog';
    protected $title = 'Каталог';
    protected $pager;
    protected $pageNumber = 1;
    protected $topForm;

    private $message = [
        'status' => 'ok',
        'notify' => [],
        'content' => '',
    ];

    public function __construct(&$props = []) { }

    public function setSettings(Array &$props = []) { }

    public function getSettings() { return $this->permitions; }

    public function getRoutes() {

        $api = new API\Controller;

        $group = Router::group();

        $group->mount('/api', $api->getRoutes());

        $group->addPut('/update-picture/id-:productId', [$this, 'updatePicture'])
            ->withAjax();

        $group->addGet('/new-product', [$this, 'newProduct'])
            ->setName('Catalog.newProduct');

        $group->addGet('/update-product-:id', [$this, 'updateProduct'])
            ->setName('Catalog.updateProduct');

        $group->addGet('/update-cat-:id', [$this, 'updateCat'])
            ->setName('Catalog.updateCat');

        $group->addPost('/update-cid/cid-:cid/', [$this, 'updateCid']);

        $group->addPost('/create-product', [$this, 'createProduct'])
            ->setName('Catalog.createProduct');

        $group->addPost('/create-cat', [$this, 'createCat'])
            ->setName('Catalog.createCat');

        $group->addPut('/update-product-:id', [$this, 'updateProductId'])
            ->setName('Catalog.updateProductId');

        $group->addPut('/update-cat-:id', [$this, 'updateCatId'])
            ->setName('Catalog.updateCatId');

        $group->addPut('/update-sort-product-:id', [$this, 'upSortProductId']);

        $group->addPut('/update-sort-cat-:id', [$this, 'upSortCatId']);

        $group->addPut('/update-visibility-product-:id', [$this, 'upVisProductId']);

        $group->addPut('/update-visibility-cat-:id', [$this, 'upVisCatId']);

        $group->addDelete('/delete-product-:id', [$this, 'deleteProductId']);

        $group->addDelete('/delete-cat-:id', [$this, 'deleteCatId']);

        $group->addDelete('/delete-tag', [$this, 'deleteTag'])
            ->withAjax();

        return $group;

    }

    public function init($req, $next) {

        $this->css = [
            'plugins/tabs.js/public/default.min.css',
            'css/catalog.css',
            'css/filesMin.css',
        ];

        $this->js = array_merge($this->js, [
            'plugins/tabs.js/public/tabs.min.js',
            'plugins/ckeditor/ckeditor.js',
            'js/plugins/dndlib/dndlib.min.js',
            'js/catalog.js',
            /* 'js/catalog.bundle.js',// webpack's bundle for extra properties */
            'js/filesMin.js',
        ]);

        $route = Router::instance();

        $route->addAll('/cat-:id?', function($req, $next) {

            $this->catalog = $req['id'] ?: null;

            $catalog = Group::find(['id' => $this->catalog])->get();
            $this->catalogObject = $catalog;
            $this->parent = $catalog->cid;

            $next();

        }, ['end' => false])->where(['id' => '\w+']);

        $route->addAll('/cat-:id', function($req, $next) {

            $data = [];
            $data['catalog'] = $this->catalogObject;
            $this->topForm = $this->render(__DIR__.'/temp/catInfo.tpl', $data);

            $next();

        }, ['end' => false])->where(['id' => '\w+']);

        $route->addAll('/', function($req, $next) {

            if (!empty($_GET['page-number'])) {

                $this->pageNumber = (int)$_GET['page-number'];
                if (empty($this->pageNumber)) $this->pageNumber = 1;

            }

            $next();

        }, ['end' => false]);

        $route->mount('/', $this->getRoutes());
        $route->mount('/cat-:cid?', $this->getRoutes());

        $next();

    }

    public function updatePicture($req) {

        $picture = &$_POST['picture'];

        $result = Qb::table(Product::getTable())
            ->update(['picture' => ($picture ?: null)])
            ->where(['id' => $req['productId']])
            ->execute();

        if ($result) {
            $product = Proguct::find([ 'id' => $req['productId'] ])->get();

             \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> обновил картинку товара "'.$product->title.'".',
                'Каталог');

            Notify::push('Картинка товара успешно изменена.', 'ok');
;
        } else {
            Notify::push('Ошибка изменения картинки товара.', 'err');
        }

        $this->back();

    }

    public function newProduct($req) {

        $this->topForm = $this->render(__DIR__.'/temp/productNew.tpl');

    }

    public function updateProduct($req) {

        $this->updateProduct = $req['id'];

        $ar = Product::find()
            ->where(['id' => $req['id']])
            ->get();

        if (!$ar->isNewRecord()){

            $data = [
                'product' => $ar,
            ];

            $this->topForm = $this->render(__DIR__.'/temp/productEdit.tpl', $data);

        }

    }

    public function updateCat($req) {

        $ar = Group::find()
            ->where(['id' => $req['id']])
            ->get();

        $data = [
            'catalog' => $ar,
            'picture' => '',
        ];

        $this->topForm = $this->render(__DIR__.'/temp/catEdit.tpl', $data);

    }

    public function updateCid($req) {

        $body = $req->body;
        $params = $req->params;

        if  ($body->type == 'cat')
            $ar = Group::find(['id' => $body->id])->get();
        elseif ($body->type == 'product') {
            $ars = Product::find();
            if ($body->offsetExists('id')) $ars->andWhere(['id' => $body->id]);
            if ($body->offsetExists('multi')) $ars->orWhere(['id' => $body->multi]);
            $ars = $ars->getAll();
        }

        if (isset($ar) && $ar->id != $params->cid) {

            $ar->cid = $params->cid ? $params->cid : null;
            $ar->sort = 0;

            $this->updateGroupChpu($ar);

            if ($ar->save()) {
   
                \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> переместил товар "'.$ar->title.'".',
                    'Каталог');

                Notify::push('Успешно перемещено.', 'ok');
                $sort = NewSort::init($ar);
                $sort->in($ar->cid)->normalise();
                $sort->in($this->page)->normalise();

            } else Notify::push('Ошибка перемещения.', 'err');

        }
       
        if (isset($ars)) {

            foreach($ars as $ar)
            {
                $this->catalog = $ar->cid;
                $ar->cid = $params->cid ? $params->cid : null;

                $productsInCategoryCount = Product::find(['cid' => $ar->cid])
                    ->count();
                $ar->sort = $productsInCategoryCount + 1;

                $sameProductsCount = Product::find(['cid' => $ar->cid])
                    ->andWhere(['title' => $ar->title])
                    ->count();

                $ar->hash = md5($ar->cid . $ar->title . $ar->sort);

                Chpu::inject($ar, ['cid' => 'category']);
                if ($sameProductsCount != 0)
                    $ar->chpu .= (string)$sameProductsCount;

                if ($ar->save()) {
   
                    \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                        '<b>'.Users::get()->login.'</b> переместил товар "'.$ar->title.'".',
                        'Каталог');

                    Notify::push('Успешно перемещено.', 'ok');
                    $sort = NewSort::init($ar);
                    $sort->in($this->catalog)->normalise();

                } else Notify::push('Ошибка перемещения.', 'err');

            }

        }
        
        $ar = Group::find(['id' => $req['cid']])->get();
        
        if(!$ar->isNew()){
            $this->catalog = $req['cid'];
            $this->parent = $ar->cid;
        }
    }

    public function updateImage($req) {

        $result = Qb::table(Product::getTable())
            ->update(['picture' => ($req['imageId'] ?: null)])
            ->where(['id' => $req['id']])
            ->execute();

        $result
            ? Notify::push('Картинка товара успешно изменена.', 'ok')
            : Notify::push('Ошибка изменения картинки товара.', 'err');

        $this->back();

    }

    public function createProduct($req) {

        $product = new Product('create');
        $product->bind($_POST['create']);
        $product->id = md5(uniqid());
        $product->cid = $this->catalog;

        $productsInCategoryCount = Product::find(['cid' => $this->catalog])
            ->count();
        $product->sort = $productsInCategoryCount + 1;

        $sameProductsCount = Product::find(['cid' => $product->cid])
            ->andWhere(['title' => $product->title])
            ->count();

        $product->hash = md5($this->catalog . $product->title . $product->sort);

        Chpu::inject($product, ['cid' => 'category']);
        if ($sameProductsCount != 0)
            $product->chpu .= (string)$sameProductsCount;

        if (isset($_POST['file'])) $product->picture = $_POST['file'];

        if ($product->save()) {

            \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> создал товар "'.$product->title.'".',
                'Каталог');

            Notify::push('Товар успешно создан.', 'ok'); 

        } else {

            foreach ($product->getErrors() as $error)
                Notify::pushArray($error);

            Notify::push('Ошибка создания товара.', 'err');

        }

        $this->back();

    }

    public function createCat($req) {

        $ar = new Group('create');
        $ar->bind($_POST['create']);

        $ar->id = md5(uniqid());
        $ar->cid = $this->catalog;
        $ar->hash = md5($ar->title);
        Chpu::inject($ar);

        /* isset($_POST['file']) && */
        /*  $ar->picture = $_POST['file']; */

        if ($ar->save()){

            \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> создал категорию "'.$ar->title.'".',
                'Каталог');

            Notify::push('Категория успешно создана.', 'ok');
            NewSort::init($ar)->normalise();

        }
        else {

            Notify::push('Ошибка создания категории.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);

        }

        $this->back();

    }

    private function updateGroupChpu($group)
    {
        $productsQuery = 'SELECT * FROM `catalog_product` WHERE `chpu` LIKE "' . $group->chpu . '%"';

        $groupsQuery = 'SELECT * FROM `catalog_group` WHERE `chpu` LIKE "' . $group->chpu . '%"';

        Chpu::inject($group);
        $groupLevels = explode('/', $group->chpu);
        $groupLevel = count($groupLevels) - 1;
        $groupChpuEnding = $groupLevels[$groupLevel];

        $products = Qb::query($productsQuery)->getAll();

        foreach($products as $product)
        {
            $productChpuElements = explode('/', $product['chpu']);
            $productChpuElements[$groupLevel] = $groupChpuEnding;
            $product['chpu'] = implode('/', $productChpuElements);

            $query = 'UPDATE `catalog_product` SET `chpu` = :chpu WHERE `id` = :id';
            $params[':chpu'] = $product['chpu'];
            $params[':id'] = $product['id'];

            Qb::query($query, $params)->execute();
        }

        $subGroups = Qb::query($groupsQuery)->getAll();

        foreach($subGroups as $subGroup)
        {
            $productChpuElements = explode('/', $subGroup['chpu']);
            $productChpuElements[$groupLevel] = $groupChpuEnding;
            $subGroup['chpu'] = implode('/', $productChpuElements);

            $query = 'UPDATE `catalog_group` SET `chpu` = :chpu WHERE `id` = :id';
            $params[':chpu'] = $subGroup['chpu'];
            $params[':id'] = $subGroup['id'];

            Qb::query($query, $params)->execute();
        }

    }

    public function updateProductId($req) {

        $product = Product::find()
            ->where(['id' => $req['id']])
            ->get();

        if ($product->isNew()) {

            Notify::push('Товар не существует.', 'err'); 
            $this->back();

        }

        $product->scenario('update');
        $product->tags = [];
        $product->bind($_POST['update']);
        $product->hash = md5($this->catalog . $product->title . $product->sort);

/*         $props = json_decode($product->props, true);

        $group = Group::find(['id' => $product->cid])
            ->get(); */

        $sameProductsCount = Product::find(['cid' => $product->cid])
            ->andWhere(['title' => $product->title])
            ->count();

        //Chpu::inject($product, ['cid' => $group->id]);//ошибка при добавлении тэга
        Chpu::inject($product, ['cid' => 'category']);
        if ($sameProductsCount != 0)
            $product->chpu .= (string)$sameProductsCount;

        $tags = &$_POST['new-tags'];
        $tags = explode(',', $tags);
        $product->addTags($tags);

        if ($product->save()) {

            \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> изменил товар "'.$product->title.'".',
                'Каталог');

            Notify::push('Товар успешно изменен.', 'ok'); 

        } else {

            foreach ($product->getErrors() as $error)
                Notify::pushArray($error);

            Notify::push('Ошибка изменения товара.', 'err');

        }

        $this->back();

    }

    public function updateCatId($req) {

        $group = Group::find()
            ->where(['id' => $req['id']])
            ->get();

        $group->scenario('update');

        if (!$group->isNewRecord()) {

            $group->bind($_POST['update']);
            $group->hash = md5($group->title);
            
            $group->tags = [];
            $tags = &$_POST['new-tags'];
            $tags = explode(',', $tags);
            $group->addTags($tags);

            $this->updateGroupChpu($group);

            if ($group->save()) {

                \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> изменил категорию "'.$group->title.'".',
                    'Каталог');

                Notify::push('Категория успешно изменена.', 'ok'); 

            } else {

                Notify::push('Ошибка изменения категории.', 'err');
                foreach($group->getErrors() as $errors)
                    Notify::pushArray($errors);

            }

        }

        $this->back();

    }

    public function upSortProductId($req) {

        $product = Product::find(['id' => $req['id']])->get();

        NewSort::init($product)
            ->move($_POST['sort'])
            ->normalise();

        $this->back();

    }

    public function upSortCatId($req) {

        $ar = Group::find(['id' => $req['id']])->get();

        NewSort::init($ar)
            ->move($_POST['sort'])
            ->normalise();

        $this->back();

    }

    public function upVisProductId($req) {

        $ar = Product::find(['id' => $req['id']])
            ->get();

        $ar->scenario('visibility');
        if ($ar->isNewRecord()) $this->back();
        $ar->visible = $ar->visible ? 0 : 1;

        if  ($ar->save()) {

            \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> изменил видимость товара "'.$ar->title.'".',
                'Каталог');

            Notify::push('Видимость товара успешно изменена.', 'ok');
        } else {
            Notify::push('Ошибка изменения видимости товара.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);
        }

        $this->back();

    }

    public function upVisCatId($req) {

        $ar = Group::find(['id' => $req['id']])
            ->get();

        $ar->scenario('visibility');
        if ($ar->isNewRecord()) $this->back();
        $ar->visible = $ar->visible ? 0 : 1;

        if  ($ar->save()) {

            \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> изменил видимость категории "'.$ar->title.'".',
                'Каталог');

            Notify::push('Видимость категории успешно изменена.', 'ok');
        } else {

            Notify::push('Ошибка изменения видимости категории.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);
        }

        $this->back();

    }

    public function deleteProductId($req) {

        $ar = Product::find(['id' => $req['id']])->get();

        if ($ar->delete()) {

             \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> удалил товар "'.$ar->title.'".',
                'Каталог');

            Notify::push('Товар успешно удален.', 'ok');
        } else {
            Notify::push('Ошибка удаления товара.', 'err');
        }

        NewSort::init($ar)->normalise();

        $this->back();

    }

    public function deleteCatId($req) {

        $ar = Group::find(['id' => $req['id']])->get();

        if ($ar->delete()) {

             \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> удалил категорию "'.$ar->title.'".',
                'Каталог');

            Notify::push('Категория успешно удалена.', 'ok');
        } else {
            Notify::push('Ошибка удаления категории.', 'err');
        }

        NewSort::init($ar)->normalise();

        $this->back();

    }

    public function deleteTag() {

        $res = $this->message;

        $tagId = &$_POST['tag_id'];

        $tag = Tags::find(['id' => $tagId])->get();

        if ($tag->isNew()) {

            Notify::push('Тэг не найден.', 'err');
            $res['notify'] = Notify::shiftAll();
            $res['status'] = 'err';

            $this->json($res);

        } elseif ($tag->static) {

            Notify::push('Тэг заблокирован от удаления.', 'err');
            $res['notify'] = Notify::shiftAll();
            $res['status'] = 'err';

            $this->json($res);

        }

        \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
            '<b>'.Users::get()->login.'</b> удалил тэг "'.$tag->title.'".',
            'Каталог');

        $tag->delete();
        $res['notify'] = Notify::shiftAll();

        $this->json($res);

    }

    private function getProductCoordinates($address) {

        $productCoords = (object)[];

        $encodedAddress = urlencode($address);
        $resJson = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key='.self::MAPS_API_KEY.'&address='.$encodedAddress.'&sensor=false&language=ru');
        $res = json_decode($resJson, true);
        $coords = $res['results'][0]['geometry']['location'];

        $productCoords->type = 'array';
        $productCoords->value = [$coords['lat'], $coords['lng']];

        return $productCoords;

    }

    public function getContent() {

        $pathTemplats = [
            'layout' => __DIR__.'/Path/temp/layout.tpl',
            'item'   => __DIR__.'/Path/temp/item.tpl'
        ];

        $ar = Group::find(['id' => $this->catalog])
            ->get();

        $newPath = NewPath::init($ar);
        $newPath->setTemplates($pathTemplats);
        $this->newPath = $newPath->getHtml();

        $qb = Product::find(['cid' => $this->catalog])
            ->order(['sort ASC']);

        $pagerTemplate = [
            'layout' => __DIR__.'/Pager/temp/layout.tpl',
            'item'   => __DIR__.'/Pager/temp/items.tpl',
        ];

        $pagerHref = $this->url;
        if ($this->catalog) $pagerHref .= '/cat-'.$this->catalog;
        $pagerHref .= '/?page-number=:page';

        $newPager = Pager::init()
            ->pagesQb($qb, 20, $this->pageNumber)
            ->href($pagerHref);

        $this->pager = $newPager->get($pagerTemplate);

        $groups = Group::find(['cid' => $this->catalog])
            ->order('sort ASC')
            ->getAll();

        $data = [
            'catalogs' => $groups,
            'products' => $qb->getAll()
        ];

        return $this->render(__DIR__.'/temp/default.tpl', $data);

    }

    public function getSettingsContent($name = '', Array $permitions) {

        return $this->render(__DIR__.'/temp/settings.tpl', $permitions);

    }

}
