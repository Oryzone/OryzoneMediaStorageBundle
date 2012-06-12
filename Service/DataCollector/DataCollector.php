<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector as BaseDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Oryzone\Bundle\MediaStorageBundle\Service\CachedMediaStorage;

class DataCollector extends BaseDataCollector
{

    protected static $IMAGES = array('jpg', 'jpeg', 'gif', 'png');

    protected $cachedMediaStorage;

    public function __construct( CachedMediaStorage $cachedMediaStorage )
    {
        $this->cachedMediaStorage = $cachedMediaStorage;
    }

    public function getName()
    {
        return 'media_storage';
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request    $request   A Request instance
     * @param Response   $response  A Response instance
     * @param \Exception $exception An Exception instance
     *
     * @api
     */
    function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $located = $this->cachedMediaStorage->getLocated();
        foreach ($located as $key => $loc)
            $located[$key]['isImage'] = in_array(pathinfo($loc['path'], PATHINFO_EXTENSION), self::$IMAGES);

        $this->data = array(
            'cacheHits' => $this->cachedMediaStorage->getCacheHits(),
            'located'   => $located,
            'stored'    => $this->cachedMediaStorage->getStored()
        );
    }

    public function getCacheHits()
    {
        return $this->data['cacheHits'];
    }

    public function getLocated()
    {
        return $this->data['located'];
    }

    public function getStored()
    {
        return $this->data['stored'];
    }

}
