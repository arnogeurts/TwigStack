<?php
/*
 * This file is part of the TwigStack extension for Twig
 *
 * (c) 2014 Arno Geurts
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */

namespace TwigStack\Node;

/**
 * Class StackPopNode
 * @package TwigStack\Node
 */
class StackPopNode extends \Twig_Node
{
    /**
     * @param string $name
     * @param \Twig_Node $separator
     * @param int $lineno
     */
    public function __construct($name, \Twig_Node $separator, $lineno = 0)
    {
        parent::__construct(array('separator' => $separator), array('name' => $name), $lineno);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->write(sprintf("echo 'stack_pop_%s(' . ", $this->getAttribute('name')))
            ->subcompile($this->getNode('separator'), true)
            ->write(" . ')';\n")
        ;
    }
} 