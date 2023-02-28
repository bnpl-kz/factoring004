<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\OAuth;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CacheOAuthTokenManager implements OAuthTokenManagerInterface
{
    private OAuthTokenManagerInterface $tokenManager;
    private CacheInterface $cache;
    private string $cacheKey;

    public function __construct(OAuthTokenManagerInterface $tokenManager, CacheInterface $cache, string $cacheKey)
    {
        $this->tokenManager = $tokenManager;
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
    }

    public function getAccessToken(): OAuthToken
    {
        return $this->retrieveAccessToken();
    }

    public function revokeToken(): void
    {
        $this->clearCache();
        $this->tokenManager->revokeToken();
    }

    /**
     * @throws \BnplPartners\Factoring004\Exception\OAuthException
     * @psalm-suppress InvalidCatch
     */
    private function retrieveAccessToken(): OAuthToken
    {
        try {
            $tokenData = $this->cache->get($this->cacheKey);
        } catch (InvalidArgumentException $e) {
            return $this->tokenManager->getAccessToken();
        }

        if ($tokenData) {
            $token = OAuthToken::createFromArray($tokenData);

            if ($token->getAccessExpiresAt() > time()) {
                return $token;
            }
        }

        $token = $this->tokenManager->getAccessToken();

        try {
            $this->cache->set($this->cacheKey, $token->toArray(), $token->getRefreshExpiresAt());
        } catch (InvalidArgumentException $e) {
            // do nothing
        }

        return $token;
    }

    /**
     * @psalm-suppress InvalidCatch
     */
    public function clearCache(): void
    {
        try {
            $this->cache->delete($this->cacheKey);
        } catch (InvalidArgumentException $e) {
            // do nothing
        }
    }
}
