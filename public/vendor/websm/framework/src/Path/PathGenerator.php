<?php

namespace Websm\Framework\Path;

class PathGenerator implements PathGeneratorInterface {

    private $provider;
    private $queue;

    public function __construct(PathProviderInterface $provider) {

        $this->provider = $provider;
        $this->queue = new PathQueue;

    }

    private function progress(PathProviderInterface $provider) {

        $item = new PathItem(
            $provider->getTitle(),
            $provider->getRef()
        );

        $this->queue->push($item);

        if ($provider->isRoot()) return;

        $this->progress($provider->getParent());

    }

    public function genQueue() {

        $this->progress($this->provider);
        return $this->queue->reverse();

    }

}
