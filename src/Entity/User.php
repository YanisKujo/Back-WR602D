<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Processor\CreateUserProcessor;
use App\Api\Resource\CreateUser;
use App\Doctrine\Trait\TimestampableTrait;
use App\Doctrine\Trait\UuidTrait;
use App\Enum\RoleEnum;
use App\Enum\TableEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource]
#[Post(input: CreateUser::class, processor: CreateUserProcessor::class)]
#[Delete(security: 'is_granted("' . RoleEnum::ROLE_USER . '") and object == user or is_granted("' . RoleEnum::ROLE_ADMIN . '")')]
#[Put(security: 'is_granted("' . RoleEnum::ROLE_USER . '") and object == user or is_granted("' . RoleEnum::ROLE_ADMIN . '")')]
#[GetCollection()]
#[ORM\Table(name: TableEnum::USER)]
#[ApiFilter(DateFilter::class, properties: ['createdAt' => 'partial'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ?string $firstName = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ?string $lastName = null;

    #[ORM\Column(unique: true)]
    #[Assert\Email]
    #[ApiProperty(writable: true)]
    public ?string $email = null;

    #[ORM\Column]
    public ?string $password = null;

    /**
     * @var array<int, string>
     */
    #[ORM\Column]
    public array $roles = [];

    public function __construct()
    {
        $this->defineUuid();
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
}
