<?php


namespace QuizApp\Services;

use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\User;
use QuizApp\Exceptions\UserLoginException;
use ReallyOrm\Repository\RepositoryManagerInterface;

class LoginService extends AbstractService
{
    private $repositoryManager;

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @throws UserLoginException
     */
    public function userLogIn(RequestInterface $request, $session)
    {
        $email = $request->getParameter("email");
        $password = $request->getParameter("password");

        $repository = $this->repositoryManager->getRepository(User::class);

        $entity = $repository->findOneBy(['name' => $email]);
        $dbPassword = $entity->getPassword();
        $role = $entity->getRole();

        if ($dbPassword === "" && $password !== "")
        {
            $dbPassword = password_hash($password, PASSWORD_BCRYPT);;
            $entity->setPassword($dbPassword);
            $repository->insertOnDuplicateKeyUpdate($entity);
        }

        if (password_verify($password, $dbPassword)) {
            $session->set('id', $entity->getId());
            $session->set('name', $email);
            $session->set('role', $role);

            return $session;
        }

        throw new UserLoginException();
    }
}