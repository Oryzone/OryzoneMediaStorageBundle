<?php

namespace Oryzone\Bundle\MediaStorageBundle\Persistence;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Doctrine\Common\Persistence\ObjectManager;

use Oryzone\MediaStorage\Persistence\PersistenceAdapterInterface,
    Oryzone\MediaStorage\Exception\PersistenceException,
    Oryzone\MediaStorage\Model\MediaInterface;

class DoctrinePersistenceAdapter implements PersistenceAdapterInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    protected $objectManager;

    /**
     * @var bool $autoFlush
     */
    protected $autoFlush;

    /**
     * Constructor
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param bool                                       $autoFlush     if <code>TRUE</code> will auto-flush the object manager after each change
     */
    public function __construct(ObjectManager $objectManager, $autoFlush = TRUE)
    {
        $this->objectManager = $objectManager;
        $this->autoFlush = $autoFlush;
    }

    protected function callObjectManager(MediaInterface $media, $method = 'save')
    {
        try {
            if($method == 'remove')
                $this->objectManager->remove($media);
            else
                $this->objectManager->persist($media);

            if($this->autoFlush)
                $this->objectManager->flush();
        } catch (\Exception $e) {
            throw new PersistenceException(sprintf('Cannot %s media "%s": %s', $method, $media->__toString(), $e->getMessage()), $this, 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function save(MediaInterface $media)
    {
        $this->callObjectManager($media);
    }

    /**
     * {@inheritDoc}
     */
    public function update(MediaInterface $media)
    {
        $this->callObjectManager($media, 'update');
    }

    /**
     * {@inheritDoc}
     */
    public function remove(MediaInterface $media)
    {
        $this->callObjectManager($media, 'remove');
    }
}
