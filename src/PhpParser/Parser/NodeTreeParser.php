<?php

namespace My\PhpParserSandbox\PhpParser\Parser;

use PhpParser\Node\Stmt;
use PhpParser\ParserFactory;
use RuntimeException;
use SplFileInfo;

class NodeTreeParser
{
    /**
     * @param SplFileInfo $file
     * @return Stmt[]
     */
    public function getNodeTreeByPath(SplFileInfo $file): array
    {
        $contents = file_get_contents($file->getRealPath());
        $parser = (new ParserFactory())->createForNewestSupportedVersion();

        $stmts = $parser->parse($contents);

        if (!is_array($stmts)) {
            throw new RuntimeException("Parse function returned null");
        }

        return $parser->parse($contents);
    }
}
