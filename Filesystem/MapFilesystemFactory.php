<?php

namespace Oryzone\Bundle\MediaStorageBundle\Filesystem;

use Knp\Bundle\GaufretteBundle\FilesystemMap;

use Oryzone\MediaStorage\Filesystem\FilesystemFactoryInterface,
    Oryzone\MediaStorage\Exception\InvalidArgumentException;

class MapFilesystemFactory implements FilesystemFactoryInterface
{

    /**
     * @var \Knp\Bundle\GaufretteBundle\FilesystemMap $map
     */
    protected $map;

    /**
     * Constructor
     *
     * @param \Knp\Bundle\GaufretteBundle\FilesystemMap $map
     */
    public function __construct(FilesystemMap $map)
    {
        $this->map = $map;
    }

    /**
     * {@inheritDoc}
     */
    public function get($filesystemName)
    {
        try
        {
            return $this->map->get($filesystemName);
        }
        catch(\InvalidArgumentException $e)
        {
            throw new InvalidArgumentException(sprintf('Cannot find a filesystem named "%s"', $filesystemName), 0, $e);
        }
    }
}
