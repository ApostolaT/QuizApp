<?php


// TODO if not routematch go to not found

namespace QuizApp\Controllers;


use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use ReallyOrm\Exceptions\NoSuchRowException;

class QuestionController extends AbstractController
{
    private $questionService;

    public function setService(AbstractService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function listAll(RequestInterface $request)
    {
        // TODO create a check function for session
        if ($this->session->get('name') !== null) {
            try {
                $entities = $this->questionService->getAll($request);
                return $this->renderer->renderView("admin-questions-listing.phtml", ['session' => $this->session, 'entities' => $entities]);
            } catch (NoSuchRowException $e) {
                return $this->renderer->renderView("admin-questions-listing.phtml", ['session' => $this->session]);
            }
        }
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com');

        return $response;
    }

    public function goToAddQuestion(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
            return $this->renderer->renderView("admin-question-details.phtml", ['session' => $this->session]);
        }
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com');

        return $response;
    }

    public function addQuestion(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
            if ($this->questionService->createEntity($request, $this->session)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/question/1');

                return $response;
            }
            $this->goToAddQuestion($request);
        }
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com');

        return $response;
    }

    public function deleteQuestion(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
            if ($this->questionService->delete($request)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/question/1');

                return $response;
            }

            return $this->listAll($request);
        }
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com');

        return $response;
    }

    public function getUpdateQuestionPage(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
            $entities = $this->questionService->getUpdatePageParams($request);

            return $this->renderer->renderView('admin-question-details.phtml', ['session' => $this->session, 'question' => $entities["question"], 'answer'=>$entities["answer"]]);
        }
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com');

        return $response;
    }

    public function updateQuestion(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
            if ($this->questionService->updateEntity($request, $this->session)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/question/1');

                return $response;
            }
            $this->goToAddQuestion($request);
        }
    }
}