<?php

namespace QuizApp\Controllers;

use Framework\Controller\AbstractController;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use QuizApp\Services\UserService;
use QuizApp\Utils\Paginator;

//TODO redirect using the getRedirectPage
class UserController extends AbstractController
{
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
     * @throws \Exception
     */
    public function listAll(RequestInterface $request)
    {
        if ($this->session->get('role') !== 'admin') {
            return $this->getRedirectPage('http://local.quiz.com/');
        }

        $renderParams = $this->getRenderParams($request);
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
            $response = $response->withHeader('Location', 'http://local.quiz.com/');

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
            $response = $response->withHeader('Location', 'http://local.quiz.com/');

            return $response;
        }

        $message = ($this->userService->addNewUser($request)) ?
            "Success." : "User addition failed!";
        $this->session->set('message', $message);
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com/user');

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
            $response = $response->withHeader('Location', 'http://local.quiz.com/');

            return $response;
        }

        $message = ($this->userService->delete($request)) ?
            "Success" : "Delete Failed";
        $this->session->set('message', $message);
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com/user');

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
            $response = $response->withHeader('Location', 'http://local.quiz.com/');

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
            $response = $response->withHeader('Location', 'http://local.quiz.com/');

            return $response;
        }

        $message = ($this->userService->updateEntity($request)) ?
            "Success" : "Update Failed";
        $this->session->set('message', $message);
        $response = new Response(Stream::createFromString(' '), []);
        $response = $response->withStatus(301);
        $response = $response->withHeader('Location', 'http://local.quiz.com/user');

        return $response;
    }
    /**
     * This function constructs the render parameters based on role.
     * If the role is empty or "all" it will extract all user entities from repo.
     * Else it will extract all "user" entities or "admin" entities.
     * Also the paginator depends on the role.
     * @param RequestInterface $request
     * @return array
     * @throws \Exception
     */
    private function getRenderParams(RequestInterface $request): array
    {
        $paginator = $this->createPaginationForRequest($request);
        $renderParams = [
            'message' => $this->session->get('message'),
            'session' => $this->session,
            'role' => $request->getParameter('role'),
            'paginator' => $paginator
        ];
        $this->session->delete('message');

        if($request->getParameter('role') !== 'admin' && $request->getParameter('role') !== 'user') {
            $renderParams['entities'] = $this->userService->getAll($paginator->getCurrentPage());
            return $renderParams;
        }

        $renderParams['entities'] =
            $this->getRenderParamsBasedOnRoleAndPage(
                $request->getParameter('role'),
                $paginator->getCurrentPage()
            );

        return $renderParams;
    }
    /**
     * This function extracts from Repository the user entities calling
     * the selectAllLikeFromPage function associated to the userService,
     * passing the role value and the page value
     * @param string $role
     * @param int $currentPage
     * @return array|null
     * @throws \Exception
     */
    private function getRenderParamsBasedOnRoleAndPage(string $role, int $currentPage): ?array
    {
        if ($role === 'admin') {
            return $this->userService->selectAllLikeFromPage($role, $currentPage);
        }

        return $this->userService->selectAllLikeFromPage('user', $currentPage);
    }
    /**
     * This function is called to create a paginator for
     * all users page based on role. If roll is different from
     * user or admin, then the paginator is created for all users.
     * Else a paginator for users or admins is created by calling the
     * getPaginatorBasedOnRoleAndPage() function.
     * @param RequestInterface $request
     * @return Paginator
     * @throws \Exception
     */
    private function createPaginationForRequest(RequestInterface $request): Paginator
    {
        $page = ($request->getParameter('page')) ?? 1;
        $role = $request->getParameter('role');

        if ($role !== 'admin' && $role !== 'user') {
            $totalResults = $this->userService->countRows()['rows'];
            $paginator = new Paginator($totalResults);
            $paginator->setCurrentPage($page);

            return $paginator;
        }

        return $this->getPaginatorBasedOnRoleAndPage($page, $role);
    }
    /**
     * This function calls the function countRowsLike($role) from user
     * service to see how many users with user role or admin role exist.
     * This is done to create a paginator for current role with the currentPage
     * equal to $page.
     * @param int $page
     * @param string $role
     * @return Paginator
     */
    private function getPaginatorBasedOnRoleAndPage(int $page, string $role): Paginator
    {
        $totalResults = $this->userService->countRowsLike($role)['rows'];
        $paginator = new Paginator($totalResults);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
