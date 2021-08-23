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

use Twig\Environment;
use Twig\Node\Node;
use TwigStack\Node\StackBodyNode;
use Twig;

/**
 * Class StackNodeVisitor
 * @package TwigStack\NodeVisitor
 */
class StackNodeVisitor extends Twig\NodeVisitor\AbstractNodeVisitor
{
    /**
     * @var Twig\Extension\ExtensionInterface
     */
    private $ext;

    public function __construct(Twig\Extension\ExtensionInterface $ext)
    {
        $this->ext = $ext;
    }
    /**
     * @inheritDoc
     */
    protected function doEnterNode(Node $node, Environment $env): Node
    {
        return $node;
    }

    /**
     * @inheritDoc
     */
    protected function doLeaveNode(Node $node, Environment $env): Node
    {
        if ($node instanceof Twig\Node\ModuleNode) {
            $this->handleModuleNode($node);
        }
        return $node;
    }

    /**
     * Handle the module node
     * Add a render stash node to the end of the module body, only when the template does not have a parent
     *
     * @param Twig\Node\ModuleNode $node
     */
    private function handleModuleNode(Twig\Node\ModuleNode $node)
    {
        if ($node->hasNode('body') && !$node->hasNode('parent')) {
            $body = $node->getNode('body');
            $node->setNode('body', new StackBodyNode($this->ext, $body));
        }
    }

    /**
     * Returns the priority for this visitor.
     * Priority should be between -10 and 10 (0 is the default).
     *
     * @return int The priority level
     */
    public function getPriority(): int
    {
        return -10;
    }
}
