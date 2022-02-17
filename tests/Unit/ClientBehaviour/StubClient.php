<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Postmark\ClientBehaviour\PostmarkClientBase;

final class StubClient extends PostmarkClientBase
{
    protected function authorizationHeaderName(): string
    {
        return 'Expect-Auth-Header';
    }

    /**
     * @param non-empty-string        $method
     * @param non-empty-string        $path
     * @param array<array-key, mixed> $params
     *
     * @return array<array-key, mixed>
     */
    public function send(string $method, string $path, array $params = []): array
    {
        return $this->processRestRequest($method, $path, $params);
    }
}
