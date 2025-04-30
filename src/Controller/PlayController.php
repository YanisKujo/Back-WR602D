<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PlayController extends AbstractController
{
    #[Route('/api/play', name: 'api_play', methods: ['POST'])]
    public function play(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getContent(), true);
        $betAmount = $data['betAmount'] ?? 0;
        $betOn = $data['betOn'] ?? 'red';
        $betType = $data['betType'] ?? 'color';
        $specificNumber = isset($data['specificNumber']) ? (int)$data['specificNumber'] : null;

        $number = random_int(0, 35);

        $result = ($number === 0) ? 'green' : ($number % 2 === 0 ? 'black' : 'red');

        // Calcul du gain
        $hasWon = false;
        $multiplier = 0;

        switch ($betType) {
            case 'color':
                $hasWon = ($betOn === $result);
                $multiplier = $result === 'green' ? 14 : 2;
                break;

            case 'even':
                $hasWon = $number !== 0 && $number % 2 === 0;
                $multiplier = 2;
                break;

            case 'odd':
                $hasWon = $number % 2 === 1;
                $multiplier = 2;
                break;

            case 'range_1_12':
                $hasWon = $number >= 1 && $number <= 12;
                $multiplier = 3;
                break;

            case 'range_13_24':
                $hasWon = $number >= 13 && $number <= 24;
                $multiplier = 3;
                break;

            case 'range_25_36':
                $hasWon = $number >= 25 && $number <= 36;
                $multiplier = 3;
                break;

            case 'number':
                $hasWon = $number === $specificNumber;
                $multiplier = 36;
                break;

            default:
                return $this->json(['error' => 'Type de mise inconnu'], 400);
        }

        $gain = $hasWon ? $betAmount * $multiplier : 0;

        $game = new Game();
        $game->setUser($user);
        $game->setBetAmount($betAmount);
        $game->setBetOn($betOn);
        $game->setHasWon($hasWon);
        $game->setGain($gain);
        $game->setNumber($number);

        $em->persist($game);
        $em->flush();

        return $this->json([
            'result' => $result,
            'number' => $number,
            'hasWon' => $hasWon,
            'gain' => $gain,
        ]);
    }

    #[Route('/custom-api/games/highscore', name: 'global_highscore', methods: ['GET'])]
    public function globalHighScore(EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(Game::class);

        $best = $repo->createQueryBuilder('g')
            ->select('MAX(g.gain)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->json(['globalHighScore' => (int)$best]);
    }
}
