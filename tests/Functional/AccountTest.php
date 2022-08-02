<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Test\CustomApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @covers \App\Entity\Account
 */
class AccountTest extends CustomApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     */
    public function testReadAccountsCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/accounts');
        // The list of accounts should not be available
        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testReadOneAccountWithNoJWTToken(): void
    {
        $response = static::createClient()->request('GET', '/api/accounts/1');
        // The list of accounts should not be available
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * @throws TransportExceptionInterface
     * @TODO
     * still not passing yet
     */
    public function testReadOneAccountWithJWTAuthToken(): void
    {
        $response = static::createClient()->request('GET', '/api/accounts/1');
        // Only the current user's account should be readable
        $this->assertResponseStatusCodeSame(403);
    }
}
