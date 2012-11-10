<?php

namespace Oryzone\Bundle\MediaStorageBundle\Variant;

use Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

/**
 * Tree structure to process variants following a hierarchical logic
 */
class VariantTree implements \IteratorAggregate
{

    protected $root;

    protected $nodes;

    /**
     * Constructor. Build a new tree
     *
     * @param VariantNode $root
     */
    public function __construct(VariantNode $root = NULL)
    {
        $this->root = $root;
        $this->nodes = array();
    }

    /**
     * Gets the root node
     *
     * @return VariantNode
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Adds a node
     *
     * @param  VariantInterface $content
     * @param  string|null      $parentName
     * @return VariantNode
     */
    public function addNode(VariantInterface $content, $parentName = NULL)
    {
        $node = new VariantNode($content);
        if ($parentName) {
            $parent = $this->getNode($parentName);
            $node->setParent($parent);
            $parent->addChild($node);
        } else {
            $this->root = $node;
        }
        $this->nodes[$content->getName()] = $node;

        return $node;
    }

    /**
     * Gets a node with a given name
     *
     * @param $name
     * @return mixed
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException
     */
    public function getNode($name)
    {
        if(!isset($this->nodes[$name]))
            throw new InvalidArgumentException(sprintf('Cannot find node named "%s" in variant tree', $name));

        return $this->nodes[$name];
    }

    /**
     * Performs a Depth First Visit on the tree calling a function on each node of the visit.
     * The function is called with the current node and the current level
     *
     * @param callable $function
     */
    public function visit(\Closure $function)
    {
        $this->visit_recursive($this->root, $function);
    }

    /**
     * Starts a depth first visit (recursive)
     */
    protected function visitForIterator()
    {
        $visit = array();
        $this->visit(function(VariantNode $node, $level) use (&$visit){
            $visit[] = $node->getContent();
        });
        return $visit;
    }

    /**
     * Performs recursive visit from a node
     *
     * @param VariantNode $node
     */
    protected function visit_recursive(VariantNode $node, \Closure $function = NULL, $level = 0)
    {
        if ($node !== NULL) {
            if($function)
                $function($node, $level);
            foreach($node->getChildren() as $child)
                $this->visit_recursive($child, $function, $level +1);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->visitForIterator());
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        // used for debug
        return $this->__toStringRecursive($this->root);
    }

    protected function __toStringRecursive(VariantNode $node, $level = 0, $string = '')
    {
        if ($node !== NULL) {
            if(!empty($string))
                $string .= "\n";
            for($i = 0; $i < $level; $i++)
                $string .= "\t";
            $string .= $node->getContent()->getName();

            foreach($node->getChildren() as $child)
                $string = $this->__toStringRecursive($child, $level + 1, $string);
        }

        return $string;
    }

}
