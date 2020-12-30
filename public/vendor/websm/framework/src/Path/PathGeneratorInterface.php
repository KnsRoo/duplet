<?php

namespace Websm\Framework\Path;

/**
 * PathGeneratorInterface
 *
 * @package 
 * @author Bykov Igor <con29rus@live>
 */
interface PathGeneratorInterface {

    /**
     * Constructor
     *
     * @param PathProviderInterface $provider
     * @access public
     */
    public function __construct(PathProviderInterface $provider);

    /**
     * genQueue
     *
     * Создает очередь пути.
     *
     * @access public
     * @return PathQueueInterface;
     */
    public function genQueue();

}
