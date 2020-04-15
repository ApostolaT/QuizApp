<?php

namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use QuizApp\Services\MessageService;
use QuizApp\Services\UserService;
use QuizApp\Utils\PaginatorTrait;
use QuizApp\Utils\UrlHelperTrait;

class UserController extends AbstractController
{
    use UrlHelperTrait;
    use PaginatorTrait;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param MessageService $messageService
     */
    public function __construct(RendererInterface $renderer, MessageService $messageService)
    {
        parent::__construct($renderer);
        $this->messageService = $messageService;
    }

    /**
     * This function sets the userService
     * @param AbstractService $userService
     */
    public function setService(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param MessageService $messageService
     */
    public function setMessageService(MessageService $messageService)
    {
        $this->messageService = $messageService;
        $this->messageService->setSession($this->session);
    }

    /**
     * This function if called by an admin displays the current page
     * with all the users from the system. If a user calls to see all
     * the users, he is redirected to a login page;
     * @param RequestInterface $request
     * @return Response
     */
    public function listAll(RequestInterface $request): Response
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->getRedirectPage('/');
        }

        $renderParams = [
            'message' => $this->messageService,
            //TODO Extract needed params like message
            'session' => $this->session,
        ];

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
     * @return Response
     */
    public function getNewUserPage(RequestInterface $request): Response
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->getRedirectPage("/");
        }

        return $this->renderer->renderView("admin-user-details.phtml", ['session' => $this->session]);
    }

    /**
     * This function is called when an admin adds a new user to the system.
     * If not an  +
     * +admin calls the function, he is redirected to login.
     * If the insertion of the new user is a success, the response will contain
     * a success message, else it will contain an error message.
     * @param RequestInterface $request
     * @return Response
     */
    public function addUser(RequestInterface $request): Response
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->getRedirectPage("/");
        }

        $email = $request->getParameter('email');
        $role = $request->getParameter('role');
        $operationStatus = $this->userService->addNewUser($email, $role);
        $this->messageService->addMessage(
            $operationStatus,
            "user",
            $email,
            "added"
        );

        return $this->getRedirectPage("/user");
    }

    /**
     * This function if called by an admin deletes a user,
     * else it will redirect the user to login.
     * If the deletion is successful, the response will contain
     * a success message, else it will contain an error.
     * @param RequestInterface $request
     * @return Response
     */
    public function deleteUser(RequestInterface $request): Response
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->getRedirectPage("/");
        }

        $id = $request->getRequestParameters()['id'];
        $operationStatus = $this->userService->delete($id);
        $this->messageService->addMessage(
            $operationStatus,
            "user",
            $id,
            "deleted"
        );

        return $this->getRedirectPage("/user");
    }

    /**
     * This function is called to return a
     * response with the update users page
     * containing all the info of that user
     * @param RequestInterface $request
     * @return Response
     */
    public function getUpdateUserPage(RequestInterface $request): Response
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->getRedirectPage("/");
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
     * @return Response
     */
    public function updateUser(RequestInterface $request): Response
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->getRedirectPage("/");
        }

        $name = $request->getParameter('email');
        $role = $request->getParameter('role');
        $id = $request->getRequestParameters()['id'];
        $operationStatus = $this->userService->updateUser($name, $role, $id);
        $this->messageService->addMessage(
            $operationStatus,
            "user",
            $id,
            "updated"
        );

        return $this->getRedirectPage("/user");
    }
}
