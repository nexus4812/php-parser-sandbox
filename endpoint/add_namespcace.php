<?php

require_once __DIR__ . '/../vendor/autoload.php';

use My\PhpParserSandbox\PhpParser\Parser\NodeTreeParser;
use My\PhpParserSandbox\PhpParser\Visitor\AddCommentInClassVisitor;
use My\PhpParserSandbox\PhpParser\Visitor\AddNameSpaceVisitor;
use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter\Standard;

$traverser = new NodeTraverser;
$traverser->addVisitor(new AddCommentInClassVisitor('@deprecated '));
$traverser->addVisitor(new AddNameSpaceVisitor('A'));

$stmts = (new NodeTreeParser())->getNodeTreeByPath(
    new SplFileInfo('/Users/my/Documents/php-parser-sandbox/sample/UserDao.php')
);

echo (new Standard())->prettyPrintFile($traverser->traverse($stmts));
