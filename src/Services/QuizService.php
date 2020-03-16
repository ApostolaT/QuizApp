<?php

namespace QuizApp\Services;

use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\QuizQuestionTemplate;
use QuizApp\Entities\QuizTemplate;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizService extends AbstractService
{
    private $repositoryManager;

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function getAll(RequestInterface $request)
    {
        $page = $request->getRequestParameters();
        $from = ($page['page'] - 1) * 10;

        $repository = $this->repositoryManager->getRepository(QuizTemplate::class);

        $entities = $repository->findBy([], [], 10, $from);

        return $entities;
    }

    public function selectAll()
    {
        $repository = $this->repositoryManager->getRepository(QuizTemplate::class);

        return $repository->findBy([], [], 0, 0);
    }

    public function getType()
    {
        $repository = $this->repositoryManager->getRepository(QuizType::class);

        // TODO create a findAll in abstractRepository
        return $repository->findBy([], [], 0, 0);
    }

    public function createEntity($request, $session)
    {
        $repository = $this->repositoryManager->getRepository(QuizTemplate::class);
        $name = $request->getParameter('name');
        $type = $request->getParameter('type');
        $questions = $request->getparameter('questions');

        $createdBy = $session->get('id');
        $entity = new QuizTemplate();
        $entity->setName($name);
        $entity->setCreatedBy($createdBy);
        $entity->setType($type);

        $count = 0;
        if ($repository->insertOnDuplicateKeyUpdate($entity)) {
            $quizQuestionTemplateRepository = $this->repositoryManager->getRepository(QuizQuestionTemplate::class);
            $id = $quizQuestionTemplateRepository->getLastInsertedId();

            foreach ($questions as $value) {
                $quizQuestionTemplateEntity = new QuizQuestionTemplate();
                $quizQuestionTemplateEntity->setQuizTemplateId($id);
                $quizQuestionTemplateEntity->setQuestionTemplateId($value);
                if ($quizQuestionTemplateRepository->insertOnDuplicateKeyUpdate($quizQuestionTemplateEntity)) {
                    $count++;
                }

            }

            return count($questions) === $count;
        }


        return $repository->insertOnDuplicateKeyUpdate($entity);
    }

    public function delete($request) {
        $repository = $this->repositoryManager->getRepository(QuizTemplate::class);
        $id = $request->getRequestParameters()['id'];

        $entity = $repository->find((int)$id);

        return $repository->delete($entity);
    }

    public function getUpdatePageParams(RequestInterface $request)
    {
        $id = $request->getRequestParameters()['id'];
        $repository = $this->repositoryManager->getrepository(QuizTemplate::class);
        $entity = $repository->find((int)$id);

        return $entity;
    }

    public function updateEntity($request, $session)
    {
        $repository = $this->repositoryManager->getRepository(QuizTemplate::class);
        $name = $request->getParameter('name');
        $type = $request->getParameter('type');
        $id = $request->getRequestParameters()['id'];

        $entity = $repository->find((int)$id);
        $entity->setName($name);
        $entity->setType($type);

        $count = 0;
        if ($repository->insertOnDuplicateKeyUpdate($entity))
        {
            $questions = $request->getparameter('questions');
            $quizQuestionTemplateRepository = $this->repositoryManager->getRepository(QuizQuestionTemplate::class);
            $quizQuestionTemplateRepository->deleteById($id);
            foreach ($questions as $value) {
                $quizQuestionTemplateEntity = new QuizQuestionTemplate();
                $quizQuestionTemplateEntity->setQuizTemplateId($id);
                $quizQuestionTemplateEntity->setQuestionTemplateId($value);
                if ($quizQuestionTemplateRepository->insertOnDuplicateKeyUpdate($quizQuestionTemplateEntity)){
                    $count++;
                }
            }

            return count($questions) === $count;
        }

        return false;
    }
}
