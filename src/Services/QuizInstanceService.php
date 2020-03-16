<?php

namespace QuizApp\Services;

use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\QuizTemplate;
use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizInstanceService extends AbstractService
{
    private $repositoryManager;

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function createQuizInstance(
        string $quizTemplateId,
        string $userId)
    : ?int {
        $quizInstanceRepository = $this->repositoryManager->getRepository(QuizInstance::class);
        $quizTemplateRepository = $this->repositoryManager->getRepository(QuizTemplate::class);

        try {
            $quizTemplate = $quizTemplateRepository->find($quizTemplateId);

            $quizInstance = new QuizInstance();
            $quizInstance->setQuizTemplateId($quizTemplateId);
            $quizInstance->setUserId($userId);
            $quizInstance->setName($quizTemplate->getName());
            $quizInstance->setCurrentQuestion("1");

            if ($quizInstanceRepository->insertOnDuplicateKeyUpdate($quizInstance)) {
                return $quizInstanceRepository->getLastInsertedId();
            }

            //TODO check if it was created before;
            return
                ($quizInstanceRepository->insertOnDuplicateKeyUpdate($quizInstance))
                    ? $quizInstanceRepository->getLastInsertedId()
                : null;
        } catch (NoSuchRowException $e) {
            return null;
        }
    }

    public function isCreated($quizTemplateId, $userId): ?AbstractEntity
    {
        $quizInstanceRepository = $this->repositoryManager->getRepository(QuizInstance::class);

        try {
            return $quizInstanceRepository->findOneBy(
                ['userId' => $userId, 'quizTemplateId' => $quizTemplateId]
            );
        } catch (NoSuchRowException $e) {
            return null;
        }
    }
}