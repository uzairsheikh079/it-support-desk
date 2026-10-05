<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;

class RequireApiKeyTest extends TestCase
{
    public function test_returns_401_when_api_key_is_missing(): void
    {
        config(['supportdesk.api_key' => 'test-api-key']);

        $this->getJson(route('api.tickets.index'))
            ->assertUnauthorized()
            ->assertExactJson(['message' => 'Invalid API key.']);
    }

    public function test_returns_401_when_api_key_is_incorrect(): void
    {
        config(['supportdesk.api_key' => 'test-api-key']);

        $this->withHeader('X-API-Key', 'incorrect-key')
            ->getJson(route('api.tickets.index'))
            ->assertUnauthorized()
            ->assertExactJson(['message' => 'Invalid API key.']);
    }

    public function test_returns_503_when_api_key_is_not_configured(): void
    {
        config(['supportdesk.api_key' => null]);

        $this->withHeader('X-API-Key', 'any-key')
            ->getJson(route('api.tickets.index'))
            ->assertServiceUnavailable()
            ->assertExactJson(['message' => 'API access is not configured.']);
    }
}
