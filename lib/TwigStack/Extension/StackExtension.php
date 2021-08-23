<?php
/*
 * This file is part of the TwigStack extension for Twig
 *
 * (c) 2014 Arno Geurts
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */

namespace TwigStack\Extension;

use Twig\NodeVisitor\NodeVisitorInterface;
use TwigStack\NodeVisitor\StackNodeVisitor;
use TwigStack\Stack;
use TwigStack\TokenParser\StackPopTokenParser;
use TwigStack\TokenParser\StackPushTokenParser;
use Twig;

/**
 * The Twig extension, which should be added to the Twig environment to enable TwigStack
 *
 * @package TwigStack\Extension
 * @author Arno Geurts
 */
class StackExtension extends Twig\Extension\AbstractExtension
{
    /**
     * @var array|Stack[]
     */
    private $stacks = array();

    /**
     * Push the given content to the stack identified by its name
     * If the stack does not exist, create it
     *
     * @param string $stackName
     * @param string $content
     * @return void
     */
    public function pushStack(string $stackName, string $content): void
    {
        if (!array_key_exists($stackName, $this->stacks)) {
            $this->stacks[$stackName] = new Stack();
        }
        $this->stacks[$stackName]->push($content);
    }

    /**
     * Render the stacks in the output string
     *
     * @param string $output
     * @return string
     */
    public function render(string $output): string
    {
        $stacks = $this->stacks;
        // try to find the following string in the output
        // stack_pop_[stashName]([seperator])
        $regex = '/stack_pop_([\w]*)\(([^)]*)\)/';
        $callback = function($matches) use ($stacks) {
            // if the requested stack does not exists, replace it with an empty string
            if (!array_key_exists($matches[1], $stacks)) {
                return '';
            }

            // set the separator for the stack
            $stacks[$matches[1]]->setSeparator($matches[2]);

            // cast the stack to string
            return (string)$stacks[$matches[1]];
        };

        return preg_replace_callback($regex, $callback, $output);
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers(): array
    {
        return array(
            new StackPushTokenParser($this),
            new StackPopTokenParser($this)
        );
    }

    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return NodeVisitorInterface[] An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors(): array
    {
        return array(
            new StackNodeVisitor($this)
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(): string
    {
        return 'stack';
    }
}