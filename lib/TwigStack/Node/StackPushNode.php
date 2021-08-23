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
use TwigStack\Extension\StackExtension;

/**
 * Class StackPushNode
 * @package TwigStack\Node
 */
class StackPushNode extends Twig\Node\Node
{
    /**
     * @var Twig\Extension\ExtensionInterface
     */
    private $ext;

    /**
     * Construct the stack body with the original body
     *
     * @param Twig\Extension\ExtensionInterface $ext
     * @param string $name
     * @param Twig\Node\Node $body
     * @param int $lineno
     * @param string|null $tag
     */
    public function __construct(
        Twig\Extension\ExtensionInterface $ext,
        string $name,
        Twig\Node\Node $body,
        int $lineno = 0,
        string $tag = null
    ) {
        parent::__construct(array('body' => $body), array('name' => $name), $lineno, $tag);
        $this->ext = $ext;
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig\Compiler $compiler A Twig\Compiler instance
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
            ->write("\$result = ob_get_clean();\n")
            ->write(sprintf("\$this->env->getExtension(" . get_class($this->ext) . "::class)->pushStack('%s', \$result);\n\n", $this->getAttribute('name')));
    }
}
