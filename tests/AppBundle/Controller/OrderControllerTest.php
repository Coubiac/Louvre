<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;

class OrderControllerTest extends WebTestCase
{
    private $client = null;

    public function testHomepageIsUp()
    {
        $this->client->request('GET', '/');

        static::assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testInvalidDate()
    {
        $crawler = $this->client->request('GET', '/');

        $form = $crawler->selectButton('appbundle_order[submit]')->form();

        $form['appbundle_order[email]'] = 'benoit.grisot@gmail.com';
        $form['appbundle_order[dateOfVisit]'] = '12/08/2018';
        $form['appbundle_order[fullDayTicket]'] = true;

        $crawler = $this->client->submit($form);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegexp(
            '/The museum is closed on Sundays and Tuesdays. Please choose another day/',
            $this->client->getResponse()->getContent());
    }

    public function testSummary()
    {
        $crawler = $this->client->request('GET', '/');
        $extract = $crawler->filter('input[name="appbundle_order[_token]"]')
            ->extract(['value']);
        $csrf_token = $extract[0];
        $data = [
            'appbundle_order[email]'                    => 'benoit.grisot@gmail.com',
            'appbundle_order[dateOfVisit]_submit'       => '2017/10/26',
            'appbundle_order[dateOfVisit]'              => '26/10/2017',
            'appbundle_order[fullDayTicket]'            => '1',
            'appbundle_order[tickets][0][reducedPrice]' => '0',
            'appbundle_order[tickets][0][lastname]'     => 'GRISOT',
            'appbundle_order[tickets][0][firstname]'    => 'Benoit',
            'appbundle_order[tickets][0][country]'      => 'FR',
            'appbundle_order[tickets][0][birthdate]'    => '03/05/1979',
            'appbundle_order[submit]'                   => '',
            'appbundle_order[_token]'                   => $csrf_token, ];
        $this->client->request(
            'POST',
            '/',
            $data);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function setUp()
    {
        $this->client = static::createClient();
    }
}
