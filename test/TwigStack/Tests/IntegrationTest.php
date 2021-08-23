<?php

namespace TwigStack\Tests;

use TwigStack\Extension\StackExtension;
use Twig;

/**
 * Integration test for the twig stack
 *
 * @package TwigStack\Tests
 */
class IntegrationTest extends Twig\Test\IntegrationTestCase
{
    /**
     * Get the extensions under test
     *
     * @return array
     */
    public function getExtensions(): array
    {
        return array(
            new StackExtension()
        );
    }

    /**
     * @return string
     */
    public function getFixturesDir(): string
    {
        return __DIR__ . '/Fixtures/';
    }
}