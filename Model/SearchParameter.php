<?php

namespace CodingCulture\DoctrineRestQueriesBundle\Model;

/**
 * Class SearchParameter
 * @package CodingCulture\DoctrineRestQueriesBundle\Model
 */
class SearchParameter
{
    const OPERATOR_EXACT = 'exact';
    const OPERATOR_LIKE = 'like';
    const OPERATOR_GREATER_THAN = '>';
    const OPERATOR_LESSER_THAN = '<';
    const OPERATOR_BETWEEN = 'between';
    const OPERATOR_IN = 'in';

    const OPERATORS = [
        self::OPERATOR_EXACT,
        self::OPERATOR_LIKE,
        self::OPERATOR_GREATER_THAN,
        self::OPERATOR_LESSER_THAN,
        self::OPERATOR_BETWEEN,
        self::OPERATOR_IN,
    ];

    const OPERATOR_TO_SQL = [
        self::OPERATOR_EXACT => '=',
        self::OPERATOR_LIKE => 'LIKE',
        self::OPERATOR_GREATER_THAN => self::OPERATOR_GREATER_THAN,
        self::OPERATOR_LESSER_THAN => self::OPERATOR_LESSER_THAN,
        self::OPERATOR_BETWEEN => 'BETWEEN',
        self::OPERATOR_IN => 'IN',
    ];

    /**
     * Classname where the attribute should be found. This can the root, instead of the actual class. E.g. if you want
     * to look for the email of an user, it is a valid pass to use User::class.
     *
     * @var string
     */
    private $scope;

    /**
     * Attribute to be queried upon.
     *
     * @var string
     */
    private $field;

    /**
     * The operator that shall be used to build the query.
     *
     * @var
     */
    private $operator;

    /**
     * The value that will be matched again
     *
     * @var string|array
     */
    private $value;

    /**
     * Returns the Scope
     *
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * Sets the Scope
     *
     * @param string $scope
     *
     * @return SearchParameter
     */
    public function setScope(?string $scope): SearchParameter
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Returns the Field
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * Sets the Field
     *
     * @param string $field
     *
     * @return SearchParameter
     */
    public function setField(string $field): SearchParameter
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Returns the Operator
     *
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Sets the Operator
     *
     * @param mixed $operator
     *
     * @return SearchParameter
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Returns the Value
     *
     * @return array|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the Value
     *
     * @param array|string $value
     *
     * @return SearchParameter
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}