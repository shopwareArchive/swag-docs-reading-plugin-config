<?php

declare(strict_types=1);

namespace Swag\ReadingPluginConfig\Subscriber;

use Shopware\Core\Framework\Struct\Struct;

class ConfigStruct extends Struct
{
    /**
     * @var string
     */
    protected $example;

    public function getExample(): string
    {
        return $this->example;
    }

    public function setExample(string $example): void
    {
        $this->example = $example;
    }
}
