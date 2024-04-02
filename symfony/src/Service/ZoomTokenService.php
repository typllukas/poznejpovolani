<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class ZoomTokenService
{
    private $httpClient;
    private $redisClient;
    private $logger;

    public function __construct(HttpClientInterface $httpClient, string $redisDsn, LoggerInterface $logger) {
        $this->httpClient = $httpClient;
        $this->redisClient = RedisAdapter::createConnection($redisDsn);
        $this->logger = $logger;
        }

    public function getValidAccessToken(): ?string {
        $this->logger->info('Attempting to retrieve access token from Redis.'); // Log attempt to retrieve token
        try {
            $accessToken = $this->redisClient->get('access_token');
            if (!$accessToken) {
                $this->logger->info('Access token not found in Redis, fetching a new one.');
                $newTokenData = $this->fetchNewAccessToken();
                if (isset($newTokenData['access_token'])) {
                    $this->logger->info('New access token fetched successfully.');
                    // Save new token in Redis with expiration
                    $this->redisClient->set('access_token', $newTokenData['access_token'], ['ex' => $newTokenData['expires_in'] - 100]);

                    // Subtracting 100 seconds to ensure we refresh the token before it actually expires
                    return $newTokenData['access_token'];
                } else {
                    $this->logger->error('Failed to fetch new access token.');
                    return null;
                }
            }
            return $accessToken; // Return existing token from Redis
        } catch (\Exception $e) {
            $this->logger->error('An error occurred while retrieving the access token: ' . $e->getMessage());
            return null;
        }

    }

    private function fetchNewAccessToken(): array {
        try {
        $response = $this->httpClient->request('POST', 'https://zoom.us/oauth/token', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($_ENV['ZOOM_CLIENT_ID'].":".$_ENV['ZOOM_CLIENT_SECRET']),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $data = $response->toArray();
        
        if (isset($data['access_token'])) {
            return [
                'access_token' => $data['access_token'],
                'expires_in' => $data['expires_in'],
            ];
        }

        // Failed to obtain access token
        return [];
    } catch (\Exception $e) {
        // Handle error (e.g., log it)
        return [];
    }    
    }
    
}