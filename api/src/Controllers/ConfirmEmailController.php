<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ConfirmEmailController
{
    private \Doctrine\DBAL\Connection $db;
    
    public function __construct(\Doctrine\DBAL\Connection $db) {
        $this->db = $db;
    }
    
    public function confirm(Request $request, Response $response, array $args): Response
    {
        $token = $args['token'];
        
        if (\Ramsey\Uuid\Uuid::isValid($token) === false) {
            throw new \DomainException('token expired');
        }
        
        $this->db->update('users_emails', ['confirmed' => 1], ['token' => $token]);

        $response->getBody()
            ->write(json_encode(['message' => 'Email confirmed']));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}