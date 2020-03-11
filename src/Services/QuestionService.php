<?php


namespace QuizApp\Services;


use Framework\Controller\AbstractController;
use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\QuestionTemplate;
use QuizApp\Entities\TextTemplate;
use QuizApp\Repositories\QuestionRepository;
use QuizApp\Repositories\TextRepository;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionService extends AbstractService
{
    private $repositoryManager;

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function selectAll()
    {
        $repository = $this->repositoryManager->getRepository(QuestionRepository::class);

        return $repository->findBy([], [], 0, 0);
    }

    public function getAll(RequestInterface $request)
    {
        $page = $request->getRequestParameters();
        $from = ($page['page'] - 1) * 10;

        $repository = $this->repositoryManager->getRepository(QuestionRepository::class);

        $entities = $repository->findBy([], [], 10, 0);

        return $entities;
    }

    public function createEntity($request)
    {
        $questionRepository = $this->repositoryManager->getRepository(QuestionRepository::class);
        $text = $request->getParameter('question');
        $answer = $request->getParameter('answer');
        $type = $request->getParameter('type');

        $repositoryType = "QuizApp\Repositories\\".ucfirst(strtolower($type))."Repository";
        $textRepository = $this->repositoryManager->getRepository($repositoryType);

        // TODO check the answer if empty

        $entity = new QuestionTemplate();
        $entity->setText($text);
        $entity->setType($type);

        if ($questionRepository->insertOnDuplicateKeyUpdate($entity)) {
            $id = $questionRepository->getLastInsertedId();
            $questionType = new TextTemplate();
            $questionType->setText($answer);
            $questionType->setQuestionTemplateId($id);

             return $textRepository->insertOnDuplicateKeyUpdate($questionType);
        }

        return false;
    }

    public function delete($request) {
        $repository = $this->repositoryManager->getRepository(QuestionRepository::class);

        $id = $request->getRequestParameters()['id'];
        $entity = $repository->find((int)$id);

        $type = $entity->getType();
        $repositoryType = "QuizApp\Repositories\\".ucfirst(strtolower($type))."Repository";
        $textRepository = $this->repositoryManager->getRepository($repositoryType);

        $answer = $textRepository->findOneBy(["questionTemplateId" => (int)$id]);

        return ($textRepository->delete($answer) && $repository->delete($entity));
    }

    public function getUpdatePageParams(RequestInterface $request)
    {
        $id = $request->getRequestParameters()['id'];
        $repository = $this->repositoryManager->getrepository(QuestionRepository::class);
        $entity = $repository->find((int)$id);

        $type = $entity->getType();
        $repositoryType = "QuizApp\Repositories\\".ucfirst(strtolower($type))."Repository";
        $textRepository = $this->repositoryManager->getRepository($repositoryType);

        $answer = $textRepository->findOneBy(["questionTemplateId" => (int)$id]);

        return ["question" =>$entity, "answer" => $answer];
    }

    public function updateEntity($request, $session)
    {
        //TODO make update in quizzes like here
        $repository = $this->repositoryManager->getRepository(QuestionRepository::class);
        $id = $request->getRequestParameters()['id'];
        $questionEntity = $repository->find((int)$id);

        $type = $questionEntity->getType();
        $repositoryType = "QuizApp\Repositories\\".ucfirst(strtolower($type))."Repository";
        $textRepository = $this->repositoryManager->getRepository($repositoryType);
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