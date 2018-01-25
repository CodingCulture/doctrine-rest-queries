<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Factory;

use CodingCulture\DoctrineRestQueriesBundle\Model\SearchParameter;
use CodingCulture\DoctrineRestQueriesBundle\Model\Statement;

/**
 * Class StatementFactory
 * @package CodingCulture\DoctrineRestQueriesBundle\Factory
 */
class StatementFactory
{
    /**
     * @param SearchParameter $searchParameter
     * @param string $address
     * @param string $operator
     * @param string $valueKey
     *
     * @return Statement
     */
    public static function create(
        SearchParameter $searchParameter,
        string $address,
        string $operator,
        string $valueKey
    ): Statement {
        return new Statement(
            sprintf('%s %s %s', $address, $operator, $valueKey),
            [$valueKey => $searchParameter->getValue()]
        );
    }

    /**
     * @param SearchParameter $searchParameter
     * @param string $address
     * @param string $valueKey
     *
     * @return Statement
     */
    public static function createBetweenDQLStatement(
        SearchParameter $searchParameter,
        string $address,
        string $valueKey
    ): Statement {
        return new Statement(
            sprintf('%s BETWEEN %s_A AND %s_B', $address, $valueKey, $valueKey),
            [
                $valueKey . '_A' => $searchParameter->getValue()[0],
                $valueKey . '_B' => $searchParameter->getValue()[1]
            ]
        );
    }

    /**
     * Creates a DQL statement for entities with specific query'ing logic
     *
     * @param SearchParameter $searchParameter
     * @param string $scopeAlias
     * @param string $valueKey
     * @param string $operator
     *
     * @return Statement
     */
    public static function createDoubleValueDQLStatement(
        SearchParameter $searchParameter,
        string $scopeAlias,
        string $valueKey,
        string $operator
    ): Statement {
        return new Statement(
            sprintf(
                '%s.type = %s_NAME AND %s.value %s %s_VALUE',
                $scopeAlias,
                $valueKey,
                $scopeAlias,
                $operator,
                $valueKey
            ),
            [
                $valueKey . '_NAME' => $searchParameter->getField(),
                $valueKey . '_VALUE' => $searchParameter->getValue(),
            ]
        );
    }

    /**
     * @param SearchParameter $searchParameter
     * @param string $address
     * @param string $valueKey
     *
     * @return Statement
     */
    public static function createInDQLStatement(
        SearchParameter $searchParameter,
        string $address,
        string $valueKey
    ): Statement {
        return new Statement(
            sprintf('%s IN (%s)', $address, $valueKey),
            [$valueKey => $searchParameter->getValue()]
        );
    }
}