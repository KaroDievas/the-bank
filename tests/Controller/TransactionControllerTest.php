<?php

namespace App\Tests\Controller;

use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TransactionControllerTest extends WebTestCase
{
    public function testGetAllTransactionsAction()
    {
        $client = static::createClient();
        $client->request('GET', '/get-all-transactions');

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_array($responseData));
    }

    public function testCreateNewTransactionActionFail()
    {
        $client = static::createClient();
        $client->request('POST', '/create-new-transaction');
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    public function testSubmitNewTransactionAction401()
    {
        $client = static::createClient();
        $client->request('PUT', '/sign-transaction/1');
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testSubmitNewTransactionAction404()
    {
        $client = static::createClient();
        $client->request('PUT', '/sign-transaction/9999999', [], [], [], json_encode(array('code' => 111)));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCreateNewTransactionActionAndSubmit()
    {
        $data = [
            "user_id" => 14,
            "details" => "Transaction number oneeeeeeeeeeeeee",
            "receiver_account" => "12345",
            "receiver_name" => "Name Surname",
            "amount" => 20.00,
            "currency" => "usd"
        ];

        $client = static::createClient();
        $client->request('POST', '/create-new-transaction', [], [], [], json_encode($data));

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent(), true);

        $this->assertNotNull($response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertGreaterThan(0, $responseData['id']);
        $this->assertArrayHasKey('details', $responseData);
        $this->assertArrayHasKey('receiver_account', $responseData);
        $this->assertArrayHasKey('receiver_name', $responseData);
        $this->assertArrayHasKey('amount', $responseData);
        $this->assertArrayHasKey('currency', $responseData);
        $this->assertArrayHasKey('user_id', $responseData);
        $this->assertArrayHasKey('created_at', $responseData);
        $this->assertArrayHasKey('fee_amount', $responseData);
        $this->assertArrayHasKey('status', $responseData);
        $this->assertNotEquals($data['details'], $responseData['details']);
        $this->assertEquals($data['user_id'], $responseData['user_id']);
        $this->assertEquals($data['amount'], $responseData['amount']);
        $this->assertEquals($data['currency'], $responseData['currency']);
        $this->assertEquals($data['receiver_name'], $responseData['receiver_name']);
        $this->assertEquals($data['receiver_account'], $responseData['receiver_account']);
        $this->assertEquals('CREATED', $responseData['status']);

        $client->request('PUT', sprintf('/sign-transaction/%s', $responseData['id']), [], [], [], json_encode(array('code' => 111)));
        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}