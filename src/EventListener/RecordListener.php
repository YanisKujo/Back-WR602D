<?php

namespace App\EventListener;

use App\Entity\Game;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecordListener
{
    public function __construct(private readonly HttpClientInterface $httpClient) {}

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
    
        if (!$entity instanceof Game) {
            return;
        }
    
        $user = $entity->getUser();
        if (!$user || !$entity->getHasWon()) {
            return;
        }
    
        $em = $args->getObjectManager(); 
        $repo = $em->getRepository(Game::class);
    
        // Chercher le meilleur score de l'utilisateur
        $qb = $repo->createQueryBuilder('g')
            ->select('MAX(g.gain)')
            ->where('g.user = :user')
            ->setParameter('user', $user);

        $previousBestGain = $qb->getQuery()->getSingleScalarResult();
    
        if ($previousBestGain === null || $entity->getGain() > (int) $previousBestGain) {
            try {
                $this->httpClient->request('POST', 'http://host.docker.internal:8001/api/mail', [
                    'headers' => [
                        'X-API-KEY' => 'SECURE1234',
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        'to' => $user->getEmail(),
                        'subject' => '🎉 Bravo !',
                        'content' => sprintf(
                            'Bravo %s, vous avez gagnez %s € !',
                            $user->getFirstName(),
                            $entity->getGain()
                        ),
                    ]
                ]);
            } catch (\Exception $e) {
                // Log the error or handle it appropriately
                // Example: error_log($e->getMessage());
            }
        }
    }
}