<?php namespace Hyyppa\Toxx\Records;

use Jawira\CaseConverter\Convert;

class FieldFormat extends Convert
{

    public const Cases    = 'camel.pascal.snake.ada.macro.kebab.train.cobol.lower.upper.title.sentence.dot';
    public const Camel    = 'camel';
    public const Pascal   = 'pascal';
    public const Snake    = 'snake';
    public const Ada      = 'ada';
    public const Macro    = 'macro';
    public const Kebab    = 'kebab';
    public const Train    = 'train';
    public const Cobol    = 'cobol';
    public const Lower    = 'lower';
    public const Upper    = 'upper';
    public const Title    = 'title';
    public const Sentence = 'sentence';
    public const Dot      = 'dot';

    public const Carbon   = 'carbon';

}
