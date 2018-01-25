<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Contract;

use CodingCulture\DoctrineRestQueriesBundle\Model\SearchParameter;
use CodingCulture\DoctrineRestQueriesBundle\Model\Statement;

/**
 * Interface CustomStatementStrategyInterface
 * @package CodingCulture\DoctrineRestQueriesBundle\Contract
 */
interface CustomStatementStrategyInterface
{
    /**
     * Should create a Statement to be added, that differs in implementation of all other
     * Statements implemented in the StatementFactory.
     *
     * @param SearchBoundaryInterface $boundary
     * @param SearchParameter         $searchParameter
     * @param string                  $address
     * @param string                  $valueKey
     * @param string                  $operator
     *
     * @return Statement
     */
    public function create(
        SearchBoundaryInterface $boundary,
        SearchParameter $searchParameter,
        string $address,
        string $valueKey,
        string $operator
    ): Statement;
}
