<?php

namespace QuizApp\Contracts;

interface RowsCountInterface
{
    public function countRows(string $filterParameter = "", string $searchParameter = "");
}
