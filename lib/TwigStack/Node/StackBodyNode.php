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
 * Class StackBodyNode
 * @package TwigStack\Node
 */
class StackBodyNode extends \Twig_Node
{
    /**
     * Consturct the stack body with the original body
     *
     * @param \Twig_Node $body
     */
    public function __construct(\Twig_Node $body)
    {
        parent::__construct(array('body' => $body));
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
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
            ->write("echo \$this->env->getExtension('stack')->render(ob_get_clean());\n\n")
        ;
    }
} 