<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Filters Configuration
 * Defines filters/middleware for application
 */
class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'      => \CodeIgniter\Filters\CSRF::class,
        'toolbar'   => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot'  => \CodeIgniter\Filters\Honeypot::class,
        'invalidate-session' => \CodeIgniter\Filters\InvalidateSession::class,
        'cors'      => \App\Filters\CorsFilter::class,
    ];

    public $globals = [
        'before' => [
            'cors',
            // 'csrf', // Enable CSRF protection if needed
        ],
        'after'  => [
            'toolbar',
            // 'honeypot',
        ],
    ];

    public $methods = [
        // Add method-specific filters here if needed
    ];

    public $filters = [
        'api/*' => [
            'before' => ['cors'],
        ],
    ];
}
