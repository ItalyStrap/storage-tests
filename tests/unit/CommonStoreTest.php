<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Config\Config;
use ItalyStrap\Storage\StoreInterface;
use ItalyStrap\StorageTests\CommonStoreMultipleTestsTrait;

class CommonStoreTest extends UnitTestCase
{
    use CommonStoreMultipleTestsTrait;

    public function makeInstance()
    {
        return new Config();
    }

    protected function prepareSetMultipleReturnFalse(): void
    {
    }
}
