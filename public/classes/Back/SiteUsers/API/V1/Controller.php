<?php

namespace Back\SiteUsers\API\V1;

use Websm\Framework\Router\Router;
use Websm\Framework\Response;

use Websm\Framework\Di;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Router\Request\Query;
use Websm\Framework\Mail\HTMLMessage;

use Rs\Json\Patch;
use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;

use Model\User;

class Controller extends Response {

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next){
             $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $routes = [
                'self' => Router::byName('api:siteusers:v1')
                    ->getAbsolutePath(),
                'users' => Router::byName('api:siteusers:v1:users')
                    ->getAbsolutePath(),
                'groups' => Router::byName('api:siteusers:v1:groups')
                    ->getAbsolutePath(),
                'tags' => Router::byName('api:siteusers:v1:tags')
                    ->getAbsolutePath(),
            ];

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'users' => [
                        'href' => $origin . $routes['users'],
                    ],
                    'groups' => [
                        'href' => $origin . $routes['groups'],
                    ],
                    'tags' => [
                        'href' => $origin . $routes['tags'],
                    ],
                ],
            ]);
        })->setName('api:siteusers:v1');

        $group->addGet('/users', [ $this, 'getUsers' ])
            ->setName('api:siteusers:v1:users');

        $group->addGet('/users/:id', [ $this, 'getUser' ])
            ->setName('api:siteusers:v1:user');

        $group->addGet('/groups', [ $this, 'getGroups' ])
            ->setName('api:siteusers:v1:groups');

        $group->addPost('/users/:id', [ $this, 'appendUser' ]);

        $group->add('PATCH', '/users/:id/groups', [$this, 'updateGroups']);

        $group->add('PATCH', '/users/:id/props', [$this, 'patchUserProps'])
            ->setname('api:siteusers:v1:user:props');

        $group->addDelete('/users/:id', [$this, 'removeUser']);

        $group->addGet('/tags', [ $this, 'getTags' ])
            ->setName('api:siteusers:v1:tags');

        return $group;
    }

    public function getGroups(){

        $groups = \Model\Catalog\Group::find()
            ->order('id')
            ->getAll();

        $groupsArr = [];

        foreach($groups as $group){
            $groupsArr[$group->id] = $group->title;
        }

        $this->hal($groupsArr);

    }

    public function getUsers($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();

        $qb = User::find();
        $qbCnt = clone $qb;

        $users = $qb->order('`id`DESC')
            ->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Users::get([
            'items' => $users,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);

    }

    public function getUser($req, $next) {

        try {

            $user = User::find(['id' => $req['id']])
                ->get();

            if ($user->isNew())
                throw new HTTPException('user not found', 404);

            $result = Factory\HAL\User::get([ 'item' => $user ]);
            $this->hal($result);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }

    public function appendUser($req){
        //Не доделано!
        //
        //
        try {
            
            $user = new User;

            $body = json_decode(file_get_contents('php://input'), true);

            $this->hal($body);

        }
        catch (HTTPException $e){
            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }

    }

    public function updateGroups($req){
        
        try {

            $user = User::find([ 'id' => $req['id'] ])
                ->get();

            if ($user->isNew())
                throw new HTTPException('Пользователь не найден', 404);

            $body = json_decode(file_get_contents('php://input'), true);
            
            $groupsIds = &$body['groupsIds'];
            $chainsIds = &$body['chainsIds'];

            $props = json_decode($user->props, true);

            $props['groupsIds'] = $groupsIds;
            $props['chainsIds'] = $chainsIds;

            $user->props = json_encode($props);

            if(!$user->save()){
                throw new HTTPException('Не удалось сохранить пользователя', 500);
            }

            $this->hal(['message' => 'User saved']);           

        }
        catch (HTTPException $e){
            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }

    }

    public function removeUser($req){
        
        try {

            $user = User::find(['id' => $req['id']])
                ->get();

            if ($user->isNew())
                throw new HTTPException('user not found', 404);

            $user->delete();
            $this->code(204);
            die();

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }

    }

    private function getLastChildGroup(Array $group){
        $buff = [];
        foreach($group as $g){
            $subGroups = \Model\Catalog\Group::find([ 'cid' => $g->id ])
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

        $pGroup = \Model\Catalog\Group::find([ 'id' => $group->cid ])
            ->get();
        if($pGroup->id === $endId)
            return trim($gPath);
        else
            return $this->getGroupPath($pGroup, $pGroup->title."/".$gPath);

    }

    public function patchUserProps($req, $next) {

        try {

            $user = \Model\User::find(['id' => $req['id']])
                ->get();

            if ($user->isNew())
                throw new HTTPException('user not found', 404);

            $props = (Object)json_decode($user->props);

            $doc = json_encode($props);

            $patchDoc = file_get_contents('php://input');

            $patchedDoc = '';

            try {
                $patch = new Patch($doc, $patchDoc);
                $patchedDoc = $patch->apply();
            } catch (InvalidPatchDocumentJsonException $e) {
                throw new HTTPException($e->getMessage(), 422);
            } catch (InvalidTargetDocumentJsonException $e) {
                throw new HTTPException($e->getMessage(), 500);
            } catch (InvalidOperationException $e) {
                throw new HTTPException($e->getMessage(), 422);
            }

            $user->props = $patchedDoc;

            if ($user->save()) die();
            else throw new HTTPException('unable to save user', 500);

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (HTTPException $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function getTags($req, $next) {

        try {

            $offset = Factory\QueryParams::getOffset();
            $limit = Factory\QueryParams::getLimit();

            $qb = \Model\Tags\Tags::find();
            $qbCnt = clone $qb;

            $tags = $qb->limit([ $offset, $limit ])
                ->getAll();

            $total = (Integer)$qbCnt->count();

            $result = Factory\HAL\Tags::get([
                'items' => $tags,
                'offset' => $offset,
                'limit' => $limit,
                'total' => $total,
            ]);

            $this->hal($result);

        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }
}
