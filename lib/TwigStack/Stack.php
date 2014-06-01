<?php
/*
 * This file is part of the TwigStack extension for Twig
 *
 * (c) 2014 Arno Geurts
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */

namespace TwigStack;

/**
 * Class Stack
 * @package TwigStack
 */
class Stack extends \ArrayObject
{
    /**
     * Separator which is used to join the stack, when cast to string
     * @var string
     */
    private $separator = '';

    /**
     * Push a content string or object to the stack
     * As long as it can be casted to a string
     *
     * @param mixed $content
     */
    public function push($content)
    {
        $this->append($content);
    }

    /**
     * Set the separator which is used to join the stack
     *
     * @param string $separator
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }


    /**
     * Cast the stack to a string
     * Which means joining all contents of the stack together
     *
     * @return string
     */
    public function __toString()
    {
        return join($this->separator, $this->getArrayCopy());
    }
} 