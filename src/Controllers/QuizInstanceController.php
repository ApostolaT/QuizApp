<?php


namespace QuizApp\Controllers;


use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;

class QuizInstanceController extends AbstractController
{
    private $quizInstanceService;

    public function setService(AbstractService $quizInstanceService)
    {
        $this->quizInstanceService = $quizInstanceService;
    }

    public function createQuizInstance(RequestInterface $request)
    {
        $role = $this->session->get('role');
        if ($role !== null && $role === 'user') {
            $quizTemplateId = $request->getRequestParameters()['id'];
            $userId = $this->session->get('id');

            $quizInstance = $this->quizInstanceService->isCreated($quizTemplateId, $userId);
            if ($quizInstance !== null)
            {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader(
                    'Location',
                    'http://local.quiz.com/quiz/question/0'
                );
                return $response;
            }

            $id = $this->quizInstanceService->createQuizInstance($quizTemplateId, $userId);
            if ($id !== null) {
                $this->session->set('quiz', $id);
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader(
                    'Location',
                    'http://local.quiz.com/questionInstance/add'
                );
                return $response;
            }
        }
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com/error/404');

        return $response;
    }
}