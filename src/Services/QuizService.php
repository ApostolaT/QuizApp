<?php

namespace QuizApp\Services;

use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\QuizQuestionTemplate;
use QuizApp\Entities\QuizTemplate;
use QuizApp\Entities\QuizType;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class QuizService extends AbstractService
{
    /**
     * Constant for pagination.
     */
    private const RESULTS_PER_PAGE = 10;
    /**
     * @var RepositoryManager
     */
    private $repositoryManager;
    /**
     * This setter is injected in Container file
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }
    /**
     * Gets all the Quizzes templates from the system
     * @return array|null
     * @throws \Exception
     */
    public function getAll(int $page): ?array
    {
        $from = ($page - 1) * $this::RESULTS_PER_PAGE;
        $repository = $this->repositoryManager->getRepository(QuizTemplate::class);
        try {
            $entities = $repository->findBy([], [], $this::RESULTS_PER_PAGE, $from);
        } catch (NoSuchRowException $e) {
            $entities = null;
        }
        return $entities;
    }
    /**
     * Counts how many quiz entities the Repository has.
     * @return mixed
     * @throws \Exception
     */
    public function countRows()
    {
        $quizInstanceRepository = $this->repositoryManager->getRepository(QuizTemplate::class);

        return $quizInstanceRepository->countRows();
    }

    /**
     * Gets all the entities from the repository
     * @return array|\ReallyOrm\Entity\EntityInterface[]
     * @throws \Exception
     */
    public function selectAll()
    {
        $repository = $this->repositoryManager->getRepository(QuizTemplate::class);

        return $repository->findBy([], [], 0, 0);
    }

    /**
     * Get all quiz types. This function is called to display the types on quiz add/edit page
     * @return array|\ReallyOrm\Entity\EntityInterface[]
     * @throws \Exception
     */
    public function getType()
    {
        $repository = $this->repositoryManager->getRepository(QuizType::class);

        // TODO create a findAll in abstractRepository
        return $repository->findBy([], [], 0, 0);
    }

    /**
     * Creates a quiz template entity in repository
     * @param $request
     * @param $session
     * @return bool
     * @throws \Exception
     */
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
    /**
     * Deletes an entity from repository by id
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function delete($request) {
        $repository = $this->repositoryManager->getRepository(QuizTemplate::class);
        $id = $request->getRequestParameters()['id'];

        $entity = $repository->find((int)$id);

        return $repository->delete($entity);
    }

    /**
     * Gets entity info from repository by quiz id
     * @param RequestInterface $request
     * @return \ReallyOrm\Entity\EntityInterface|null
     * @throws \Exception
     */
    public function getUpdatePageParams(RequestInterface $request)
    {
        $id = $request->getRequestParameters()['id'];
        $repository = $this->repositoryManager->getrepository(QuizTemplate::class);
        $entity = $repository->find((int)$id);

        return $entity;
    }

    /**
     * this function is called when an entity is being edited.
     * It inserts the new info about entity in the same place in repository
     * by id
     * @param $request
     * @param $session
     * @return bool
     * @throws \Exception
     */
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
