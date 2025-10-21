<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

pest()
    ->printer()
    ->compact();

pest()
    ->extend(TestCase::class)
    ->in('Unit');
