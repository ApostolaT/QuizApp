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
        $role = $this->session->get('role');

        if ($role !== null) {
            $quizInstanceId = $request->getRequestParameters()['quizId'];
            $questionDTOs = $this->resultService->getQuestionsAnswersForQuizWithId($quizInstanceId);

            if ($questionDTOs === null) {
                return $this->getRedirectPage("http://local.quiz.com/error/404");
            }

            if ($role === 'admin') {
                $userId = $request->getRequestParameters()['userId'];
                return $this->renderer->renderView(
                    "admin-score.phtml",
                    [
                        'session' => $this->session,
                        'user' => $userId,
                        'quizInstanceId' => $quizInstanceId,
                        'questions' => $questionDTOs
                    ]);
            }

            return $this->renderer->renderView(
                'admin-results.phtml',
                [
                    'session' => $this->session,
                    'questions' => $questionDTOs
                ]
            );
        }
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