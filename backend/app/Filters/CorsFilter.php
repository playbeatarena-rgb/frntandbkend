<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Cors;

/**
 * CORS Filter
 * Handles Cross-Origin Resource Sharing (CORS) headers
 */
class CorsFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not alter the request or response
     * in any way. However, as occasionally needed, it
     * could also process the request in some way.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $config = new Cors();
        $origin = $request->getHeaderLine('Origin');

        // Check if origin is allowed
        if (empty($config->allowedOrigins) || in_array($origin, $config->allowedOrigins)) {
            $response = service('response');
            $response->setHeader('Access-Control-Allow-Origin', $origin ?: '*');
            $response->setHeader('Access-Control-Allow-Methods', implode(', ', $config->allowedMethods));
            $response->setHeader('Access-Control-Allow-Headers', implode(', ', $config->allowedHeaders));
            $response->setHeader('Access-Control-Expose-Headers', implode(', ', $config->exposedHeaders));
            $response->setHeader('Access-Control-Max-Age', (string)$config->maxAge);

            if ($config->supportsCredentials) {
                $response->setHeader('Access-Control-Allow-Credentials', 'true');
            }

            // Handle preflight requests
            if ($request->getMethod() === 'OPTIONS') {
                $response->setStatusCode(200);
                return $response;
            }
        }
    }

    /**
     * We don't have anything to do here. If a particular filter
     * needs to process something after the controller has done
     * its thing, they can do that here.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
