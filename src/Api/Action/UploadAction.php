<?php declare(strict_types=1);

namespace App\Api\Action;

use App\Entity\Upload;
use App\Service\FileUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class UploadAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FileUploadService $fileUploadService,
        #[Autowire(param: 'kernel.project_dir')]
        private string $projectDir,
    ) {
    }

    public function __invoke(Request $request): Upload
    {
        $file = $request->files->get('file');

        if (!$file instanceof UploadedFile) {
            throw new BadRequestHttpException('No valid file provided.');
        }

        $filePath = $this->fileUploadService->handleFileUpload($file, $this->projectDir);

        $upload = new Upload($filePath);
        $this->entityManager->persist($upload);
        $this->entityManager->flush();

        return $upload;
    }
}
