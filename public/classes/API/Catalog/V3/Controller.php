<?php

namespace API\Catalog\V3;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Model\Catalog\Group;
use Model\Catalog\Product;
use Model\Catalog\Structure;
use Model\Catalog\Childs;

use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Di;

class Controller extends Response {

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', [ $this, 'default' ])
              ->setName('api:catalog:v2');

        $group->addGet('/base/products', [$this, 'getBaseProducts'])
            ->setName('api:catalog:v2:base-products');

        $group->addGet('/base/groups', [$this, 'getBaseGroups'])
            ->setName('api:catalog:v2:base-groups');

        $group->addGet('/groups', [$this, 'getGroups'])
            ->setName('api:catalog:v2:groups');

        $group->addGet('/struct', [$this, 'getStructure'])
            ->setName('api:catalog:v2:structure');

        $group->addGet('/groups/:id', [$this, 'getGroup'])
            ->setName('api:catalog:v2:group');

        $group->addGet('/groups/:id/subgroups', [$this, 'getSubgroups'])
            ->setName('api:catalog:v2:subgroups');

        $group->addGet('/groups/:id/subproducts', [$this, 'getAllSubproducts'])
            ->setName('api:catalog:v2:subproducts');

        $group->addGet('/products', [$this, 'getProducts'])
            ->setName('api:catalog:v2:products');

        $group->addGet('/products/:id', [$this, 'getProduct'])
            ->setName('api:catalog:v2:product');

        $group->addGet('/search/:query', [$this, 'getFound'])
            ->setName('api:catalog:v2:search');

        $group->addGet('/doc/rels/:rel', [$this, 'getRelDoc'])
            ->setName('api:catalog:v2:docs');

