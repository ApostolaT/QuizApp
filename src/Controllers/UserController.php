<?php


namespace QuizApp\Controllers;

use Framework\Controller;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Session\Session;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use ReallyOrm\Exceptions\NoSuchRowException;

class UserController extends Controller\AbstractController
{
    private $userService;

    public function setService(AbstractService $userService)
    {
        $this->userService = $userService;
    }

    public function listAll(RequestInterface $request)
    {
        // TODO create a check function for session
        if ($this->session->get('role') === 'admin') {
            try {
                $entities = $this->userService->getAll($request);
                return $this->renderer->renderView("admin-users-listing.phtml", ['session' => $this->session, 'entities' => $entities]);
            } catch (NoSuchRowException $e) {
                return $this->renderer->renderView("admin-users-listing.phtml", ['session' => $this->session]);
            }
        }

        return $this->renderer->renderView("login.phtml", []);
    }

    public function goToAddUser(RequestInterface $request)
    {
        if ($this->session->get('role') === 'admin') {
            return $this->renderer->renderView("admin-user-details.phtml", ['session' => $this->session]);
        }

        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    public function addUser(RequestInterface $request)
    {
        if ($this->session->get('role') === 'admin') {
            if ($this->userService->createEntity($request, $this->session)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/user/1');

                return $response;
            }
            $this->goToAddUser($request);
        }

        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    public function deleteUser(RequestInterface $request)
    {
        if ($this->session->get('role') === 'admin') {
            if ($this->userService->delete($request)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/user/1');

                return $response;
            }

            return $this->listAll($request);
        }
        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    public function getUpdateUserPage(RequestInterface $request)
    {
        if ($this->session->get('role') === 'admin') {
            $entity = $this->userService->getUpdatePageParams($request);

            return $this->renderer->renderView('admin-user-details.phtml', ['session' => $this->session, 'entity' => $entity]);
        }
        return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
    }

    public function updateUser(RequestInterface $request)
    {
        if ($this->session->get('role') === 'admin') {
            if ($this->userService->updateEntity($request, $this->session)) {
                $response = new Response(Stream::createFromString(' '), []);
                $response = $response->withStatus(301);
                $response = $response->withHeader('Location', 'http://local.quiz.com/user/1');

                return $response;
            }
            $this->goToAddUser($request);
        }
    }
}
