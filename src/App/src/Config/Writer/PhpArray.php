<?php

declare(strict_types=1);

namespace App\Config\Writer;

use Laminas\Config\Writer\PhpArray as BaseWriter;

use function sprintf;

class PhpArray extends BaseWriter
{
    public const TEMPLATE = <<<'EOT'
        <?php

        declare(strict_types=1);

        return %s
            %s
        %s;

        EOT;

    public function processConfig(array $config)
    {
        $arraySyntax = [
            'open'  => $this->useBracketArraySyntax ? '[' : 'array(',
            'close' => $this->useBracketArraySyntax ? ']' : ')',
        ];

        return sprintf(
            self::TEMPLATE,
            $arraySyntax['open'],
            $this->processIndented($config, $arraySyntax),
            $arraySyntax['close']
        );
    }
}
