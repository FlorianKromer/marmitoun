<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase
{
    public function testGetAllRecettes()
    {
        $client = static::createClient();

        $client->request('GET', '/api/recettes.json');

        
        // var_dump($client->getResponse()->getContent());
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
        // asserts that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );

        $content = json_decode($client->getResponse()->getContent(), true);
        // $this->assertInternalType('array', $content);
        // $recette = $content[0];
        // $this->assertArrayHasKey('titre', $recette);

    }
}