<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\DataTransformer;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\Form\DataTransformerInterface;

use Oryzone\MediaStorage\Model\MediaInterface;

class ContextFixerDataTransformer implements DataTransformerInterface
{
    /**
     * @var string $contextName
     */
    protected $contextName;

    /**
     * Constructor
     *
     * @param string $contextName
     */
    public function __construct($contextName)
    {
        $this->contextName = $contextName;
    }

    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($media)
    {
        if (!$media instanceof MediaInterface) {
            return $media;
        }

        if (!$media->getContext())
            $media->setContextName($this->contextName);

        return $media;
    }
}
