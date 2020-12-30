<?php

namespace Back\SiteSettings\API\V1;

use Websm\Framework\Router\Router;
use Websm\Framework\Exceptions;

use Websm\Framework\Di;

use Rs\Json\Patch;
use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;

class Controller extends \Websm\Framework\Response {

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $routes = [
                'self' => Router::byName('site-settings:api:v1')
                    ->getAbsolutePath(),
                'settings' => Router::byName('site-settings:api:v1:settings')
                    ->getAbsolutePath(),
            ];

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'settings' => [
                        'href' => $origin . $routes['settings'],
                    ],
                ],
            ]);
        })->setName('site-settings:api:v1');

        $group->addGet('/settings', [$this, 'getSettings'])
            ->setName('site-settings:api:v1:settings');
        $group->addPost('/settings', [ $this, 'appendSetting' ]);

        $group->addGet('/settings/:name', [$this, 'getSetting'])
            ->setName('site-settings:api:v1:setting');
        $group->add('PATCH', '/settings/:name/content', [ $this, 'updateSettingContent' ])
            ->setName('site-settings:api:v1:setting:content');

        $group->addDelete('/settings/:name', [ $this, 'removeSetting' ]);

        $group->addAll('/', [$this, 'notFound'], [ 'end' => false ]);

        return $group;
    }

    public function notFound($req, $next) {

        $this->code(404);
        $this->json([
            'errors' => [
                [ 'message' => 'route not found' ],
            ],
        ]);

    }

    public function getSettings($req, $next) {

        try {

            $items = \Model\Setting::find()
                ->getAll();

            $this->hal(Factory\HAL\Settings::get([
                'items' => $items,
            ]));

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
            ]);
        } 
    }

    public function getSetting($req, $next) {

        try {

            $setting = \Model\Setting::find(['name' => $req['id']])
                ->get();

            if ($setting->isNew())
                throw new Exceptions\HTTP('setting not found', 404);

            $this->hal(Factory\HAL\Setting::get([
                'item' => $setting,
            ]));

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
            ]);
        } 
    }

    public function appendSetting($req, $next) {

        try {

            $body = json_decode(file_get_contents('php://input'));

            if (!property_exists($body, 'type'))
                throw new Exceptions\HTTP('no type specified', 422);

            $type = trim($body->type);
            if (!$type || ! is_string($type))
                throw new Exceptions\HTTP('invalid type specified', 422);

            if (!property_exists($body, 'name'))
                throw new Exceptions\HTTP('no name specified', 422);

            $name = trim($body->name);
            if (!$name || ! is_string($name))
                throw new Exceptions\HTTP('invalid name specified', 422);

            $setting = \Model\Setting::find(['name' => $name])
                ->get();

            if (!$setting->isNew())
                throw new Exceptions\HTTP('setting exist', 409);

            $setting->name = $name;
            $setting->type = $type;

            $setting->content = json_encode((Object)null);

            if ($setting->save()) {

                $this->code(201);

                $id = $setting->getDb()->lastInsertId();
                $item = \Model\Setting::find(['id' => $id])
                    ->get();

                $this->hal(Factory\HAL\Setting::get([
                    'item' => $item,
                ]));

            } throw new Exceptions\HTTP('unable to save setting', 500);

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
            ]);
        } 
    }

    public function updateSettingContent($req, $next) {

        try {

            $setting = \Model\Setting::find(['name' => $req['name']])
                ->get();

            if ($setting->isNew())
                throw new Exceptions\HTTP('setting not found', 404);

            $doc = json_encode((Object)json_decode($setting->content));

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

            $setting->content = $patchedDoc;

            if ($setting->save()) die();
            else throw new Exceptions\HTTP('unable to save setting', 500);

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
            ]);
        } 
    }

    public function removeSetting($req, $next) {

        try {
            var_dump($req['name']);

            $item = \Model\Setting::find(['name' => $req['name']])
                ->get();

            if ($item->isNew())
                throw new Exceptions\HTTP('setting not found', 404);

            if ($item->delete()) {

                $this->code(204);
                die();

            } else throw new Exceptions\HTTP('unable to delete setting', 500);

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
            ]);
        } 
    }
}
