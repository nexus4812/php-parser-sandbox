<?php

// https://github.com/nikic/PHP-Parser/blob/master/doc/component/Walking_the_AST.markdown#node-visitors

require_once __DIR__ . '/../../vendor/autoload.php';

$output = function (Closure $closure) {
    ob_start();
    $closure();
    $string = ob_get_clean();
    file_put_contents(__FILE__ . '.result', $string);
    echo $string;
};

use PhpParser\{Node, NodeDumper, NodeTraverser, NodeVisitorAbstract, ParserFactory};

$code = <<<'CODE'
<?php

class A {

    function test($foo)
    {
        $int = 123;
    }

    function test2($foo)
    {
        $int = 123;
    }
}

CODE;

$traverser = new NodeTraverser;
$traverser->addVisitor(new class extends NodeVisitorAbstract {

    // step1
    public function beforeTraverse(array $nodes)
    {
        foreach ($nodes as $node) {
            echo __FUNCTION__ . ' argument is  ' . get_class($node) . PHP_EOL;
        }
    }

    // step2
    public function enterNode(Node $node)
    {
        echo __FUNCTION__ . ' argument is ' . get_class($node) . PHP_EOL;
    }

    // step3
    public function leaveNode(Node $node)
    {
        echo __FUNCTION__ . ' argument is ' . get_class($node) . PHP_EOL;
    }

    public function afterTraverse(array $nodes)
    {
        foreach ($nodes as $node) {
            echo __FUNCTION__ . ' argument is ' . get_class($node) . PHP_EOL;
        }
    }
});

$parser = (new ParserFactory())->createForNewestSupportedVersion();
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$output(function () use ($ast, $traverser){
    $dumper = new NodeDumper;
    echo $dumper->dump($ast) . "\n";
    $modifiedStmts = $traverser->traverse($ast);
});

