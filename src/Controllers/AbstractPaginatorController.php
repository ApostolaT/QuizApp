<?php


namespace QuizApp\Controllers;


use Framework\Controller\AbstractController;
use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;
use QuizApp\Utils\Paginator;

class AbstractPaginatorController extends AbstractController
{
    /**
     * @var AbstractService
     */
    protected $service;
    /**
     * This function sets the service
     * @param AbstractService $service
     */
    public function setService(AbstractService $service)
    {
        $this->service = $service;
    }
    /**
     * This function is called to create a paginator for
     * all users page.
     * @param RequestInterface $request
     * @return Paginator
     */
    protected function createPaginationForRequest(RequestInterface $request): Paginator
    {
        $page = $request->getParameter('page');

        $totalResults = $this->service->countRows()['rows'];
        $paginator = new Paginator($totalResults);
        if ($page) {
            $paginator->setCurrentPage($page);
        }

        return $paginator;
    }
}
