<?php

// https://github.com/nikic/PHP-Parser/blob/master/doc/component/Walking_the_AST.markdown#modifying-the-ast

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpParser\{Node, NodeTraverser, NodeVisitor, NodeVisitorAbstract, ParserFactory, PrettyPrinter};

$output = function (Closure $closure) {
    ob_start();
    $closure();
    $string = ob_get_clean();
    file_put_contents(__FILE__ . '.result', $string);
    echo $string;
};

$code = <<<'CODE'
<?php

function test($foo)
{
    $int = 123;
    
    return $int;
}
CODE;

$traverser = new NodeTraverser;
$traverser->addVisitor(new class extends NodeVisitorAbstract {
    public function leaveNode(Node $node) {
        if ($node instanceof Node\Stmt\Return_) {
            // Remove all return statements
            return NodeVisitor::REMOVE_NODE;
        }

        return null;
    }
});

$parser = (new ParserFactory())->createForNewestSupportedVersion();
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$modifiedStmts = $traverser->traverse($ast);

$prettyPrinter = new PrettyPrinter\Standard;

$output(function () use ($prettyPrinter, $ast) {
    echo $prettyPrinter->prettyPrintFile($ast);
});

