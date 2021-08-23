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

use Twig;

/**
 * Class StackPopNode
 * @package TwigStack\Node
 */
class StackPopNode extends Twig\Node\Node
{
    /**
     * @var Twig\Extension\ExtensionInterface
     */
    private $ext;

    /**
     * @param Twig\Extension\ExtensionInterface $ext,
     * @param string $name
     * @param Twig\Node\Node $separator
     * @param int $lineno
     */
    public function __construct(
        Twig\Extension\ExtensionInterface $ext,
        string $name,
        Twig\Node\Node $separator,
        int $lineno = 0
    ) {
        parent::__construct(array('separator' => $separator), array('name' => $name), $lineno);
        $this->ext = $ext;
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig\Compiler $compiler A Twig_Compiler instance
     */
    public function compile(Twig\Compiler $compiler)
    {
        $compiler
            ->write(sprintf("echo 'stack_pop_%s(' . ", $this->getAttribute('name')))
            ->subcompile($this->getNode('separator'), true)
            ->write(" . ')';\n")
        ;
    }
} 