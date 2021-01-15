<?php
namespace API\Catalog\V2\Factory\Search;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Model\Catalog\Product;

class Controller extends Response
{
    public function getRoutes() {
		$group = Router::group();
        
		$group->addGet('/', [$this, 'find']);

		return $group;
    }

    public function find($req){
    	$query = $_GET['query'];
    	$finded = Product::find('`title` LIKE :query', ['query' => '%'.$query.'%'])
    		->limit(10)
    		->getAll();

		foreach ($finded as $value) {
			$result[] = ['title'=> $value->title, 'chpu' => $value->getRef()];
		}

        $this->hal($result);
	}
}