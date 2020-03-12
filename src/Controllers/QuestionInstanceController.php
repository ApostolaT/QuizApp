<?php


namespace QuizApp\Controllers;


use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Framework\Http\Stream;
use http\Env\Request;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;

class QuestionInstanceController extends AbstractController
{
    private $questionInstanceService;

    public function setService(AbstractService $questionInstanceService)
    {
        $this->questionInstanceService = $questionInstanceService;
    }

    public function createQuestionInstances(RequestInterface $request)
    {
        $quizInstanceId = $this->session->get('quiz');

        if ($quizInstanceId !== null) {
            if ($this->questionInstanceService->createQuestionInstances((int)$quizInstanceId)) {
                $this->session->set('offset', 0);
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/quiz/question/0');

                return $response;
            }
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', 'http://local.quiz.com/error/404');

            return $response;
        }
    }

    public function getQuestionPage(RequestInterface $request) {
        $quizInstanceId = $this->session->get('quiz');
        if ($quizInstanceId !== null) {
            $offset = $request->getRequestParameters()['offset'];

            $questionInstanceEntity = $this->questionInstanceService->getQuestionInstance($quizInstanceId, $offset)[0];

            if ($questionInstanceEntity !== null) {
                $textInstanceEntity = $this->questionInstanceService->getTextInstance($questionInstanceEntity->getId());

                $this->session->set('offset', $offset);
                return $this->renderer->renderView(
                    'candidate-quiz-page.phtml',
                    [
                        'session' => $this->session,
                        'questionInstance' => $questionInstanceEntity->getText(),
                        'textInstance' => $textInstanceEntity->getText()
                    ]
                );
            }
        }
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com/error/404');

        return $response;
    }

    public function updateTextInstance(RequestInterface $request)
    {
        $quizInstanceId = $this->session->get('quiz');
        if ($quizInstanceId !== null) {
            $offset = $this->session->get('offset');
            $text = $request->getParameter('text');

            $questionInstanceEntity = $this->questionInstanceService->getQuestionInstance($quizInstanceId, $offset)[0];
            $textInstanceEntity = $this->questionInstanceService->getTextInstance($questionInstanceEntity->getId());
            $textInstanceEntity->setText($text);

            $this->questionInstanceService->saveTextInstance($textInstanceEntity);

            $count = $this->questionInstanceService->countQuestion($quizInstanceId) - 1;
            if ($count === (int)$offset) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/quiz/completed');

                return $response;
            }

            $offset++;
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', 'http://local.quiz.com/quiz/question/' . $offset);

            return $response;
        }
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com/error/404');

        return $response;
    }

    public function getReviewPage(RequestInterface $request)
    {
        $quizInstanceId = $this->session->get('quiz');
        $questionInstanceEntities = $this->questionInstanceService->getQuestionsInstance($quizInstanceId);

        $textInstnaces = [];
        foreach ($questionInstanceEntities as $key => $value) {
            $textInstnaces[] = $this->questionInstanceService->getTextInstance($value->getId());
        }

        return $this->renderer->renderView(
            "admin-results.phtml",
            [
                'session' => $this->session,
                'questions' => $questionInstanceEntities,
                'answers' => $textInstnaces
                ]);
    }

    public function getCongrats(RequestInterface $request)
    {
        $this->session->delete('quiz');
        $this->session->delete('offset');
        return $this->renderer->renderView('quiz-success-page.phtml', ['session' => $this->session]);
    }
}