<?php

// TODO if not routematch go to not found
namespace QuizApp\Controllers;

use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use QuizApp\Services\QuestionService;
use QuizApp\Utils\PaginatorTrait;

class QuestionController extends AbstractController
{
    use PaginatorTrait;
    /**
     * @var QuestionService
     */
    private $questionService;
    /**
     * This function sets the questionService.
     * Its injected from container
     * @param AbstractService $service
     */
    public function setService(AbstractService $service)
    {
        $this->questionService = $service;
    }
    /**
     * This function renders all the questions from the repo in pages.
     * The questions are taken from repo by calling the corresponding method from service,
     * and paginated by using the paginator trait.
     * @param RequestInterface $request
     * @return Response
     */
    public function listAll(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->getRedirectPage("http://local.quiz.com/error/404");
        }
        $paginator = $this->createPaginationForRequestWithService($request, $this->questionService);
        $renderParams = [
            'session' => $this->session,
            //message is for the future messages that will be displayed
            //on admin-results-listing.phtml page
            'message' => $this->session->get('message'),
            'paginator' => $paginator,
            'entities' =>  $this->questionService->getAll($paginator->getCurrentPage())
        ];

        return $this->renderer->renderView('admin-questions-listing.phtml', $renderParams);
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
            if ($this->questionService->createEntity($request)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/question');

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
                $response = $response->withHeader('Location', 'http://local.quiz.com/question');

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
                $response = $response->withHeader('Location', 'http://local.quiz.com/question');

                return $response;
            }
            $this->goToAddQuestion($request);
        }
    }
}