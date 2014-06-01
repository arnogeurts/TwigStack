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
 * Class StackPushNode
 * @package TwigStack\Node
 */
class StackPushNode extends \Twig_Node
{
    /**
     * Construct the stack body with the original body
     *
     * @param string $name
     * @param \Twig_Node $body
     * @param int $lineno
     * @param string $tag
     */
    public function __construct($name, \Twig_Node $body, $lineno = 0, $tag = null)
    {
        parent::__construct(array('body' => $body), array('name' => $name), $lineno, $tag);
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
            ->write("\$result = ob_get_clean();\n")
            ->write(sprintf("\$this->env->getExtension('stack')->pushStack('%s', \$result);\n\n", $this->getAttribute('name')));
        ;
    }
} 