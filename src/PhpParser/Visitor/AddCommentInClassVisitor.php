<?php

namespace My\PhpParserSandbox\PhpParser\Visitor;

use LogicException;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class AddCommentInClassVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    private $comment;

    public function __construct(string $comment)
    {
        if (str_contains($comment, PHP_EOL)) {
            throw new LogicException('Line breaks are not supported');
        }

        $this->comment = $comment;
    }

    public function enterNode(Node $node)
    {
        if (!$node instanceof Node\Stmt\Class_) {
            return $node;
        }

        $comments = $node->getComments();
        if (empty($comments)) {
            $node->setDocComment(new Doc("/** 
            * {$this->comment}
            */"));
            return $node;
        }

        $comment = (string)$comments[0];

        if(str_contains($comment, '*/')) {
            $new_comment = str_replace('*/', " * {$this->comment} 
            */", $comment);

            $node->setDocComment(new Doc($new_comment));
            return $node;
        }

        throw new LogicException('Unexpected comment pattern');
    }
}
