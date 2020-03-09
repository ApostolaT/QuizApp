<?php


namespace QuizApp\Controllers;

use Framework\Controller\AbstractController;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use ReallyOrm\Exceptions\NoSuchRowException;

class LoginController extends AbstractController
{
    private $loginService;
    private $session;

    public function setService(AbstractService $service)
    {
        $this->loginService = $service;
    }

    public function getLoginPage(RequestInterface $request)
    {
        $response = $this->renderer->renderView("login.phtml", $request->getRequestParameters());
        return $response;
    }

    public function loginAction(RequestInterface $request)
    {
        try {
            $this->session = $this->loginService->userLogIn($request);
            return
                ($this->session->get('role') === 'admin') ?
                    $this->renderer->renderView("admin-dashboard.phtml", ["session" => $this->session])
                    : $this->renderer->renderView("candidate-quiz-page.html", ["session" => $this->session]);
        } catch (NoSuchRowException $e) {
            return  $this->renderer->renderView("login.phtml", ["error" => "Wrong input, just try again"]);
        }
    }
}