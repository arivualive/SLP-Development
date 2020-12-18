<?php

namespace GaeaUserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testRedirectlogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/redirectLogin');
    }

}
