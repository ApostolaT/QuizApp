<?php

namespace QuizApp\Utils;

/**
 * Class Paginator
 * @package QuizApp\Utils
 */
class Paginator
{
    /**
     * @var int
     */
    private $totalResults;
    /**
     * @var int
     */
    private $totalPages;
    /**
     * @var int
     */
    private $currentPage;
    /**
     * @var int
     */
    private $resultsPerPage;

    public function __construct(
        int $totalResults,
        int $currentPage = 1,
        int $resultsPerPage = 10
    ) {
        $this->totalResults = $totalResults;
        $this->setCurrentPage($currentPage);
        $this->setResultsPerPage($resultsPerPage);
    }
    /**
     * Sets the number of results that should be displayed on a page
     * and updates the number of available pages accordingly.
     * @param int $resultsPerPage
     */
    public function setResultsPerPage(int $resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->setTotalPages($this->totalResults, $resultsPerPage);
    }
    /**
     * Calculates the number of available pages.
     *
     * @param int $totalResults
     * @param int $resultsPerPage
     */
    private function setTotalPages(
        int $totalResults,
        int $resultsPerPage
    ) {
        $this->totalPages = ceil($totalResults / $resultsPerPage);
    }
    /**
     * Sets the current page, ensuring that only non-negative and
    non-zero values are possible.
     *
     * @param int $currentPage
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = ($currentPage <= 1 || $this->totalPages < $currentPage) ? 1 : $currentPage;
    }
    /**
     * Returns the next page number or null if there are no more pages
    available.
     *
     * @return int|null
     */
    public function getNextPage(): ?int
    {
        if ($this->currentPage < $this->totalPages) {
            return $this->currentPage + 1;
        }
        return null;
    }
    /**
     * Returns the previous page number or null if there are no more pages
    available.
     *
     * @return int|null
     */
    public function getPreviousPage(): ?int
    {
        if ($this->currentPage > 1) {
            return $this->currentPage - 1;
        }
        return null;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
}