<?php
/*
 * This file is part of the TwigStack extension for Twig
 *
 * (c) 2014 Arno Geurts
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */

namespace TwigStack\TokenParser;

use TwigStack\Node\StackPushNode;

/**
 * Class StackPushTokenParser
 * @package TwigStack\TokenParser
 */
class StackPushTokenParser extends \Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token A Twig_Token instance
     * @return \Twig_NodeInterface A Twig_NodeInterface instance
     * @throws \Twig_Error_Syntax
     */
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(\Twig_Token::NAME_TYPE)->getValue();
        $this->parser->pushLocalScope();

        if ($stream->nextIf(\Twig_Token::BLOCK_END_TYPE)) {
            $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
            if ($token = $stream->nextIf(\Twig_Token::NAME_TYPE)) {
                $value = $token->getValue();

                if ($value != $name) {
                    throw new \Twig_Error_Syntax(sprintf("Expected endstackpush for stack '$name', but got %s", $value), $stream->getCurrent()->getLine(), $stream->getFilename());
                }
            }
        } else {
            $body = new \Twig_Node(array(
                new \Twig_Node_Print($this->parser->getExpressionParser()->parseExpression(), $lineno),
            ));
        }
        $this->parser->popLocalScope();
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new StackPushNode($name, $body, $lineno, $this->getTag());
    }

    /**
     * @param \Twig_Token $token
     * @return bool
     */
    public function decideBlockEnd(\Twig_Token $token)
    {
        return $token->test('endstackpush');
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'stackpush';
    }
}