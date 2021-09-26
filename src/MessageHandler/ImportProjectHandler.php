<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Project;
use App\Exception\InvalidProjectException;
use App\Form\Model\ProjectModel;
use App\Import\ProjectImportResult;
use App\Message\ImportProject;
use App\Repository\ProjectRepository;
use App\Repository\StatusRepository;
use League\CommonMark\CommonMarkConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImportProjectHandler implements MessageHandlerInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private StatusRepository $statusRepository,
        private ValidatorInterface $validator
    ) {
    }

    public function __invoke(ImportProject $importProject): ProjectImportResult
    {
        $content = $importProject->getContent();
        $document = YamlFrontMatter::parse($content);

        $frontMatter = $document->matter();
        $descriptionCommonMark = $document->body();

        $converter = new CommonMarkConverter([
            'html_input' => 'allow',
            'allow_unsafe_links' => true,
        ]);

        $description = $converter->convertToHtml($descriptionCommonMark);
        $status = $this->statusRepository->findOneByName($frontMatter['status'] ?? '');

        $model = new ProjectModel();
        $model->name = $frontMatter['name'] ?? '';
        $model->url = $frontMatter['url'] ?? '';
        $model->alias = $frontMatter['alias'] ?? '';
        $model->rssUrl = $frontMatter['rssUrl'] ?? '';
        $model->description = $description->getContent();
        $model->status = $status;

        $errors = $this->validator->validate($model);

        if (count($errors) > 0) {
            throw new InvalidProjectException($errors);
        }

        $project = $this->projectRepository->findProject($model->alias);

        if ($project !== null) {
            $project->updateFromModel($model);
            $this->projectRepository->update($project);

            return new ProjectImportResult($project, false);
        }

        $project = Project::createFromModel($model);
        $this->projectRepository->create($project);

        return new ProjectImportResult($project, true);
    }
}
