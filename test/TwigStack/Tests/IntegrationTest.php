<?php

namespace TwigStack\Tests;

use TwigStack\Extension\StackExtension;

/**
 * Integration test for the twig stack
 *
 * @package TwigStack\Tests
 */
class IntegrationTest extends \Twig_Test_IntegrationTestCase
{
    /**
     * Get the extensions under test
     *
     * @return array
     */
    public function getExtensions()
    {
        return array(
            new StackExtension()
        );
    }

    /**
     * @return string
     */
    public function getFixturesDir()
    {
        return dirname(__FILE__).'/Fixtures/';
    }
}