        return $group;
    }

    public function default($req, $next) {

        $this->hal([
            '_links' => [
                'self' => [
                    'href' => Router::byName('api:catalog:v2')->getURL(),
                ],
                'groups' => [
                    'href' => Router::byName('api:catalog:v2:groups')->getURL(),
                ],
                'products' => [
                    'href' => Router::byName('api:catalog:v2:products')->getURL(),
                ],
                'base-groups' => [
                    'href' => Router::byName('api:catalog:v2:base-groups')->getURL(),
                ],
                'base-products' => [
                    'href' => Router::byName('api:catalog:v2:base-products')->getURL(),
                ],
            ],
        ]);
    }

    public function getGroups($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $tags = Factory\QueryParams::getTags();
        $order = Factory\QueryParams::getOrder();

        $qb = Group::find(['visible' => true]);

        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);
        $qb = Factory\Filters\QB\OrderGroups::filter($qb, $order);
        $qbCnt = clone $qb;

        $groups = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Groups::get([
            'items' => $groups,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getBaseProducts($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrder();
        $tags = Factory\QueryParams::getTags();
        $props = Factory\QueryParams::getProps();
        $query = Factory\QueryParams::getQuery();

        $qb = Product::find([ 'cid' => null, 'visible' => true ]);

        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);
        $qb = Factory\Filters\QB\Props::filter($qb, $props);
        $qb = Factory\Filters\QB\Query::filter($qb, $query);
        $qb = Factory\Filters\QB\OrderProducts::filter($qb, $order);

        $qbCnt = clone $qb;

        $products = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();
        $result = Factory\HAL\Products::get([
            'items' => $products,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getBaseGroups($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $tags = Factory\QueryParams::getTags();
        $order = Factory\QueryParams::getOrder();

        $qb = Group::find(['cid' => null, 'visible' => true]);

        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);
        $qb = Factory\Filters\QB\OrderGroups::filter($qb, $order);
        $qbCnt = clone $qb;

        $groups = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Groups::get([
            'items' => $groups,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getGroup($req, $next) {

        $id = $req['id'];

        $group = Group::find([ 'id' => $id, 'visible' => true, ])
            ->get();

        try {

            if ($group->isNew())
                throw new HTTPException('group not found', 404);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }

        $children = Group::find([ 'cid' => $id, 'visible' => true, ])
            ->getAll();

        $picSize = 1000;
        $picture = $group->getPicture($picSize . 'x' .$picSize);

        $result = Factory\HAL\Group::get(['item' => $group]);
        $this->hal($result);
    }

    public function getSubgroups($req, $next) {

        $groupId = $req['id'];
        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrder();
        $tags = Factory\QueryParams::getTags();

        $qb = Group::find([ 'visible' => true, 'cid' => $groupId ]);

        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);
        $qb = Factory\Filters\QB\OrderGroups::filter($qb, $order);

        $qbCnt = clone $qb;

        $groups = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Groups::get([
            'items' => $groups,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getSubproducts($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrder();
        $tags = Factory\QueryParams::getTags();
        $props = Factory\QueryParams::getProps();
        $query = Factory\QueryParams::getQuery();

        $groupId = $req['id'];

        $group = Group::find([ 'id' => $groupId, 'visible' => true ])
            ->get();

        try {

            if ($group->isNew())
                throw new Exceptions\HTTP('group not found', 404);

        } catch(Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }

        $qb = Product::find([ 'cid' => $groupId, 'visible' => true ]);

        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);
        $qb = Factory\Filters\QB\Props::filter($qb, $props);
        $qb = Factory\Filters\QB\Query::filter($qb, $query);
        $qb = Factory\Filters\QB\OrderProducts::filter($qb, $order);

        $qbCnt = clone $qb;

        $products = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Subproducts::get([
            'group' => $group,
            'items' => $products,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getProduct($req, $next) {

        $id = $req['id'];

        $product = Product::find([ 'id' => $id, 'visible' => true, ])
            ->get();

        try {

            if ($product->isNew())
                throw new HTTPException('product not found', 404);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }

        $result = Factory\HAL\Product::get([
            'item' => $product,
        ]);

        $this->hal($result);

    }

    public function getProducts($req, $next) {

        $qb = Product::find(['visible' => true ]);

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getEasyOrder();
        $sort = Factory\QueryParams::getSort();
        $tags = Factory\QueryParams::getTags();
        $props = Factory\QueryParams::getProps();
        $query = Factory\QueryParams::getQuery();

        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);
        $qb = Factory\Filters\QB\Props::filter($qb, $props);
        $qb = Factory\Filters\QB\Query::filter($qb, $query);
        $qb = Factory\Filters\QB\OrderProducts::easyFilter($qb, $order, $sort);

        $qbCnt = clone $qb;

        $products = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();
        $result = Factory\HAL\Products::get([
            'items' => $products,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getRelDoc($req, $next) {

        $validRels = [ 'groups', 'group', 'subgroups', 'subproducts', 'products', 'product' ];
        $validAccepts = [ 'text/html', 'text/yaml' ];

        $headers = apache_request_headers();
        $accepts = &$headers['Accept'];
        $accepts = (String)$accepts;
        $accepts = explode(',', $accepts);
        $accept = 'text/yaml';

        foreach($accepts as $acceptItem)
            if (in_array($acceptItem, $validAccepts)) {
                $accept = $acceptItem;
                break;
            }

        $rel = $req['rel'];
        if(!in_array($rel, $validRels)) {
            $this->code(404);
            die();
        }

        switch($accept) {
            case 'text/html':
                header('Content-Type: text/html');
                die(file_get_contents(__DIR__ . "/doc/${rel}.html"));
                break;
            case 'text/yaml':
                header('Content-Type: text/yaml');
                die(file_get_contents(__DIR__ . "/doc/${rel}.yml"));
                break;
        }
    }

    private function getChildrenGroups($group,$groups){
        $groups[] = $group->id;
        foreach ($group->getGroups() as $group){
            $groups = $this->getChildrenGroups($group, $groups);
        }
        return $groups;
    }

    public function getAllSubproducts($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getEasyOrder();
        $sort = Factory\QueryParams::getSort();
        $tags = Factory\QueryParams::getTags();
        $props = Factory\QueryParams::getProps();
        $query = Factory\QueryParams::getQuery();

        $groupId = $req['id'];

        $group = Group::find([ 'id' => $groupId, 'visible' => true ])
            ->get();

        try {

            if ($group->isNew())
                throw new Exceptions\HTTP('group not found', 404);

        } catch(Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }

        $childs = Childs::find(['id' => $groupId])
                    ->get();

        $qb = Product::find()
            ->where("`cid` IN ".$childs->childs)
            ->andWhere(['visible' => true]);

        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);
        $qb = Factory\Filters\QB\Props::filter($qb, $props);
        $qb = Factory\Filters\QB\Query::filter($qb, $query);
        $qb = Factory\Filters\QB\OrderProducts::easyFilter($qb, $order, $sort);

        $qbCnt = clone $qb;

        $products = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Subproducts::get([
            'group' => $group,
            'items' => $products,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getSubproductsRecursive($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrder();
        $tags = Factory\QueryParams::getTags();
        $props = Factory\QueryParams::getProps();
        $query = Factory\QueryParams::getQuery();

        $groupId = $req['id'];

        $group = Group::find([ 'id' => $groupId, 'visible' => true ])
            ->get();

        try {

            if ($group->isNew())
                throw new Exceptions\HTTP('group not found', 404);

        } catch(Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }

        $groupslist = [];

        $groupslist = $this->getChildrenGroups($group,$groupslist);
        $qb = Product::find(['cid' => $groupslist])
            ->andWhere(['visible' => true]);

        // $childs = "('".implode("','",$groupslist)."')";



        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);
        $qb = Factory\Filters\QB\Props::filter($qb, $props);
        $qb = Factory\Filters\QB\Query::filter($qb, $query);
        $qb = Factory\Filters\QB\OrderProducts::filter($qb, $order);

        $qbCnt = clone $qb;

        $products = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Subproducts::get([
            'group' => $group,
            'items' => $products,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }
}
