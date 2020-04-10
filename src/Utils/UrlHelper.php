<?php


namespace QuizApp\Utils;

/**
 * Class UrlHelper
 * is meant to build the url of the links based on the values it gets
 * @package QuizApp\Utils
 */
class UrlHelper
{
    /**
     * @var [] $urlParameters Type ["parameterName" => "parameterValue", ....]
     */
    private $urlParameters = [];
    /**
     * @var Paginator $paginator
     */
    private $paginator;
    /**
     * Sets the urlParameters with the newParameters,
     * the initial parameters from urlParameters are deleted.
     * @param array $newParameters
     */
    public function setParameters(array $newParameters)
    {
        if ($newParameters !== []) {
            $this->urlParameters = $newParameters;
        }
    }
    /**
     * Sets the paginator
     * @param Paginator $paginator
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }
    /**
     * Check if a key exists in an array
     * @param string $key
     * @return bool
     */
    public function keyExists(string $key): bool
    {
        return array_key_exists($key, $this->urlParameters);
    }
    /**
     * Get the value of the specified parameter
     * @param string $key
     * @return mixed|null
     */
    public function getValue(string $key)
    {
        if (!$this->keyExists($key)) {
            return "";
        }

        return $this->urlParameters[$key];
    }
    /**
     * Returns the previous page's url
     * @return string
     */
    public function getPreviousPageUrl(): string
    {
        if ($this->paginator->getPreviousPage() === null) {
            return "";
        }

        return "?page=".$this->paginator->getPreviousPage().http_build_query($this->urlParameters);
    }
    /**
     * Returns the next page's url
     * @return string
     */
    public function getNextPageUrl(): string
    {
        if ($this->paginator->getNextPage() === null) {
            return "";
        }

        return "?page=".$this->paginator->getNextPage().http_build_query($this->urlParameters);
    }
    /**
     * Returns the
     * @return string
     */
    public function getCurrentPageUrl(): string
    {
        $pageParameter = "";
        if ($this->paginator->getCurrentPage() > 0) {
            $pageParameter = "?page=".$this->paginator->getCurrentPage();
        }

        return $pageParameter.http_build_query($this->urlParameters);
    }
    public function getUrlForPage(int $page): string
    {
        return "?page=$page&".http_build_query($this->urlParameters);
    }
    /**
     * @return string that is the complete url containing all the urlParameters.
     * This url will have the sort state set to the next one, or nothing if the state is dsc:
     * State toggle: none=>asc=>dsc=>none
     */
    public function getNextSortStateUrl(): string
    {
        $parametersCopy = $this->urlParameters;
        unset($parametersCopy["sort"]);

        if ($this->paginator->getCurrentPage() > 1) {
            $parametersCopy = ["page" => $this->paginator->getCurrentPage()] + $parametersCopy;
        }
        if (!$this->keyExists("sort")) {
            $parametersCopy["sort"] = "asc";
        }
        if ($this->keyExists("sort")) {
            if ($this->getValue("sort") === "asc") {
                $parametersCopy["sort"] = "dsc";
            }
        }

        return "?".http_build_query($parametersCopy);
    }

    /**
     * Return the url with pre-pended filter
     * @param string $filterName
     * @param string $filterValue
     * @return string
     */
    public function getUrlForFilter(string $filterName, string $filterValue): string
    {
        $parametersCopy = $this->urlParameters;
        $parametersCopy =  [$filterName => $filterValue] + $parametersCopy;

        return "?".http_build_query($parametersCopy);
    }
}