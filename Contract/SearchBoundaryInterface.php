<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Contract;

/**
 * Interface BoundaryInterface
 * @package CodingCulture\DoctrineRestQueriesBundle\Contract
 */
interface SearchBoundaryInterface
{
    /**
     * Returns all entities available in the boundary. These mostly consist of the root entity + support entities.
     * E.g. User and [UserEmails, UserEvents].
     *
     * These entities should have a unique key as alias e.g. u, and the doctrine className:
     * ['u' => User::class]
     *
     * @return array
     */
    public function getAvailableEntities(): array;

    /**
     * Fields returned here (crude, without alias) get ignored in all entities supplied in available entities.
     *
     * @return array
     */
    public function getIgnoredFields(): array;

    /**
     * Adds fields to entities (with alias e.g. u.created_at), that were ignored in the getIgnoredFields.
     *
     * @return array
     */
    public function getAdditionalFields(): array;

    /**
     * Returns a list of all entities (className as key) that need a custom statement to work:
     * [UserPreferences::class => new PreferencesStrategy()]
     *
     * These factories should implement CustomStatementStrategyInterface
     *
     * @return CustomStatementStrategyInterface[]
     */
    public function getCustomStatementStrategies(): array;

    /**
     * Returns the root className.
     *
     * e.g. User::class
     *
     * @return string
     */
    public function getRootClass(): string;

    /**
     * Returns the root alias. This should be consistent with a record in the $this::getAvailableEntities. If root
     * entity is User, and it is listed in $this::getAvailableEntities as:
     * [
     *      'u' => User:class,
     * ]
     *
     * This method should return 'u'
     *
     * @return string
     */
    public function getRootAlias(): string;
}
