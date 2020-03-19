<?php


namespace QuizApp\Controllers;

use Framework\Controller;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use QuizApp\Services\UserService;
use QuizApp\Utils\Paginator;
use ReallyOrm\Exceptions\NoSuchRowException;

class UserController extends Controller\AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    public function setService(AbstractService $userService)
    {
        $this->userService = $userService;
    }

    public function listAll(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->renderer->renderView("login.phtml", []);
        }

        $page = $request->getParameter('page');
        $paginator = $this->createPaginator((int)$page);
        try {
            $entities = $this->userService->getAll($paginator->getCurrentPage());
            return $this->renderer->renderView(
                "admin-users-listing.phtml",
                [
                    'session' => $this->session,
                    'entities' => $entities,
                    'paginator' => $paginator
                ]
            );
        } catch (NoSuchRowException $e) {
            return $this->renderer->renderView(
                "admin-users-listing.phtml",
                [
                    'session' => $this->session,
                    'paginator' => $paginator
                ]
            );
        }
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
        if ($this->session->get('role') !== 'admin') {
            return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
        }

        if ($this->userService->createEntity($request, $this->session)) {
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', 'http://local.quiz.com/user');

            return $response;
        }

        $this->goToAddUser($request);
    }

    public function deleteUser(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
        }

        if ($this->userService->delete($request)) {
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', 'http://local.quiz.com/user');

            return $response;
        }

        return $this->listAll($request);
    }

    public function getUpdateUserPage(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
        }

        $entity = $this->userService->getUpdatePageParams($request);
        return $this->renderer->renderView('admin-user-details.phtml', ['session' => $this->session, 'entity' => $entity]);
    }

    public function updateUser(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->renderer->renderView("login.phtml", $request->getRequestParameters());
        }

        if ($this->userService->updateEntity($request, $this->session)) {
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', 'http://local.quiz.com/user');

            return $response;
        }
    }

    private function createPaginator(int $page): Paginator
    {
        $totalResults = $this->userService->countRows()['rows'];
        $paginator = new Paginator($totalResults);
        if ($page) {
            $paginator->setCurrentPage($page);
        }

        return $paginator;
    }
}
