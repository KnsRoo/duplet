<?php

namespace API\Favorites;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Exceptions\HTTP as HTTPException;
use Websm\Framework\Di;

use Model\Catalog\Favorite;

class Controller extends Response {
	private $userId = null;

	public function getRoutes() {

        $group = Router::group();

        $group->addAll('/', [ $this, 'parseJWT' ], [ 'end' => false ])
            ->setName('api:favorites');

        $group->addDelete('/:id', [ $this, 'removeFavorite'])
            ->setName('api:favorites:remove');

        $group->addGet('/', [$this, 'getFavorites'])
            ->setName('api:favorites:get');

        $group->addPost('/', [$this, 'appendFavorite'])
            ->setName('api:favorites:add');

        return $group;
    }

    public function parseJWT($req, $next) {

        $jwt = $_GET['jwt'] ?? '';
        $client = new JWTClient;

        try {

            $payload = '';
            try {

                $payload = $client->decodeToken($jwt);
            } catch (HTTPException $e) {

                throw new HTTPException('authorization failed', 409);
            }

            $payload = json_decode($payload);
            if ($payload->site !== $_SERVER['HTTP_HOST'])
                throw new HTTPException('authorization failed', 409);

            $user = \Model\User::find(['id' => $payload->userId])
                ->get();

            if ($user->isNew())
                throw new HTTPException('user not found', 404);

            $this->user = $user;
            $next();

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());

            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }

    public function appendFavorite(){

        try {

            $body = file_get_contents('php://input');
            $bodyArr = (Object)json_decode($body);

            $record = Favorite::find(['product_id' => $bodyArr->id])
                ->andWhere(['user_id' => $this->user->id])
                ->get();

            if ($record->isNew()){
                $favorite = new Favorite;
                $favorite->user_id = $this->user->id;
                $favorite->product_id = $bodyArr->id;

                if (!$favorite->save())
                    throw new HTTPException('Не удалось сохранить продукт', 500);
            } else {
                throw new HTTPException('Товар уже существует', 422);
            }

            $this->code(200);

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function removeFavorite($req){

        try {
 
            $favorite = Favorite::find(['product_id' => $req['id']])
                ->andWhere(['user_id' => $this->user->id])
                ->get();

            if (!$favorite->delete())
                throw new HTTPException('Не удалось удалить запись', 500);

            $this->code(200);

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function getFavorites(){

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrder();

        $qb = Favorite::find([ 'user_id' => $this->user->id ]);

        $qbCnt = clone $qb;

        $favs = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Favorites::get([
            'items' => $favs,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

}