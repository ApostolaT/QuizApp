<?php

namespace QuizApp\Controllers;

use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use QuizApp\Services\UserService;
use QuizApp\Utils\PaginatorTrait;
use QuizApp\Utils\UrlHelperTrait;

//TODO redirect using the getRedirectPage
class UserController extends AbstractController
{
    use UrlHelperTrait;
    use PaginatorTrait;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * This function sets the userService
     * @param AbstractService $userService
     */
    public function setService(AbstractService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * This function if called by an admin displays the current page
     * with all the users from the system. If a user calls to see all
     * the users, he is redirected to a login page;
     * @param RequestInterface $request
     * @return \Framework\Http\Message|Response|\Psr\Http\Message\MessageInterface
     */
    public function listAll(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->getRedirectPage('/');
        }

        $renderParams = [
            'message' => $this->session->get('message'),
            'session' => $this->session,
        ];
        $this->session->delete('message');

        $sortParam = ($request->getParameter('sort')) ?? "";
        $filterParams = ($request->getParameter('role')) ?? "";
        $searchParam = ($request->getParameter('email')) ?? "";
        //Create paginator based on filters and searchParams
        $renderParams['paginator'] = $this->createCustomPaginator(
            $request,
            $this->userService,
            $filterParams,
            $searchParam
        );
        $renderParams['urlHelper'] = $this->createUrlHelper($request, $renderParams['paginator']);
        $renderParams['entities'] = $this->userService->getAll($renderParams['paginator']->getCurrentPage(), $filterParams, $searchParam, $sortParam);

        return $this->renderer->renderView('admin-users-listing.phtml', $renderParams);
    }

    /**
     * This function is called to return a
     * response with the add users page
     * @param RequestInterface $request
     * @return \Framework\Http\Message|Response|\Psr\Http\Message\MessageInterface
     */
    public function goToAddUser(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', '/');

            return $response;
        }

        return $this->renderer->renderView("admin-user-details.phtml", ['session' => $this->session]);
    }

    /**
     * This function is called when an admin adds a new user to the system.
     * If not an admin calls the function, he is redirected to login.
     * If the insertion of the new user is a success, the response will contain
     * a success message, else it will contain an error message.
     * @param RequestInterface $request
     * @return \Framework\Http\Message|Response|\Psr\Http\Message\MessageInterface
     */
    public function addUser(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', '/');

            return $response;
        }

        $message = ($this->userService->addNewUser($request)) ?
            "Success." : "User addition failed!";
        $this->session->set('message', $message);
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', '/user');

        return $response;
    }

    /**
     * This function if called by an admin deletes a user,
     * else it will redirect the user to login.
     * If the deletion is successful, the response will contain
     * a success message, else it will contain an error.
     * @param RequestInterface $request
     * @return \Framework\Http\Message|Response|\Psr\Http\Message\MessageInterface
     */
    public function deleteUser(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', '/');

            return $response;
        }

        $message = ($this->userService->delete($request)) ?
            "Success" : "Delete Failed";
        $this->session->set('message', $message);
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', '/user');

        return $response;
    }

    /**
     * This function is called to return a
     * response with the update users page
     * containing all the info of that user
     * @param RequestInterface $request
     * @return \Framework\Http\Message|Response|\Psr\Http\Message\MessageInterface
     */
    public function getUpdateUserPage(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', '/');

            return $response;
        }

        $renderParams = [
            'session' => $this->session,
            'entity' => $this->userService->getUpdatePageParams($request)
        ];
        return $this->renderer->renderView('admin-user-details.phtml', $renderParams);
    }

    /**
     * This function is called when an admin edits a user from the system.
     * If not an admin calls the function, he is redirected to login.
     * If the update of the user is a success, the response will contain
     * a success message, else it will contain an error message.
     * @param RequestInterface $request
     * @return \Framework\Http\Message|Response|\Psr\Http\Message\MessageInterface
     */
    public function updateUser(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            $response = new Response(Stream::createFromString(' '), []);
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', '/');

            return $response;
        }

        $message = ($this->userService->updateEntity($request)) ?
            "Success" : "Update Failed";
        $this->session->set('message', $message);
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', '/user');

        return $response;
    }
}
