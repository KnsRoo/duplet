<?php

namespace Back\Pages\API\V1;

use Websm\Framework\Router\Router;

use Core\Users;

use Websm\Framework\Di;

use Rs\Json\Patch;
use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;

use Websm\Framework\Exceptions;

class Controller extends \Websm\Framework\Response
{

    const FORCED_SCHEME = 'http';
    const BASE_PATH = '/admin/pages/api/v1';

    public function __construct()
    {
        /* $this->origin = self::FORCED_SCHEME . '://'. $_SERVER['SERVER_NAME']; */
        $this->origin = '';
        $this->basePath = $this->origin . self::BASE_PATH;
    }

    public function getRoutes()
    {
        $group = Router::group();

        $group->addGet('/', function ($req, $next) {

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $this->origin . $_SERVER['REQUEST_URI'],
                    ],
                    'pages' => [
                        'href' => $this->basePath . "/pages",
                    ],
                ],
            ]);
        });

        $group->addGet('/pages', [$this, 'getPages']);
        $group->addGet('/pages/:id', [$this, 'getPage']);
        $group->addGet('/pages/:id/props', [$this, 'getProps']);
        $group->addPost('/pages/:id/props', [$this, 'createProp']);
        $group->addGet('/pages/:id/props/:prop', [$this, 'getProp']);
        $group->add('PATCH', '/pages/:id/props/:prop', [$this, 'updateProp']);
        $group->addPut('/pages/:id/props/:prop', [$this, 'replaceProp']);
        $group->addDelete('/pages/:id/props/:prop', [$this, 'removeProp']);

        $group->addGet('/tags', [$this, 'getTags'])
            ->setName('page:api:v1:tags');

        $group->addGet('/tags/:id', [$this, 'getTag'])
            ->setName('page:api:v1:tag');

        $group->addAll('/', [$this, 'notFound'], ['end' => false]);

        return $group;
    }

    public function notFound($req, $next)
    {
        $this->code(404);
        $this->json([
            'errors' => [
                ['message' => 'route not found'],
            ],
        ]);
    }

    public function getProps($req, $next)
    {
        try {
            $page = \Model\Page::find(['id' => $req['id']])
                ->get();

            if ($page->isNew())
                throw new Exceptions\HTTP('Страница не найдена', 404);

            $props = json_decode($page->props);

            if (!is_object($props))
                $props = (object) [];

            $this->hal([
                '_links' => Factory\HAL\Props::getLinks([
                    'origin' => $this->origin,
                    'basePath' => $this->basePath,
                    'pageId' => $req['id'],
                    'items' => $props,
                ]),
                '_embedded' => Factory\HAL\Props::getEmbedded([
                    'origin' => $this->origin,
                    'basePath' => $this->basePath,
                    'pageId' => $req['id'],
                    'items' => $props,
                ]),
            ]);
        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function createProp($req, $next)
    {
        try {

            $page = \Model\Page::find(['id' => $req['id']])
                ->get();

            if ($page->isNew())
                throw new Exceptions\HTTP('Страница не найден', 404);

            $body = json_decode(file_get_contents('php://input'));

            if (!property_exists($body, 'type'))
                throw new Exceptions\HTTP('Тип не задан', 422);

            $type = trim($body->type);
            if (!is_string($type) || !$type)
                throw new Exceptions\HTTP('Не корректный тип', 422);

            if (!property_exists($body, 'name'))
                throw new Exceptions\HTTP('Имя не задано', 422);

            $name = trim($body->name);
            if (!$name || !is_string($name))
                throw new Exceptions\HTTP('Не корректное имя свойства', 422);

            $props = (object) json_decode($page->props);

            if (property_exists($props, $name))
                throw new Exceptions\HTTP('Свойство существует', 409);

            $props->$name = (object) null;
            $props->$name->type = $type;

            $page->props = json_encode($props);

            if ($page->save()) {

                \Model\Journal::add(
                    \Model\Journal::STATUS_NOTICE,
                    '<b>' . Users::get()->login . '</b> создал свойство "' . $name . '" товара "' . $page->title . '".',
                    'Каталог'
                );

                $this->code(201);
                $this->hal([
                    'name' => $name,
                    'content' => [
                        'type' => $props->$name->type,
                    ],
                    '_links' => Factory\HAL\Prop::getLinks([
                        'origin' => $this->origin,
                        'basePath' => $this->basePath,
                        'pageId' => $req['id'],
                        'name' => $name,
                        'item' => $props->$name,
                    ]),
                    '_embedded' => Factory\HAL\Prop::getEmbedded([
                        'origin' => $this->origin,
                        'basePath' => $this->basePath,
                        'pageId' => $req['id'],
                        'name' => $name,
                        'item' => $props->$name,
                    ]),
                ]);
            } else throw new Exceptions\HTTP('Не удалось сохранить продукт', 500);
        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function getProp($req, $next)
    {
        try {

            $page = \Model\Page::find(['id' => $req['id']])
                ->get();

            if ($page->isNew())
                throw new Exceptions\HTTP('Страница не найден', 404);

            $props = json_decode($page->props);
            $name = $req['prop'];

            if ($props == null)
                $props = (object) [];

            if (!isset($props->$name))
                throw new Exceptions\HTTP('Свойство не найдено', 404);

            $this->hal([
                'name' => $name,
                'content' => $props->$name,
                '_links' => Factory\HAL\Prop::getLinks([
                    'origin' => $this->origin,
                    'basePath' => $this->basePath,
                    'pageId' => $req['id'],
                    'name' => $name,
                    'item' => $props->$name,
                ]),
                '_embedded' => Factory\HAL\Prop::getEmbedded([
                    'origin' => $this->origin,
                    'basePath' => $this->basePath,
                    'pageId' => $req['id'],
                    'name' => $name,
                    'item' => $props->$name,
                ]),
            ]);
        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function updateProp($req, $next)
    {
        try {

            $page = \Model\Page::find(['id' => $req['id']])
                ->get();

            if ($page->isNew())
                throw new Exceptions\HTTP('Страница не найден', 404);

            $name = $req['prop'];

            $props = json_decode($page->props);

            if (!property_exists($props, $name))
                throw new Exceptions\HTTP('Свойство не найдено', 404);

            $prop = $props->$name;
            $doc = json_encode($prop);

            $patchDoc = file_get_contents('php://input');

            $patchedDoc = '';

            try {
                $patch = new Patch($doc, $patchDoc);
                $patchedDoc = $patch->apply();
            } catch (InvalidPatchDocumentJsonException $e) {
                throw new Exceptions\HTTP($e->getMessage(), 422);
            } catch (InvalidTargetDocumentJsonException $e) {
                throw new Exceptions\HTTP($e->getMessage(), 500);
            } catch (InvalidOperationException $e) {
                throw new Exceptions\HTTP($e->getMessage(), 422);
            }

            $patchedDoc = json_decode($patchedDoc);

            if (!property_exists($patchedDoc, 'type'))
                throw new Exceptions\HTTP('Не корректный тип свойства', 422);

            if (!$patchedDoc->type || !is_string($patchedDoc->type))
                throw new Exceptions\HTTP('Не корректный тип свойства', 422);

            $props->$name = $patchedDoc;
            $page->props = json_encode($props);

            if ($page->save()) {

                \Model\Journal::add(
                    \Model\Journal::STATUS_NOTICE,
                    '<b>' . Users::get()->login . '</b> обновил свойство "' . $name . '" страницы "' . $page->title . '".',
                    'Страница'
                );
                die();
            } else throw new Exceptions\HTTP('Не удалось сохранить страницу', 500);
        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function removeProp($req, $next)
    {
        try {

            $page = \Model\Page::find(['id' => $req['id']])
                ->get();

            if ($page->isNew())
                throw new Exceptions\HTTP('Страница не найден', 404);

            $name = $req['prop'];

            $props = json_decode($page->props);

            if (!property_exists($props, $name))
                throw new Exceptions\HTTP('Свойство не найдено', 404);

            unset($props->$name);

            $page->props = json_encode($props);

            if ($page->save()) {

                \Model\Journal::add(
                    \Model\Journal::STATUS_NOTICE,
                    '<b>' . Users::get()->login . '</b> удалил свойство "' . $name . '" страницу "' . $page->title . '".',
                    'Страница'
                );

                $this->code(204);
                die();
            } else throw new Exceptions\HTTP('Не удалось удалить свойство', 500);
        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function getPages($req, $next)
    {
        $offset = QueryParams::getOffset();
        $limit = QueryParams::getLimit();
        $order = QueryParams::getOrderPages();
        $props = QueryParams::getProps();
        $tags = QueryParams::getTags();

        $qb = \Model\Page::find();

        if (isset($_GET['query']))
            $qb = Factory\Filters\QB::filterQuery($qb, $_GET['query']);

        $qb = Factory\Filters\QB\Props::filter($qb, $props);
        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);

        $qbCnt = clone $qb;

        $pages = $qb->order($order)
            ->limit([$offset, $limit])
            ->getAll();

        $result = [];

        foreach ($pages as $page) {

            $picSize = 500;
            $picture = $page->getPicture($picSize . 'x' . $picSize);
            $tags = explode(':', trim($page->tags, ':'));
            if ($tags[0] === "") $tags = [];

            $result[] = [
                'id' => (string) $page->id,
                'title' => (string) $page->title,
                'pageRef' => $this->origin . $page->chpu,
                'announce' => $page->announce,
                'icon' => $page->icon,
                'text' => (string) $page->text,
                'picture' => $picture,
                'tags' => $tags,
                'creationDate' => $page->date,
                'sort' => (string) $page->sort,
                'props' => json_decode($page->props),
            ];
        }

        $total = (int) $qbCnt->count();
        $this->hal([
            '_embedded' => Factory\HAL\Pages::getEmbedded([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'items' => $result,
            ]),
            '_links' => Factory\HAL\Pages::getLinks([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
            ]),
            'offset' => $offset,
            'limit' => $limit,
            'size' => (int) count($pages),
            'total' => $total,
        ]);
    }

    public function getPage($req, $next)
    {
        $page = \Model\Page::find(['id' => $req['id']])
            ->get();

        $picSize = 500;
        $picture = $page->getPicture($picSize . 'x' . $picSize);

        $result[] = [
            'id' => (string) $page->id,
            'title' => (string) $page->title,
            'pageRef' => $this->origin . $page->chpu,
            'announce' => $page->announce,
            'icon' => $page->icon,
            'text' => (string) $page->text,
            'picture' => $picture,
            'tags' => $tags,
            'creationDate' => $page->date,
            'sort' => (string) $page->sort,
            'props' => json_decode($page->props),
        ];

        $result['_embedded'] = Factory\HAL\Page::getEmbedded([
            'origin' => $this->origin,
            'baseUrl' => $this->baseUrl,
            'item' => $result,
        ]);

        $result['_links'] = Factory\HAL\Page::getLinks([
            'origin' => $this->origin,
            'baseUrl' => $this->baseUrl,
            'item' => $result,
        ]);

        $this->hal($result);
    }

    public function getTags($req, $next)
    {
        $offset = QueryParams::getOffset();
        $limit = QueryParams::getLimit();

        $qb = \Model\Tags\Tags::find();
        $qbCnt = clone $qb;
        $qb = $qb->limit([$offset, $limit]);

        $tags = $qb->getAll();
        $total = $qbCnt->count();

        $result = Factory\HAL\Tags::get([
            'items' => $tags,
            'total' => $total,
            'offset' => $offset,
            'limit' => $limit,
        ]);

        $this->hal($result);
    }

    public function getTag($req, $next)
    {
        $tag = \Model\Tags\Tags::find(['id' => $req['id']])
            ->get();

        $result = Factory\HAL\Tag::get([
            'item' => $tag,
        ]);

        $this->hal($result);
    }
}
