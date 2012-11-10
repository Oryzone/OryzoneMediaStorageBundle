<?php

namespace Oryzone\Bundle\MediaStorageBundle\Variant;

/**
 * Node class for VariantTree
 */
class VariantNode
{

    /**
     * @var VariantNode $parent
     */
    protected $parent;

    /**
     * @var array $children
     */
    protected $children;

    /**
     * @var VariantInterface $content
     */
    protected $content;

    /**
     * Constructor. Build a new Variant Node
     *
     * @param VariantInterface $content
     * @param VariantNode      $parent
     */
    public function __construct(VariantInterface $content, VariantNode $parent = NULL)
    {
        $this->content = $content;
        $this->parent = $parent;
        $this->children = array();
    }

    /**
     * Get the list of children
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Adds a child to the node
     *
     * @param VariantNode $node
     */
    public function addChild(VariantNode $node)
    {
        $this->children[] = $node;
    }

    /**
     * Set the content
     *
     * @param VariantInterface $content
     */
    public function setContent(VariantInterface $content)
    {
        $this->content = $content;
    }

    /**
     * Get the content
     *
     * @return VariantInterface
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the parent node
     *
     * @param VariantNode $parent
     */
    public function setParent(VariantNode $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get the parent node
     *
     * @return VariantNode
     */
    public function getParent()
    {
        return $this->parent;
    }

}
