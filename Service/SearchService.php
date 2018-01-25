<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Service;

use CodingCulture\DoctrineRestQueriesBundle\Contract\SearchBoundaryInterface;
use CodingCulture\DoctrineRestQueriesBundle\QueryBuilder\QueryBuilder;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use CodingCulture\DoctrineRestQueriesBundle\Model\SearchParameter;
use CodingCulture\DoctrineRestQueriesBundle\QueryBuilder\UserQueryBuilder;

/**
 * Class SearchService
 * @package CodingCulture\DoctrineRestQueriesBundle\Service
 */
class SearchService
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * QueryBuilderService constructor.
     *
     * @param EntityManager $manager
     * @param QueryBuilder  $queryBuilder
     */
    public function __construct(EntityManager $manager, QueryBuilder $queryBuilder)
    {
        $this->manager = $manager;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Returns all parameters that can be used to search against for a search boundary
     *
     * @param SearchBoundaryInterface $boundary
     *
     * @return array|\string[]
     */
    public function getAvailableSearchParameters(SearchBoundaryInterface $boundary): array
    {
        $searchParameters = array_merge(
            $this->getDoctrineFields($boundary),
            $boundary->getAdditionalFields()
        );

        sort($searchParameters);

        return $searchParameters;
    }

    /**
     * Returns all supported operators
     *
     * @return array
     */
    public function getAvailableSearchOperators(): array
    {
        return SearchParameter::OPERATORS;
    }

    /**
     * Counts how many records exist for your query
     *
     * @param SearchBoundaryInterface $boundary
     * @param array                   $searchParameters
     *
     * @return int
     */
    public function getCountForParameters(SearchBoundaryInterface $boundary, array $searchParameters): int
    {
        $this->manager->getMetadataFactory()->getAllMetadata();

        $query = $this->queryBuilder->buildQuery($boundary, $searchParameters);

        $scopeAliases = array_flip($boundary->getAvailableEntities());

        $query->select(sprintf('count(distinct %s.id)', $scopeAliases[$boundary->getRootClass()]));

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Creates a doctrine query from
     *
     * @param SearchBoundaryInterface $boundary
     * @param SearchParameter[]       $searchParameters
     * @param int                     $page
     * @param int                     $pageSize
     *
     * @return array
     */
    public function getResultsForParameters(
        SearchBoundaryInterface $boundary,
        array $searchParameters,
        int $page,
        int $pageSize
    ): array {
        $query = $this->queryBuilder->buildQuery($boundary, $searchParameters);

        $query
            ->setMaxResults($pageSize)
            ->setFirstResult($page * $pageSize)
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * Gets all fields from the entities
     *
     * @param SearchBoundaryInterface $boundary
     *
     * @return array
     */
    private function getDoctrineFields(SearchBoundaryInterface $boundary): array
    {
        $fieldNames = [];

        $diff = array_diff($boundary->getAvailableEntities(), array_keys($boundary->getCustomStatementStrategies()));

        foreach ($diff as $key => $class) {
            $classFieldNames = array_diff(
                $this->manager->getClassMetadata($class)->getFieldNames(),
                $boundary->getIgnoredFields()
            );

            array_walk($classFieldNames, function (&$classFieldName) use ($key) {
                $classFieldName = sprintf('%s.%s', $key, $classFieldName);
            });

            $fieldNames = array_merge($fieldNames, $classFieldNames);
        }

        return $fieldNames;
    }
}