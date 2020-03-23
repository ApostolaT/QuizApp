<?php

namespace QuizApp\Controllers;

use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use QuizApp\Services\QuestionService;
use QuizApp\Services\QuizService;
use QuizApp\Utils\PaginatorTrait;
use ReallyOrm\Exceptions\NoSuchRowException;

/**
 * Class QuizController
 * @package QuizApp\Controllers
 */
class QuizController extends AbstractController
{
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
     * @param AbstractService $quizService
     */
    public function setQuizService(AbstractService $quizService)
    {
        $this->quizService = $quizService;
    }
    /**
     * Setter Injection by Container
     * This function sets the $questionService
     * @param AbstractService $questionService
     */
    public function setQuestionService(AbstractService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function listAll(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
            $path = ($this->session->get('role') === 'admin') ? "admin-quizzes-listing.phtml" : "candidate-quiz-listing.phtml";
            try {
                $entities = $this->quizService->getAll($request);
            } catch (NoSuchRowException $e) {

            }
        }

        return $this->renderer->renderView("login.phtml", []);
    }

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

    public function addQuiz(RequestInterface $request)
    {
        $role = $this->session->get('role');
        if ($role !== null && $role === 'admin') {
            if ($this->quizService->createEntity($request, $this->session)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/quiz/1');

                return $response;
            }
            $this->goToAddQuiz($request);
        }

        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    public function deleteQuiz(RequestInterface $request)
    {
        $role = $this->session->get('role');
        if ($role !== null && $role === 'admin') {
            if ($this->quizService->delete($request)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/quiz/1');

                return $response;
            }

            return $this->listAll($request);
        }
        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

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

    public function updateQuiz(RequestInterface $request)
    {
        $role = $this->session->get('role');
        if ($role !== null && $role === 'admin') {
            if ($this->quizService->updateEntity($request, $this->session)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/quiz/1');

                return $response;
            }
            $this->goToAddQuiz($request);
        }
    }
}