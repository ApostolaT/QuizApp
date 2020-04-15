<?php

namespace QuizApp\Controllers;

use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\QuestionService;
use QuizApp\Services\QuizService;
use QuizApp\Utils\PaginatorTrait;
use QuizApp\Utils\UrlHelperTrait;

/**
 * Class QuizController
 * @package QuizApp\Controllers
 */
class QuizController extends AbstractController
{
    use UrlHelperTrait;
    use PaginatorTrait;

    /**
     * @var QuizService
     */
    private $quizService;
    /**
     * @var QuestionService
     */
    private $questionService;

    /**
     * Setter Injection by Container
     * This function sets the $quizService
     * @param QuizService $quizService
     */
    public function setQuizService(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Setter Injection by Container
     * This function sets the $questionService
     * @param QuestionService $questionService
     */
    public function setQuestionService(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Gets all the entities and displays them either on
     * admin quizzes page or on user quizzes page
     * Depending on what user is requesting this info
     * @param RequestInterface $request
     * @return Response
     * @throws \Exception
     */
    public function listAll(RequestInterface $request)
    {
        if ($this->session->get('name') === null) {
            return $this->getRedirectPage("/error/404");
        }
        $path = ($this->session->get('role') === 'admin') ? "admin-quizzes-listing.phtml" : "candidate-quiz-listing.phtml";

        $renderParams = [
            'session' => $this->session,
            'message' => $this->session->get('message')
        ];

        //TODO Create a method to build a DTO for urlService and every findAll method.
        $sortParam = ($request->getParameter('sort')) ?? "";
        $filterParams = ($request->getParameter('type')) ?? "";
        $searchParam = ($request->getParameter('search')) ?? "";

        $renderParams["paginator"] = $paginator = $this->createCustomPaginator(
            $request,
            $this->quizService,
            $filterParams,
            $searchParam
        );
        $renderParams["urlHelper"] = $this->createUrlHelper($request, $paginator);
        $renderParams['entities'] => $this->quizService->getAll(
            $renderParams['paginator'],
            $filterParams,
            $searchParam,
            $sortParam
        );
        ];

        return $this->renderer->renderView($path, $renderParams);
    }
    /**
     * Creates a response with the page of quiz addition
     * @param RequestInterface $request
     * @return Response
     * @throws \Exception
     */
    public function goToAddQuiz(RequestInterface $request)
    {
        $role = $this->session->get('role');
        if ($role !== null && $role === 'admin') {
            $entities = $this->quizService->getType();
            $questionEntities = $this->questionService->selectAll();

            return
                $this->renderer->renderView(
                    "admin-quiz-details.phtml",
                    [
                        'session' => $this->session,
                        'entities' => $entities,
                        'question' => $questionEntities
                    ]
                );
        }

        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    /**
     * Gets the input from phtml's form and calls a function from service to
     * insert the quiz info into the repo
     * @param RequestInterface $request
     * @return \Framework\Http\Message|Response|\Psr\Http\Message\MessageInterface
     * @throws \Exception
     */
    public function addQuiz(RequestInterface $request)
    {
        $role = $this->session->get('role');
        if ($role !== null && $role === 'admin') {
            if ($this->quizService->createEntity($request, $this->session)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', '/quiz');

                return $response;
            }
            $this->goToAddQuiz($request);
        }

        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    /**
     * Calls the delete function from repo to delete the quiz entity from repo
     * @param RequestInterface $request
     * @return \Framework\Http\Message|Response|\Psr\Http\Message\MessageInterface
     * @throws \Exception
     */
    public function deleteQuiz(RequestInterface $request)
    {
        $role = $this->session->get('role');
        if ($role !== null && $role === 'admin') {
            if ($this->quizService->delete($request)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', '/quiz');

                return $response;
            }

            return $this->listAll($request);
        }
        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    /**
     * A function that renders the update page for a quiz
     * @param RequestInterface $request
     * @return Response
     * @throws \Exception
     */
    public function getUpdateQuizPage(RequestInterface $request)
    {
        $role = $this->session->get('role');
        if ($role !== null && $role === 'admin') {
            $entity = $this->quizService->getUpdatePageParams($request);
            $entities = $this->quizService->getType();
            $questionEntities = $this->questionService->selectAll();

            return
                $this->renderer->renderView(
                    'admin-quiz-details.phtml',
                    ['session' => $this->session,
                        'entity' => $entity,
                        'entities' => $entities,
                        'question' => $questionEntities]);
        }
        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    /**
     * This function takes the input from phtml's form and calls the service to update the entity
     * in the repo
     * @param RequestInterface $request
     * @return \Framework\Http\Message|Response|\Psr\Http\Message\MessageInterface
     * @throws \Exception
     */
    public function updateQuiz(RequestInterface $request)
    {
        $role = $this->session->get('role');
        if ($role !== null && $role === 'admin') {
            if ($this->quizService->updateEntity($request, $this->session)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', '/quiz');

                return $response;
            }
            $this->goToAddQuiz($request);
        }
    }
}