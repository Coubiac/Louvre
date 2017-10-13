<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;

class OrderControllerTest extends WebTestCase
{

    private $client = null;
    private $order;
    private $ticket;

    public function setUp()
    {
        $this->client = static::createClient();
        $dateOfVisit = new DateTime('2018-08-12');
        $this->order = new Order();
        $this->order->setEmail('testlouvre@gmail.com')->setDateOfVisit($dateOfVisit)->setFullDayTicket(true);

        $this->ticket = new Ticket;


    }

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
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Submit')->form();

        $form['appbundle_order[email]'] = 'benoit.grisot@gmail.com';
        $form['appbundle_order[dateOfVisit]'] = '2018-08-12';
        $form['appbundle_order[fullDayTicket]'] = true;

        $crawler = $client->submit($form);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegexp(
            '/The museum is closed on Sundays and Tuesdays. Please choose another day/',
            $client->getResponse()->getContent());


    }

    public function testSummary()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $extract = $crawler->filter('input[name="appbundle_order[_token]"]')
            ->extract(array('value'));
        $csrf_token = $extract[0];
        $data = array(
            'appbundle_order[email]' => 'benoit.grisot@gmail.com',
            'appbundle_order[dateOfVisit]_submit' => '2017/10/26',
            'appbundle_order[dateOfVisit]' => '2017-10-26',
            'appbundle_order[fullDayTicket]' => '1',
            'appbundle_order[tickets][0][reducedPrice]' => '0',
            'appbundle_order[tickets][0][lastname]' => 'GRISOT',
            'appbundle_order[tickets][0][firstname]' => 'Benoit',
            'appbundle_order[tickets][0][country]' => 'FR',
            'appbundle_order[tickets][0][birthdate]' => '03/05/1979',
            'appbundle_order[submit]' => '',
            'appbundle_order[_token]' => $csrf_token);
        $client->request(
            'POST',
            '/',
            $data);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        echo($client->getResponse()->getContent());


    }

}