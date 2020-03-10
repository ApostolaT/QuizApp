<?php


namespace QuizApp\Controllers;

use Framework\Controller\AbstractController;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use ReallyOrm\Exceptions\NoSuchRowException;

class LoginController extends AbstractController
{
    private $loginService;

    public function setService(AbstractService $service)
    {
        $this->loginService = $service;
    }

    public function getLoginPage(RequestInterface $request)
    {
        if($this->session->get('name') === null) {
            return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
        }

        return $this->loginAction($request);
    }

    public function loginAction(RequestInterface $request)
    {
        try {
            if($this->session->get('name') === null) {
                $this->session = $this->loginService->userLogIn($request, $this->session);
            }
            return
                ($this->session->get('role') === 'admin') ?
                    $this->renderer->renderView("admin-dashboard.phtml", ["session" => $this->session])
                    : $this->renderer->renderView("candidate-quiz-listing.html", ["session" => $this->session]);
        } catch (NoSuchRowException $e) {
            return  $this->renderer->renderView("login.phtml", ["error" => "Wrong input, just try again"]);
        }// TODO catch exception
    }

    public function logoutAction(RequestInterface $request)
    {
        $this->session->destroy();
        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }
}