<?php


namespace QuizApp\Controllers;


use Framework\Controller\AbstractController;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use ReallyOrm\Exceptions\NoSuchRowException;

class QuizController extends AbstractController
{
    private $quizService;

    public function setService(AbstractService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function listAll(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
            try {
                $entities = $this->quizService->getAll($request);
            } catch (NoSuchRowException $e) {
                return $this->renderer->renderView("admin-quizzes-listing.phtml", ['session' => $this->session]);
            }
        }

        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    public function goToAddPage(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
//            $entities = $this->quizService->getType($request);

            return $this->renderer->renderView("admin-quiz-details.phtml", ['session' => $this->session]);
        }

        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }
}