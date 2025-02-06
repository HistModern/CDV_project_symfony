<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{
    public function testSuccessfulRegistration(): void
    {
        $client = static::createClient();
        
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'securepassword',
        ]));
        
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJson($client->getResponse()->getContent());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('User registered successfully', $data['message']);
    }
    
    public function testRegistrationWithMissingFields(): void
    {
        $client = static::createClient();
        
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser'
        ]));
        
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJson($client->getResponse()->getContent());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }
    
    public function testRegistrationWithInvalidEmail(): void
    {
        $client = static::createClient();
        
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'email' => 'invalid-email',
            'password' => 'securepassword',
        ]));
        
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJson($client->getResponse()->getContent());
    }
    
    public function testDuplicateRegistration(): void
    {
        $client = static::createClient();
        
        $userData = json_encode([
            'username' => 'duplicateuser',
            'email' => 'duplicate@example.com',
            'password' => 'securepassword',
        ]);
        
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], $userData);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], $userData);
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
