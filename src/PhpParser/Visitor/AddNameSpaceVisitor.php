<?php

namespace My\PhpParserSandbox\PhpParser\Visitor;

use PhpParser\Builder\Namespace_;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class AddNameSpaceVisitor extends NodeVisitorAbstract
{
    /**
     * @var Namespace_
     */
    private $namespace_;

    /**
     * @deprecated
     */
    public function __construct(string $namespace_)
    {
        $this->namespace_ = new Node\Stmt\Namespace_(new Node\Name($namespace_));
    }

    public function beforeTraverse(array $nodes)
    {
        foreach ($nodes as $node) {
            if ($node instanceof Node\Stmt\Namespace_) {
                return $nodes;
            }
        }

        array_unshift($nodes, $this->namespace_);
        return $nodes;
    }
}
