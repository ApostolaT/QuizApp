<?php


namespace QuizApp\Controllers;


use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Framework\Http\Stream;
use phpDocumentor\Reflection\Location;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use ReallyOrm\Exceptions\NoSuchRowException;


class QuizController extends AbstractController
{
    private $quizService;
    private $questionService;

    public function setQuizService(AbstractService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function setQuestionService(AbstractService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function listAll(RequestInterface $request)
    {
        // TODO create a check function for session
        if ($this->session->get('name') !== null) {
            try {
                $entities = $this->quizService->getAll($request);
                return $this->renderer->renderView("admin-quizzes-listing.phtml", ['session' => $this->session, 'entities' => $entities]);
            } catch (NoSuchRowException $e) {
                return $this->renderer->renderView("admin-quizzes-listing.phtml", ['session' => $this->session]);
            }
        }

        return $this->renderer->renderView("login.phtml", []);
    }

    public function goToAddQuiz(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
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
        if ($this->session->get('name') !== null) {
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
        if ($this->session->get('name') !== null) {
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
        if ($this->session->get('name') !== null) {
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
        if ($this->session->get('name') !== null) {
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