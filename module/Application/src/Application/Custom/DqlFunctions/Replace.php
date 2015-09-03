<?php

/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 21/05/2015
 * Time: 15:49.
 */

namespace Application\Custom\DqlFunctions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * Class Replace
 * @package Application\Custom\DqlFunctions
 */
class Replace extends FunctionNode
{
    /**
     * @var mixed
     */
    public $stringFirst;
    /**
     * @var mixed
     */
    public $stringSecond;
    /**
     * @var mixed
     */
    public $stringThird;

    /**
     * Retorna sintaxe do comando replace para o Doctrine
     *
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'replace(' . $this->stringFirst->dispatch($sqlWalker) . ','
        . $this->stringSecond->dispatch($sqlWalker) . ','
        . $this->stringThird->dispatch($sqlWalker) . ')';
    }

    /**
     * Implementa o parse para validação da sintaxe do comando.
     *
     * @param \Doctrine\ORM\Query\Parser $parser
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->stringFirst = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->stringSecond = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->stringThird = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
