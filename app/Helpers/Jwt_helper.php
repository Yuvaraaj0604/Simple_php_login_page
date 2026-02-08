<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateJWT(array $user): string
{
    $secret = '863a780092f695ee65dbffeeb83c9cf01014e7db4d9af940eb559d998e7c3a10';

    if (empty($secret) || strlen($secret) < 32) {
        throw new Exception('JWT_SECRET is missing or too short');
    }

    $expiry = env('JWT_EXPIRY') ?? 3600; // default 1 hour

    $payload = [
        'iss'   => 'simple-login',
        'aud'   => 'simple-login-client',
        'iat'   => time(),
        'exp'   => time() + (int)$expiry,
        'uid'   => $user['id'],
        'email' => $user['email']
    ];

    // ✅ PASS SECRET STRING DIRECTLY
    return JWT::encode($payload, $secret, 'HS256');
}

function validateJWT(string $token)
{
    try {
        return JWT::decode(
            $token,
            new Key("863a780092f695ee65dbffeeb83c9cf01014e7db4d9af940eb559d998e7c3a10", 'HS256')
        );
    } catch (Exception $e) {
        return false;
    }
}
