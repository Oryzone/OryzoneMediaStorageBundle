<?php

namespace Oryzone\Bundle\MediaStorageBundle\Context;

interface ContextInterface
{

    /**
     * Get the name of the context
     *
     * @return string
     */
    public function getName();

    /**
     * Get the name of the associated provider
     *
     * @return string
     */
    public function getProviderName();

    /**
     * Get the name of the associated filesystem
     *
     * @return string
     */
    public function getFilesystemName();

    /**
     * Get the name of the associated cdn
     *
     * @return string
     */
    public function getCdnName();

    /**
     * Get the name of the associated naming strategy
     *
     * @return string
     */
    public function getNamingStrategyName();

    /**
     * Get the raw array of variants
     *
     * @return string
     */
    public function getVariants();

    /**
     * Builds the variant tree used for processing
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Variant\VariantTree
     */
    public function buildVariantTree();
}
