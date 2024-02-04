<?php

// https://github.com/nikic/PHP-Parser/blob/master/doc/component/Walking_the_AST.markdown#modifying-the-ast

require_once __DIR__ . '/../../vendor/autoload.php';
use PhpParser\{Node, NodeTraverser, NodeVisitorAbstract, ParserFactory, PrettyPrinter};

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
}
CODE;

$traverser = new NodeTraverser;
$traverser->addVisitor(new class extends NodeVisitorAbstract {
    public function leaveNode(Node $node) {
        if ($node instanceof Node\Scalar\LNumber) {
            // Convert all $a && $b expressions into !($a && $b)
            return new Node\Scalar\LNumber(1);
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

