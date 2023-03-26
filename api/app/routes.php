<?php

use Psr\Http\Message\ServerRequestInterface;

return function (Slim\App $app) {
    $app->get('/', \App\Controllers\HomeController::class . ':index');
    $app->get('/confirm-email/{token}', \App\Controllers\ConfirmEmailController::class . ':confirm');

    $app->addRoutingMiddleware();

    $customErrorHandler = function (
        ServerRequestInterface $request,
        Throwable $exception,
    ) use ($app) {
    
        $message = getenv('ENV') === 'production' ? 'Internal server error' : $exception->getMessage();
    
        $payload = ['error' => $message];
    
        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );
        
        $response = $response->withHeader('Content-Type', 'application/json');
    
        if ($exception instanceof \DomainException) {
            return $response->withStatus(400);
        }
    
        if ($exception instanceof \Slim\Exception\HttpNotFoundException) {
            return $response->withStatus(404);
        }
    
        return $response->withStatus(500);
    };
    
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
};