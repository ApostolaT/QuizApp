<?php


namespace QuizApp\Controllers;


use Framework\Controller\AbstractController;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use ReallyOrm\Exceptions\NoSuchRowException;

class ResultController extends AbstractController
{
    private $resultService;

    public function setService(AbstractService $resultService)
    {
        $this->resultService = $resultService;
    }

    public function getAllUserTest(RequestInterface $request)
    {
        try {
            $results = $this->resultService->getAllUserTests($request);
            return $this->renderer->renderView
            (
                "admin-results-listing.phtml",
                [
                    'session' => $this->session,
                    'results' => $results
                ]
            );
        } catch (NoSuchRowException $e) {
            return $this->renderer->renderView("admin-results-listing.phtml", ['session' => $this->session]);
        }
    }

    public function getScorePage(RequestInterface $request)
    {
        $quizInstanceId = $request->getRequestParameters()['quizId'];
        $userId = $request->getRequestParameters()['userId'];
        $questionInstanceEntities = $this->resultService->getQuestionsInstance($quizInstanceId);

        $textInstances = [];
        foreach ($questionInstanceEntities as $key => $value) {
            $textInstance = $this->resultService->getTextInstance($value->getId());
            $textInstance[0]->setText($this->codeHighlight->highlight($textInstance[0]->getText()));
            $textInstances[] = $textInstance;
        }

        return $this->renderer->renderView(
            "admin-score.phtml",
            [
                'session' => $this->session,
                'user' => $userId,
                'quizInstanceId' => $quizInstanceId,
                'questions' => $questionInstanceEntities,
                'answers' => $textInstances
            ]);
    }

    public function scoreQuiz(RequestInterface $request)
    {
        $role = $this->session->get('role');

        if ($role === null || $role === 'user') {
            return $this->getRedirectPage('http://local.quiz.com/error/404');
        }

        $score = $request->getParameter('score');
        $quizInstanceId = $request->getRequestParameters()['quizId'];
        if (!$this->resultService->scoreResult($score, $quizInstanceId)) {
            return $this->getRedirectPage('http://local.quiz.com/error/404');
        }

        return $this->getRedirectPage('http://local.quiz.com/results/1');
    }
}