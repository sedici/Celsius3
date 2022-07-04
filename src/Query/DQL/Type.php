<?php

namespace Celsius3\Query\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

/**
 * @see https://gist.github.com/jasonhofer/8420677
 */
class Type extends FunctionNode
{
    /**
     * @var string
     */
    public $dqlAlias;

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        $qComp = $sqlWalker->getQueryComponent($this->dqlAlias);
        /** @var \Doctrine\ORM\Mapping\ClassMetadataInfo $class */
        $class = $qComp['metadata'];
        $tableAlias = $sqlWalker->getSQLTableAlias($class->getTableName(), $this->dqlAlias);

        if (!isset($class->discriminatorColumn['name'])) {
            throw QueryException::semanticalError(
                'TYPE() only supports entities with a discriminator column.'
            );
        }

        return $tableAlias . '.' . $class->discriminatorColumn['name'];
    }

    /**
     * @param Parser $parser
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->dqlAlias = $parser->IdentificationVariable();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
