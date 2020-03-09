<?php


namespace QuizApp\Controllers;

use Framework\Controller;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;

class UserController extends Controller\AbstractController
{
    private $userService;

    public function setService(AbstractService $userService)
    {
        $this->userService = $userService;
    }

    public function getId(RequestInterface $request)
    {

        $response = $this->renderer->renderView("render.phtml", $request->getRequestParameters());
        return $response;
    }

    public function postAll(RequestInterface $request)
    {
        $postBody = $request->getBody();
        $postBody = $postBody->getContents();

        return $this->renderer->renderView("render.phtml", $request->getRequestParameters());
    }

    public function deleteUser(RequestInterface $request)
    {
        return  $this->renderer->renderJson($request->getRequestParameters());
    }
}
