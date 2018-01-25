<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Model;

/**
 * Class Statement
 * @package CodingCulture\DoctrineRestQueriesBundle\Model
 */
class Statement
{
    /**
     * @var string
     */
    private $dql;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * Statement constructor.
     *
     * @param string $dql
     * @param array $parameters
     */
    public function __construct(string $dql, array $parameters)
    {
        $this->dql = $dql;
        $this->parameters = $parameters;
    }

    /**
     * Returns the Dql
     *
     * @return string
     */
    public function getQuery(): string
    {
        return $this->dql;
    }

    /**
     * Sets the Dql
     *
     * @param string $dql
     *
     * @return Statement
     */
    public function setQuery(string $dql): Statement
    {
        $this->dql = $dql;

        return $this;
    }

    /**
     * Returns the Parameters
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Sets the Parameters
     *
     * @param array $parameters
     *
     * @return Statement
     */
    public function setParameters(array $parameters): Statement
    {
        $this->parameters = $parameters;

        return $this;
    }
}