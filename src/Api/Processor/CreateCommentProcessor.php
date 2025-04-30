<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CreateCommentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
    ) {
    }

    /** @param Comment $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Comment {
        $comment = new Comment();

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new RuntimeException('Invalid user.');
        }

        $comment->author = $user;

        $comment->comment = $data->comment;

        $comment->content = $data->content;

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }
}
