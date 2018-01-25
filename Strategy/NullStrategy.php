<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Factory;

use CodingCulture\DoctrineRestQueriesBundle\Contract\CustomStatementStrategyInterface;
use CodingCulture\DoctrineRestQueriesBundle\Contract\SearchBoundaryInterface;
use CodingCulture\DoctrineRestQueriesBundle\Model\SearchParameter;
use CodingCulture\DoctrineRestQueriesBundle\Model\Statement;

/**
 * Class DoubleValueStrategy
 * @package CodingCulture\DoctrineRestQueriesBundle\Strategy
 */
class NullStrategy implements CustomStatementStrategyInterface
{
    public function create(
        SearchBoundaryInterface $boundary,
        SearchParameter $searchParameter,
        string $address,
        string $valueKey,
        string $operator
    ): Statement
    {
        return new Statement('',[]);
    }
}