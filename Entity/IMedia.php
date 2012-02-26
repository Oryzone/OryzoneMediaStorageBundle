<?php

namespace Oryzone\Bundle\MediaStorageBundle\Entity;

public interface IMedia
{
	public function getId();
	public function getMediaName();
	public function getMediaType();
}