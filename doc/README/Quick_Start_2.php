<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;

$code = <<<'CODE'
<?php

function test($foo)
{
    var_dump($foo);
}
CODE;

$parser = (new ParserFactory())->createForNewestSupportedVersion();
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$traverser = new NodeTraverser();
$traverser->addVisitor(new class extends NodeVisitorAbstract {
    public function enterNode(Node $node) {
        if ($node instanceof Function_) {
            // Clean out the function body
            $node->stmts = [];
        }
    }
});

$dumper = new NodeDumper;
$ast = $traverser->traverse($ast);
echo $dumper->dump($ast) . "\n";
