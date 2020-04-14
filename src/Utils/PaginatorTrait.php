<?php

namespace QuizApp\Utils;

use Psr\Http\Message\RequestInterface;
use QuizApp\Contracts\RowsCountInterface;
use QuizApp\Services\AbstractService;

/**
 * Implements methods for creating paginators.
 * Trait PaginatorTrait
 * @package QuizApp\Utils
 */
trait PaginatorTrait {

    /**
     * This function is called within controllers to create
     * a paginator for all pages that will need it.
     * @param RequestInterface $request
     * @param AbstractService $service
     * @return Paginator
     */
    public function createFromRequest(RequestInterface $request, AbstractService $service): Paginator
    {
        $page = $request->getParameter('page');
        $totalResults = $service->countRows()["rows"];

        $paginator = new Paginator($totalResults);
        if ($page) {
            $paginator->setCurrentPage($page);
        }

        return $paginator;
    }

    /**
     * Create paginator for request based on filters and search parameters.
     * @param RequestInterface $request
     * @param RowsCountInterface $service
     * @param string $filterParameter
     * @param string $searchParameter
     * @return Paginator
     */
    public function createCustomPaginator(
        RequestInterface $request,
        RowsCountInterface $service,
        string $filterParameter = "",
        string $searchParameter = ""
    ) {
        $page = (int)$request->getParameter('page');
        $totalResults = $service->countRows($filterParameter, $searchParameter);

        $paginator = new Paginator($totalResults);
        if ($page) {
            $paginator->setCurrentPage($page);
        }

        return $paginator;
    }
}
