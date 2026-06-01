<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * CORS Configuration
 * Controls cross-origin requests to the API
 */
class Cors extends BaseConfig
{
    /**
     * Allowed request origins
     * @var array
     */
    public array $allowedOrigins = [];

    /**
     * Allowed HTTP methods
     * @var array
     */
    public array $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'];

    /**
     * Allowed HTTP headers
     * @var array
     */
    public array $allowedHeaders = ['Content-Type', 'Authorization', 'X-Requested-With'];

    /**
     * Headers to expose to the client
     * @var array
     */
    public array $exposedHeaders = ['Content-Type', 'X-Total-Count'];

    /**
     * Max age for preflight requests (in seconds)
     * @var int
     */
    public int $maxAge = 86400;

    /**
     * Allow credentials (cookies, headers)
     * @var bool
     */
    public bool $supportsCredentials = true;

    public function __construct()
    {
        parent::__construct();

        // Load allowed origins from environment
        $allowedOrigins = getenv('ALLOWED_ORIGINS');
        if ($allowedOrigins) {
            $this->allowedOrigins = array_map('trim', explode(',', $allowedOrigins));
        }
    }
}
