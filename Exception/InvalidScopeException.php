<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Exception;

/**
 * Class InvalidScopeException
 * @package CodingCulture\DoctrineRestQueriesBundle\Exception
 */
class InvalidScopeException extends \InvalidArgumentException
{
    const MESSAGE = 'The given scope is invalid. Available scopes are %s';

    /**
     * InvalidScopeException constructor.
     *
     * @param array           $scopes
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct(array $scopes, $code = 400, ?\Exception $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, implode(',', $scopes)), $code, $previous);
    }
}