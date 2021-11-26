<?php

declare(strict_types=1);

namespace App\Service;

interface ActionInterface {
    public function exec(): self;
    public function getResult(): array;
}
