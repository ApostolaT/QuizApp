<?php


namespace QuizApp\Controllers;


use Framework\Controller\AbstractController;
use Psr\Http\Message\RequestInterface;

class ErrorController extends AbstractController
{
    public function getErrorPage(RequestInterface $request)
    {
        if ($this->session->get('name') !== null) {
            return $this->renderer->renderView("exceptions-page.phtml", ['session' => $this->session]);
        }
        return $this->renderer->renderView("error-logout.html", []);
    }
}