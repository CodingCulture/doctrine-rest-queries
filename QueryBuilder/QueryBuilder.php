<?php

namespace CodingCulture\DoctrineRestQueriesBundle\QueryBuilder;

use CodingCulture\DoctrineRestQueriesBundle\Contract\CustomStatementStrategyInterface;
use CodingCulture\DoctrineRestQueriesBundle\Contract\SearchBoundaryInterface;
use CodingCulture\DoctrineRestQueriesBundle\Factory\StatementFactory;
use CodingCulture\DoctrineRestQueriesBundle\Model\SearchParameter;
use CodingCulture\DoctrineRestQueriesBundle\Model\Statement;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;


/**
 * Class QueryBuilder
 * @package CodingCulture\DoctrineRestQueriesBundle\QueryBuilder
 */
class QueryBuilder
{
    private static $scopeAliases = [];

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * UserQueryBuilder constructor.
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param SearchBoundaryInterface $boundary
     * @param SearchParameter[]       $searchParameters
     *
     * @return ORMQueryBuilder
     */
    public function buildQuery(SearchBoundaryInterface $boundary, array $searchParameters): ORMQueryBuilder
    {
        $queryBuilder = $this->manager->createQueryBuilder();

        $scopes = $this->getRequiredScopes($boundary, $searchParameters);
        self::$scopeAliases = array_flip($boundary->getAdditionalFields());

        $queryBuilder
            ->select($boundary->getRootAlias())
            ->from($boundary->getRootClass(), $boundary->getRootAlias())
        ;

        foreach ($scopes as $scope) {
            $scopeAlias = self::$scopeAliases[$scope];
            $queryBuilder->join($this->getAssociationName($boundary, $scope), $scopeAlias, Join::WITH);
        }

        $queryBuilder = $this->applyStatementsToQuery(
            $queryBuilder,
            $this->getDQLStatementsForSearchParameters($boundary, $searchParameters)
        );

        return $queryBuilder;
    }

    /**
     * @param SearchBoundaryInterface $boundary
     * @param SearchParameter[]       $searchParameters
     *
     * @return array
     */
    private function getRequiredScopes(SearchBoundaryInterface $boundary, array $searchParameters): array
    {
        $scopes = [];

        foreach ($searchParameters as $searchParameter) {
            if (!in_array($searchParameter->getScope(), $scopes)) {
                $scopes[] = $searchParameter->getScope();
            }
        }

        return array_diff($scopes, [$boundary->getRootClass()]);
    }

    /**
     * Applies the statements to the query
     *
     * @param ORMQueryBuilder $queryBuilder
     * @param Statement[]     $statements
     *
     * @return ORMQueryBuilder
     */
    private function applyStatementsToQuery(ORMQueryBuilder $queryBuilder, array $statements): ORMQueryBuilder
    {
        $parameters = [];

        foreach ($statements as $key => $statement) {
            if ($key === 0) {
                $queryBuilder->where($statement->getQuery());
            }

            if ($key > 0) {
                $queryBuilder->andWhere($statement->getQuery());
            }

            $parameters = array_merge($parameters, $statement->getParameters());
        }

        foreach ($parameters as $key => $value) {
            $queryBuilder->setParameter($key, $value);
        }

        return $queryBuilder;
    }

    /**
     * @param SearchBoundaryInterface $boundary
     * @param SearchParameter[]       $searchParameters
     *
     * @return Statement[]
     *
     * @throws \Exception
     */
    private function getDQLStatementsForSearchParameters(
        SearchBoundaryInterface $boundary,
        array $searchParameters
    ): array {
        $statements = [];

        foreach ($searchParameters as $searchParameter) {
            if (!in_array($searchParameter->getOperator(), SearchParameter::OPERATORS)) {
                throw new \Exception(
                    sprintf(
                        'Given operator (%s) is invalid. Allowed operators are %s',
                        $searchParameter->getOperator(),
                        implode(', ', SearchParameter::OPERATORS)
                    )
                );
            }

            $statements[] = $this->buildDQLStatement($boundary, $searchParameter);
        }

        return $statements;
    }

    /**
     * We should add some more logic to it.
     *
     * @param SearchBoundaryInterface $boundary
     * @param string                  $className
     *
     * @return string
     */
    private function getAssociationName(SearchBoundaryInterface $boundary, string $className): string
    {
        $hits = $this->manager->getClassMetadata($boundary->getRootClass())->getAssociationsByTargetClass($className);

        foreach ($hits as $hit) {
            return sprintf('u.%s', $hit['fieldName']);
        }

        return null;
    }

    /**
     * Creates an appropriate statement for the Search Parameter
     *
     * @param SearchBoundaryInterface $boundary
     * @param SearchParameter         $searchParameter
     *
     * @return Statement
     */
    private function buildDQLStatement(
        SearchBoundaryInterface $boundary,
        SearchParameter $searchParameter
    ): Statement {
        $scopeAlias = self::$scopeAliases[$searchParameter->getScope()];
        $address = sprintf('%s.%s', $scopeAlias, $searchParameter->getField());
        $valueKey = strtoupper(sprintf(':%s_%s_VALUE', $scopeAlias, $searchParameter->getField()));
        $dqlOperator = SearchParameter::OPERATOR_TO_SQL[$searchParameter->getOperator()];

        if (key_exists($searchParameter->getScope(), $boundary->getCustomLogicFactories())) {
            /** @var CustomStatementStrategyInterface $factory */
            $factory = $boundary->getCustomLogicFactories()[$searchParameter->getScope()];

            return $factory->create($boundary, $searchParameter, $scopeAlias, $valueKey, $dqlOperator);
        }

        if ($searchParameter->getOperator() === SearchParameter::OPERATOR_BETWEEN) {
            return StatementFactory::createBetweenDQLStatement($searchParameter, $address, $valueKey);
        }

        if ($searchParameter->getOperator() === SearchParameter::OPERATOR_IN) {
            return StatementFactory::createInDQLStatement($searchParameter, $address, $valueKey);
        }

        return StatementFactory::create($searchParameter, $address, $dqlOperator, $valueKey);
    }
}