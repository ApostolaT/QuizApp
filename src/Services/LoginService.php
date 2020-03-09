<?php


namespace QuizApp\Services;


use Framework\Session\Session;
use Psr\Http\Message\RequestInterface;
use QuizApp\Repositories\UserRepository;
use ReallyOrm\Repository\RepositoryManagerInterface;

class LoginService extends AbstractService
{
    private $repositoryManager;

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function userLogIn(RequestInterface $request, $session)
    {
        $email =  $request->getParameter("email");
        $password =  $request->getParameter("password");

        $repository = $this->repositoryManager->getRepository(UserRepository::class);

        $entity = $repository->findOneBy(['name' => $email]);
        $dbParam = $entity->getPassword();
        $role = $entity->getRole();

        if (password_verify($password, $dbParam)) {
            $session->set('name', $email);
            $session->set('role', $role);

            return $session;
        }
    }
}