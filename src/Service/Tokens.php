<?php declare(strict_types=1);

namespace App\Service;

use DateTime;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Throwable;
use const FILTER_VALIDATE_EMAIL;

final readonly class Tokens
{
    public function __construct(
        #[Autowire(param: 'kernel.secret')]
        private string $secret,
    ) {
    }

    public function generateTokenForUser(string $email, DateTime $expire = new DateTime('+4 hours')): string
    {
        $payload = [
            'email' => $email,
            'exp' => $expire->getTimestamp(), // 🔄 Utilisation de 'exp' pour la date d'expiration standard du JWT
        ];

        return JWT::encode($payload, $this->secret, 'HS256'); // 🔥 Utilisation correcte du JWT avec algorithme HS256
    }

    public function decodeUserToken(?string $token): ?string
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));

            // Vérifie que l'email est bien présent dans le token
            if (isset($decoded->email) && filter_var($decoded->email, FILTER_VALIDATE_EMAIL)) {
                return $decoded->email;
            }

            return null;
        } catch (Throwable $e) {
            return null; // Retourne null en cas d'échec de décodage
        }
    }
}