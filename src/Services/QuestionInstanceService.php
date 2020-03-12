<?php


namespace QuizApp\Services;


use QuizApp\Entities\QuestionInstance;
use QuizApp\Entities\TextInstance;
use QuizApp\Repositories\QuestionInstanceRepository;
use QuizApp\Repositories\QuestionRepository;
use QuizApp\Repositories\QuizInstanceRepository;
use QuizApp\Repositories\QuizQuestionTemplateRepository;
use QuizApp\Repositories\TextInstanceRepository;
use QuizApp\Repositories\TextRepository;
use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionInstanceService extends AbstractService
{
    private $repositoryManager;

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function createQuestionInstances(int $quizInstanceId)
    {
        $quizInstanceRepository = $this->repositoryManager->getRepository(QuizInstanceRepository::class);
        $quizQuestionTemplateRepository = $this->repositoryManager->getRepository(QuizQuestionTemplateRepository::class);
        $questionInstanceRepository = $this->repositoryManager->getRepository(QuestionInstanceRepository::class);
        $questionTemplateRepository = $this->repositoryManager->getRepository(QuestionRepository::class);
        $textTemplateRepository = $this->repositoryManager->getRepository(TextRepository::class);
        $textInstanceRepository = $this->repositoryManager->getRepository(TextInstanceRepository::class);

        $quizInstance = $quizInstanceRepository->find((int)$quizInstanceId);
        $quizTemplateId = $quizInstance->getQuizTemplateId();

        $questions = $quizQuestionTemplateRepository->findBy(['quizTemplateId' => $quizTemplateId], [], 0, 0);

        $count = 0;
        foreach ($questions as $key => $value) {
            $questionTemplate =
                $questionTemplateRepository->findOneBy(
                    ['id' => $value->getQuestionTemplateId()]
                );
            $textTempate =
                $textTemplateRepository->findOneBy(
                    ['questionTemplateId' => $value->getQuestionTemplateId()]
                );

            $questionInstanceEntity = new QuestionInstance();
            $questionInstanceEntity->setQuizInstanceId($quizInstanceId);
            $questionInstanceEntity->setText($questionTemplate->getText());

            if ($questionInstanceRepository->insertOnDuplicateKeyUpdate($questionInstanceEntity)) {
                $lastInsertedQuestionInstance = $questionInstanceRepository->getLastInsertedId();

                $textInstanceEntity = new TextInstance();
                $textInstanceEntity->setQuestionInstanceId($lastInsertedQuestionInstance);
                $textInstanceEntity->setText($textTempate->getText());

                if ($textInstanceRepository->insertOnDuplicateKeyUpdate($textInstanceEntity)) {
                    $count++;
                }
            }
        }

        return count($questions) === $count;
    }

    public function getQuestionInstance(string $id, string $offset): ?array
    {
        $questionInstanceRepository = $this->repositoryManager->getRepository(QuestionInstanceRepository::class);

        try {
            return $questionInstanceRepository->findBy(['quizInstanceId' => $id], [], 1, $offset);
        } catch (NoSuchRowException $e) {
            return null;
        }
    }

    public function getTextInstance(string $id): ?AbstractEntity
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(TextInstanceRepository::class);

        try {
            return $textInstanceRepository->findOneBy(['questionInstanceId' => $id]);
        } catch (NoSuchRowException $e) {
            return null;
        }
    }

    public function saveTextInstance(AbstractEntity $entity)
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(TextInstanceRepository::class);

        return $textInstanceRepository->insertOnDuplicateKeyUpdate($entity);
    }

    public function countQuestion(string $quizInstanceId)
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(QuestionInstanceRepository::class);

        $count = $textInstanceRepository->countInstancesWithQuestionInstance($quizInstanceId);

        return $count['COUNT(quizInstanceId)'];
    }

    public function getQuestionsInstance($quizInstanceId)
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(QuestionInstanceRepository::class);

        return $textInstanceRepository->findBy(['quizInstanceId' => $quizInstanceId], [], 0, 0);
    }
}
