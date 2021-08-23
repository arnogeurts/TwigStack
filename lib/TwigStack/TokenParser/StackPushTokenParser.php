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
use Twig;

/**
 * Class StackPushTokenParser
 * @package TwigStack\TokenParser
 */
class StackPushTokenParser extends Twig\TokenParser\AbstractTokenParser
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
     * @param Twig\Token $token A Twig\Token instance
     * @return Twig\Node\Node A Twig\Node\Node instance
     * @throws Twig\Error\SyntaxError
     */
    public function parse(Twig\Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(Twig\Token::NAME_TYPE)->getValue();
        $this->parser->pushLocalScope();

        if ($stream->nextIf(Twig\Token::BLOCK_END_TYPE)) {
            $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
            if ($token = $stream->nextIf(Twig\Token::NAME_TYPE)) {
                $value = $token->getValue();

                if ($value != $name) {
                    throw
                        new Twig\Error\SyntaxError(
                            sprintf("Expected endstackpush for stack '$name', but got %s", $value),
                            $stream->getCurrent()->getLine(),
                            $stream->getSourceContext()
                        );
                }
            }
        } else {
            $body = new Twig\Node\Node(array(
                new Twig\Node\PrintNode($this->parser->getExpressionParser()->parseExpression(), $lineno),
            ));
        }
        $this->parser->popLocalScope();
        $stream->expect(Twig\Token::BLOCK_END_TYPE);
        return new StackPushNode($this->ext, $name, $body, $lineno, $this->getTag());
    }

    /**
     * @param Twig\Token $token
     * @return bool
     */
    public function decideBlockEnd(Twig\Token $token): bool
    {
        return $token->test('endstackpush');
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag(): string
    {
        return 'stackpush';
    }
}
