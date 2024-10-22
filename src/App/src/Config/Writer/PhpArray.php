<?php

declare(strict_types=1);

namespace App\Config\Writer;

use Laminas\Config\Writer\PhpArray as BaseWriter;

use function sprintf;

class PhpArray extends BaseWriter
{
    private string $strictString = 'declare(strict_types=1);';

    private bool $declareStrictTypes = true;

    public const TEMPLATE = <<<'EOT'
        <?php

        %s

        return %s
        %s
        %s;
        EOT;

    public function processConfig(array $config)
    {
        $this->setUseClassNameScalars(true);

        $arraySyntax = [
            'open'  => $this->useBracketArraySyntax ? '[' : 'array(',
            'close' => $this->useBracketArraySyntax ? ']' : ')',
        ];

        return sprintf(
            self::TEMPLATE,
            $this->declareStrictTypes ? $this->strictString : '',
            $arraySyntax['open'],
            $this->processIndented($config, $arraySyntax),
            $arraySyntax['close']
        );
    }

    public function setDeclareStrictTypes(bool $flag = true): void
    {
        if (! $flag) {
            $this->declareStrictTypes = $flag;
        }
    }

    public function getDeclareStrictTypes(): bool
    {
        return $this->declareStrictTypes;
    }
}
