<?php

namespace QuizApp\Services;

use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\QuestionTemplate;
use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\TextTemplate;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionService extends AbstractService
{
    /**
     * Constant for pagination.
     */
    private const RESULTS_PER_PAGE = 10;

    private $repositoryManager;

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }
    /**
     * Counts how many user entities the Repository has.
     * @return mixed
     * @throws \Exception
     */
    public function countRows()
    {
        $quizInstanceRepository = $this->repositoryManager->getRepository(QuestionTemplate::class);

        return $quizInstanceRepository->countRows();
    }

    public function selectAll()
    {
        $repository = $this->repositoryManager->getRepository(QuestionTemplate::class);

        return $repository->findBy([], [], 0, 0);
    }

    /**
     * Gets all the question from the specified page
     * @param int $page
     * @return |null
     */
    public function getAll(int $page)
    {
        $from = ($page - 1) * $this::RESULTS_PER_PAGE;
        $repository = $this->repositoryManager->getRepository(QuestionTemplate::class);
        try {
            $entities = $repository->findBy([], [], $this::RESULTS_PER_PAGE, $from);
        } catch (NoSuchRowException $e) {
            $entities = null;
        }
        return $entities;
    }

    public function createEntity($request)
    {
        $questionRepository = $this->repositoryManager->getRepository(QuestionTemplate::class);
        $text = $request->getParameter('question');
        $answer = $request->getParameter('answer');
        $type = $request->getParameter('type');

        $answerRepository = $this->repositoryManager->getRepository(TextTemplate::class);

        // TODO check the answer if empty

        $entity = new QuestionTemplate();
        $entity->setText($text);
        $entity->setType($type);

        if ($questionRepository->insertOnDuplicateKeyUpdate($entity)) {
            $id = $questionRepository->getLastInsertedId();
            $questionType = new TextTemplate();
            $questionType->setText($answer);
            $questionType->setQuestionTemplateId($id);

             return $answerRepository->insertOnDuplicateKeyUpdate($questionType);
        }

        return false;
    }

    public function delete($request) {
        $repository = $this->repositoryManager->getRepository(QuestionTemplate::class);

        $id = $request->getRequestParameters()['id'];
        $entity = $repository->find((int)$id);

        $type = $entity->getType();

        $textRepository = $this->repositoryManager->getRepository(TextTemplate::class);

        $answer = $textRepository->findOneBy(["questionTemplateId" => (int)$id]);

        return ($textRepository->delete($answer) && $repository->delete($entity));
    }

    public function getUpdatePageParams(RequestInterface $request)
    {
        $id = $request->getRequestParameters()['id'];
        $repository = $this->repositoryManager->getrepository(QuestionTemplate::class);
        $entity = $repository->find((int)$id);
        $type = $entity->getType();

        $textRepository = $this->repositoryManager->getRepository(TextTemplate::class);

        $answer = $textRepository->findOneBy(["questionTemplateId" => (int)$id]);

        return ["question" =>$entity, "answer" => $answer];
    }

    public function updateEntity($request, $session)
    {
        //TODO make update in quizzes like here
        $repository = $this->repositoryManager->getRepository(QuestionTemplate::class);
        $id = $request->getRequestParameters()['id'];
        $questionEntity = $repository->find((int)$id);

        $type = $questionEntity->getType();
        $textRepository = $this->repositoryManager->getRepository(TextTemplate::class);
        $answerEntity = $textRepository->findOneBy(["questionTemplateId" => (int)$id]);

        $name = $request->getParameter('question');
        $role = $request->getParameter('type');
        $answer = $request->getParameter('answer');

        $questionEntity->setText($name);
        $questionEntity->setType($role);
        $answerEntity->setText($answer);

        return ($textRepository->insertOnDuplicateKeyUpdate($answerEntity) &&  $repository->insertOnDuplicateKeyUpdate($questionEntity));
    }
}