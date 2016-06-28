<?php

namespace My\TaskBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Hello World', $client->getResponse()->getContent());
    }
    
    public function testCustom(){
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/es/create-task');
        
        $form = $crawler->selectButton('submit')->form();
        
        $form['name'] = 'Tarea de prueba';
        
        $form['description'] = 'Tarea de prueba desde PHP Unit';
        
        $crawler = $client->submit($form);
        
        $this->assertTrue($client->getResponse()->getContent());
        $this->assertTrue($client->getResponse()->isNotFound());
        
        $this->assertTrue(
                    $client->getResponse()->isRedirect('/es/')
                );
    }
}
