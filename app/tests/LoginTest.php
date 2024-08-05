<?php
// tests/SeleniumTest.php


namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class LoginTest extends PantherTestCase
{
    public function testPageTitle()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', 'http://localhost:8000'); // Adjust the URL accordingly

        $this->assertSelectorTextContains('h1', 'Welcome to Symfony'); // Adjust the selector and expected text
    }
}

