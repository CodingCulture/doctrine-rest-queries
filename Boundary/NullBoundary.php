<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Boundary;

use CodingCulture\DoctrineRestQueriesBundle\Contract\CustomStatementStrategyInterface;
use CodingCulture\DoctrineRestQueriesBundle\Contract\SearchBoundaryInterface;

/**
 * Class NullBoundary
 * @package CodingCulture\DoctrineRestQueriesBundle\Boundary
 *
 * Class mainly for test and documentation purposes.
 */
class NullBoundary implements SearchBoundaryInterface
{
    /**
     * @{inheritDoc}
     */
    public function getAvailableEntities(): array
    {
        return [];
    }

    /**
     * @{inheritDoc}
     */
    public function getIgnoredFields(): array
    {
        return [];
    }

    /**
     * @{inheritDoc}
     */
    public function getAdditionalFields(): array
    {
        return [];
    }

    /**
     * @{inheritDoc}
     */
    public function getRootAlias(): string
    {
        return '';
    }

    /**
     * @{inheritDoc}
     */
    public function getRootClass(): string
    {
        return '';
    }

    /**
     * Returns a list of all entities (className as key) that need a custom statement to work:
     * [UserPreferences::class => new PreferencesStrategy()]
     *
     * These factories should implement CustomStatementStrategyInterface
     *
     * @return CustomStatementStrategyInterface[]
     */
    public function getCustomStatementStrategies(): array
    {
        return [];
    }
}