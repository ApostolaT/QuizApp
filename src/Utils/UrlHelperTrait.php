<?php

namespace QuizApp\Utils;

use Psr\Http\Message\RequestInterface;

trait UrlHelperTrait {

    public function createUrlHelper(RequestInterface $request, Paginator $paginator): UrlHelper
    {
        $sortParam = ($request->getParameter('sort')) ?? "";
        $filterParam = ($request->getParameter('role')) ?? "";
        $searchParam = ($request->getParameter('email')) ?? "";

        return new UrlHelper(
            $paginator,
            $this->createUrlParameters($filterParam, $sortParam, $searchParam)
        );
    }

    private function createUrlParameters(string $filterParam = "", string $sortParam = "", string $searchParam = ""): array
    {
        $urlParams = [];
        if ($filterParam !== "") {
            $urlParams['role'] = $filterParam;
        }
        if ($sortParam !== "") {
            $urlParams['sort'] = $sortParam;
        }
        if ($searchParam !== "") {
            $urlParams["email"] = $searchParam;
        }

        return $urlParams;
    }
}
