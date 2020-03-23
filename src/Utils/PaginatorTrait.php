<?php

namespace QuizApp\Utils;

use Psr\Http\Message\RequestInterface;
use QuizApp\Services\AbstractService;

trait PaginatorTrait {
    /**
     * This function is called within controllers to create
     * a paginator for all pages that will need it.
     * @param RequestInterface $request
     * @param AbstractService $service
     * @return Paginator
     */
    public function createPaginationForRequestWithService(RequestInterface $request, AbstractService $service): Paginator
    {
        $page = $request->getParameter('page');

        $totalResults = $service->countRows()['rows'];
        $paginator = new Paginator($totalResults);
        if ($page) {
            $paginator->setCurrentPage($page);
        }

        return $paginator;
    }
}
