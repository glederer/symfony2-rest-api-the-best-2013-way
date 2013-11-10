<?php

namespace Acme\BlogBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Acme\BlogBundle\Tests\Fixtures\Entity\LoadPageData;

class PageControllerTest extends WebTestCase
{
    public function customSetUp($fixtures)
    {
        $this->client = static::createClient();
        $this->loadFixtures($fixtures);
    }

    public function testIndex()
    {
        $fixtures = array('Acme\BlogBundle\Tests\Fixtures\Entity\LoadPageData');
        $this->customSetUp($fixtures);
        $pages = LoadPageData::$pages;
        $page = array_pop($pages);

        $route =  $this->getUrl('api_1_get_page', array('id' => $page->getId(), '_format' => 'json'));

        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));

    }

    protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }


}
