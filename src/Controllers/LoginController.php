<?php


namespace QuizApp\Controllers;

use Framework\Controller\AbstractController;
use Framework\Http\Stream;
use Psr\Http\Message\RequestInterface;
use QuizApp\Exceptions\userLoginException;
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

            if ($this->session->get('role') === 'admin') {
                return $this->renderer->renderView('admin-dashboard.phtml', ['session' => $this->session]);
            }
            if ($this->session->get('role') === 'user') {
                $response = new \Framework\Http\Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', '/quiz');

                return $response;
            }
        } catch (NoSuchRowException $e) {
            return  $this->renderer->renderView("login.phtml", ["error" => "Wrong input, just try again"]);
        } catch (userLoginException $e) {
            return  $this->renderer->renderView("login.phtml", ["error" => "Your password must not be an empty string"]);
        }
    }

    public function logoutAction(RequestInterface $request)
    {
        $this->session->destroy();
        return $this->renderer->renderView("login.phtml", []);
    }
}