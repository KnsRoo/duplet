<?php

namespace API\DiscountCards\V1;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Model\Page;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Di;

class Controller extends Response {

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => Router::byName('api:discount-cards:v1')
                            ->getURL(),
                    ],
                    'cards' => [
                        'href' => Router::byName('api:discount-cards:v1:cards')
                            ->getURL(),
                    ],
                ],
            ]);
        })->setName('api:discount-cards:v1');

        $group->addGet('/cards', [$this, 'getCards'])
            ->setName('api:discount-cards:v1:cards');

        $group->addGet('/cards/:id', [$this, 'getCard'])
            ->setName('api:discount-cards:v1:card');

        $group->addGet('/doc/rels/:rel', [$this, 'getRelDoc']);

        return $group;
    }

    public function getCards($req, $next) {

        $code = Factory\QueryParams::getCode();
        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();

        try {

            $qb = \Model\Card::find();

            if ($code !== null)
                $qb = $qb->andWhere([ 'code' => $code ]);

            $qbCnt = clone $qb;

            $cards = $qb->limit([ $offset, $limit ])
                ->getAll();

            $total = (Integer)$qbCnt->count();

            $response = Factory\HAL\Cards::get([
                'items' => $cards,
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
            ]);

            $this->hal($response);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }
    }

    public function getCard($req, $next) {

        try {

            $card = \Model\Card::find(['id' => $req['id']])
                ->get();

            if ($card->isNew())
                throw new HTTPException('card not found', 404);

            $response = Factory\HAL\Card::get([
                'item' => $card,
            ]);

            $this->hal($response);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }
    }
}
