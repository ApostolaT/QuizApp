<?php


namespace QuizApp\Controllers;

use Framework\Controller;
use Psr\Http\Message\RequestInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;
use QuizApp\Repositories\UserRepository;

class UserController extends Controller\AbstractController
{
    private $repositoryManager;

    public function setRepositorymanager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function getLoginPage(RequestInterface $request)
    {
        $response = $this->renderer->renderView("login.html", $request->getRequestParameters());
        return $response;
    }

    public function loginAction(RequestInterface $request)
    {
        $email =  $request->getParameter("email");
        $password =  $request->getParameter("password");

        echo $email.'<br>';
        echo $password;

        $repository = $this->repositoryManager->getRepository(UserRepository::class);

        $entity = $repository->findOneBy(['name' => $email]);
        $dbParam = $entity->getPassword();


        if (password_verify($password, $dbParam))
            echo "salut";
        return null;
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
