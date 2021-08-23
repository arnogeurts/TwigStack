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
use Twig;

/**
 * Class StackPopTokenParser
 * @package TwigStack\TokenParser
 */
class StackPopTokenParser extends Twig\TokenParser\AbstractTokenParser
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
     * Parses a token and returns a node.
     *
     * @param Twig\Token $token A Twig_Token instance
     * @return Twig\Node\Node A Twig_NodeInterface instance
     * @throws Twig\Error\SyntaxError
     */
    public function parse(Twig\Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(Twig\Token::NAME_TYPE)->getValue();
        if ($stream->test(Twig\Token::STRING_TYPE)) {
            $separator = $this->parser->getExpressionParser()->parseStringExpression();
        } else {
            $separator = new Twig\Node\Expression\ConstantExpression('', $lineno);
        }
        $stream->expect(Twig\Token::BLOCK_END_TYPE);
        return new StackPopNode($this->ext, $name, $separator, $lineno);
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag(): string
    {
        return 'stackpop';
    }
}
