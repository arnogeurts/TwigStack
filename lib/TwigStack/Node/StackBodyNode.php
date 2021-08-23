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

use Twig\Node\Node;
use Twig;

/**
 * Class StackBodyNode
 * @package TwigStack\Node
 */
class StackBodyNode extends Node
{
    /**
     * @var Twig\Extension\ExtensionInterface
     */
    private $ext;

    /**
     * Construct the stack body with the original body
     *
     * @param Twig\Extension\ExtensionInterface $ext
     * @param Node $body
     */
    public function __construct(Twig\Extension\ExtensionInterface $ext, Node $body)
    {
        parent::__construct(array('body' => $body));
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
            ->write("ob_start();\n")
            ->write("try {\n")
            ->indent()
            ->subcompile($this->getNode('body'))
            ->outdent()
            ->write("} catch (Exception \$e) {\n")
            ->indent()
            ->write("ob_end_clean();\n\n")
            ->write("throw \$e;\n")
            ->outdent()
            ->write("}\n\n")
            ->write("echo \$this->env->getExtension(" . get_class($this->ext) . "::class)->render(ob_get_clean());\n\n");
    }
}
