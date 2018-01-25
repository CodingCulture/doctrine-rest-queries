<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Factory;

use CodingCulture\DoctrineRestQueriesBundle\Contract\SearchBoundaryInterface;
use CodingCulture\DoctrineRestQueriesBundle\Exception\InvalidScopeException;
use CodingCulture\DoctrineRestQueriesBundle\Model\SearchParameter;

/**
 * Class SearchParameterFactory
 * @package CodingCulture\DoctrineRestQueriesBundle\Factory
 */
class SearchParameterFactory
{
    /**
     * @param SearchBoundaryInterface $boundary
     * @param string                  $rawField
     * @param string                  $operator
     * @param $value
     *
     * @return SearchParameter
     * @throws \Exception
     */
    public static function create(
        SearchBoundaryInterface $boundary,
        string $rawField,
        string $operator,
        $value
    ): SearchParameter {
        $searchParameter = new SearchParameter();
        $entities = $boundary->getAvailableEntities();

        list($alias, $field) = explode('.', $rawField);

        if (!key_exists($alias, $entities)) {
            throw new InvalidScopeException($entities);
        }

        $searchParameter
            ->setScope($entities[$alias])
            ->setField($field)
            ->setOperator($operator)
            ->setValue($value)
        ;

        return $searchParameter;
    }
}