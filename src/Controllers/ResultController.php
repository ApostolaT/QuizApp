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

        $textInstnaces = [];
        foreach ($questionInstanceEntities as $key => $value) {
            $textInstnaces[] = $this->resultService->getTextInstance($value->getId());
        }

        return $this->renderer->renderView(
            "admin-score.phtml",
            [
                'session' => $this->session,
                'user' => $userId,
                'questions' => $questionInstanceEntities,
                'answers' => $textInstnaces
            ]);
    }
}