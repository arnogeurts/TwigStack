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

use TwigStack\Node\StackPopNode;

/**
 * Class StackPopTokenParser
 * @package TwigStack\TokenParser
 */
class StackPopTokenParser extends \Twig_TokenParser
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
        if ($stream->test(\Twig_Token::STRING_TYPE)) {
            $separator = $this->parser->getExpressionParser()->parseStringExpression();
        } else {
            $separator = new \Twig_Node_Expression_Constant('', $lineno);
        }
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new StackPopNode($name, $separator, $lineno);
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'stackpop';
    }
}