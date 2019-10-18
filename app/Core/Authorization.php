<?php

namespace App\Core;

use Emarref\Jwt\Claim;

class Authorization
{
    public static function GenerarToken($data)
    {
        $token = new \Emarref\Jwt\Token();
        $token->addClaim(new Claim\Audience(['client']));
        $token->addClaim(new Claim\Expiration(new \DateTime('30 minutes')));
        $token->addClaim(new Claim\IssuedAt(new \DateTime('now')));
        $token->addClaim(new Claim\Issuer('issuer'));
        $token->addClaim(new Claim\JwtId($data['id']));
        $token->addClaim(new Claim\NotBefore(new \DateTime('now')));
        $token->addClaim(new Claim\Subject('user'/*$data['usuario']*/));
        // $token->addClaim(new Claim\PublicClaim('roles', '101,102,103'));

        $jwt = new \Emarref\Jwt\Jwt();
        $algorithm = new \Emarref\Jwt\Algorithm\Hs256('caballo-salvaje');
        $encryption = \Emarref\Jwt\Encryption\Factory::create($algorithm);
        $serializedToken = $jwt->serialize($token, $encryption);

        return $serializedToken;
    }

    public static function validarToken($data)
    {
        $jwt = new \Emarref\Jwt\Jwt();
        $token = $jwt->deserialize($data['token']);
        $algorithm = new \Emarref\Jwt\Algorithm\Hs256('caballo-salvaje');
        $encryption = \Emarref\Jwt\Encryption\Factory::create($algorithm);
        $context = new \Emarref\Jwt\Verification\Context($encryption);

        $context->setAudience('client');
        $context->setIssuer('issuer');
        $context->setSubject('user'/*$data['usuario']*/);

        // var_dump($token->getPayload()->getClaims()->jsonSerialize());
        try {
            $jwt->verify($token, $context);
            return true;
        } catch (\Emarref\Jwt\Exception\VerificationException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
