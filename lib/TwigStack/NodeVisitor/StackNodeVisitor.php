<?php
/*
 * This file is part of the TwigStack extension for Twig
 *
 * (c) 2014 Arno Geurts
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */

namespace TwigStack\NodeVisitor;

use TwigStack\Node\StackBodyNode;

/**
 * Class StackNodeVisitor
 * @package TwigStack\NodeVisitor
 */
class StackNodeVisitor implements \Twig_NodeVisitorInterface
{
    /**
     * Called before child nodes are visited.
     *
     * @param \Twig_NodeInterface $node The node to visit
     * @param \Twig_Environment $env The Twig environment instance
     * @return \Twig_NodeInterface The modified node
     */
    public function enterNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        return $node;
    }

    /**
     * Called after child nodes are visited.
     *
     * @param \Twig_NodeInterface $node The node to visit
     * @param \Twig_Environment $env The Twig environment instance
     * @return \Twig_NodeInterface|false The modified node or false if the node must be removed
     */
    public function leaveNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        if ($node instanceof \Twig_Node_Module) {
            $this->handleModuleNode($node);
        }

        return $node;
    }

    /**
     * Handle the module node
     * Add a render stash node to the end of the module body, only when the template does not have a parent
     *
     * @param \Twig_Node_Module $node
     */
    private function handleModuleNode(\Twig_Node_Module $node)
    {
        if ($node->hasNode('body') && !$node->hasNode('parent')) {
            $body = $node->getNode('body');
            $node->setNode('body', new StackBodyNode($body));
        }
    }

    /**
     * Returns the priority for this visitor.
     * Priority should be between -10 and 10 (0 is the default).
     *
     * @return integer The priority level
     */
    public function getPriority()
    {
        return -10;
    }
}