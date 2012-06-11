<?php

namespace Acne\MediaManager\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oryzone\Bundle\MediaStorageBundle\Entity\Media as BaseMedia;

/**
 * Acne\MediaManager\SiteBundle\Entity\Media
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Media extends BaseMedia
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}