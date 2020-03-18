<?php

namespace QuizApp\Controllers;

use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use ReallyOrm\Exceptions\NoSuchRowException;

/**
 * Class ResultController
 * @package QuizApp\Controllers
 */
//TODO local.quiz.com/results?page=1
class ResultController extends AbstractController
{
    private $resultService;

    public function setService(AbstractService $resultService)
    {
        $this->resultService = $resultService;
    }

    /**
     * This function renders for admin all the quizzes that've been taken by users.
     * It calls the resultService for QuizInstanceDTOs which contain
     * userName, quizName, and the Score of the user taken quiz.
     * if the process is successful, the Renderer gets those DTOs, else
     * the rendered will just display and empty results page
     * @return Response
     */
    public function getAllUserTest(RequestInterface $request): Response
    {
        $role = $this->session->get('role');

        //Only admins ca see all ever taken quizzes
        if ($role !== 'admin') {
            return $this->getRedirectPage("http://local.quiz.com/error/404");
        }

        //We need session for the Header
        $viewParams = ['session' => $this->session];
        try {
            $page = $request->getRequestParameters();
            $viewParams['quizzes'] = $this->resultService->getAllUserTests($page);
            return $this->renderer->renderView("admin-results-listing.phtml", $viewParams);
        } catch (NoSuchRowException $e) {
            return $this->renderer->renderView("admin-results-listing.phtml", $viewParams);
        }
    }

    /**
     * This function is called when a user finishes answering the last question
     * from quiz and presses Next. The Review page will be displayed on his screen.
     * This function calls the ResultService for all the QuestionDTOs related to the
     * taken quiz. QuestionDTOs contains pairs of questionText and answerText.
     * @return Response
     */
    public function getUserResultPage(RequestInterface $request): Response
    {
        $role = $this->session->get('role');

        //Just the user that've given the quiz can see his current quiz review
        if ($role !== 'user') {
            return $this->getRedirectPage("http://local.quiz.com/error/404");
        }

        $quizInstanceId = $request->getRequestParameters()['quizId'];
        $questionDTOs = $this->resultService->getQuestionsAnswersForQuizWithId($quizInstanceId);

        if ($questionDTOs === null) {
            return $this->getRedirectPage("http://local.quiz.com/error/404");
        }

        $viewParams = [
            'session' => $this->session,
            'questions' => $questionDTOs
        ];

        return $this->renderer->renderView('user-results.phtml', $viewParams);
    }

    /**
     * This function is called when an admin wants to see the answered questions
     * from a quiz taken by any user. The functionality is the same
     * as for the getUserResultPage() function.
     * @return Response
     */
    public function getAdminResultPage(RequestInterface $request): Response
    {
        $role = $this->session->get('role');

        //Only an admin can see the score page-
        if ($role !== 'admin') {
            return $this->getRedirectPage("http://local.quiz.com/error/404");
        }

        $quizInstanceId = $request->getRequestParameters()['quizId'];
        $questionDTOs = $this->resultService->getQuestionsAnswersForQuizWithId($quizInstanceId);

        if ($questionDTOs === null) {
            return $this->getRedirectPage("http://local.quiz.com/error/404");
        }

        $userId = $request->getRequestParameters()['userId'];
        $viewParams = [
            'session' => $this->session,
            'user' => $userId,
            'quizInstanceId' => $quizInstanceId,
            'questions' => $questionDTOs
        ];

        return $this->renderer->renderView('admin-score.phtml', $viewParams);
    }

    /**
     * This function is called to score a quiz given by any user. It calls the scoreResult
     * method from resultService class with the provided score and the id of the quiz.
     * @return Response
     */
    public function scoreQuiz(RequestInterface $request): Response
    {
        $role = $this->session->get('role');

        //Only an admin can score a quiz
        if ($role !== 'admin') {
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
