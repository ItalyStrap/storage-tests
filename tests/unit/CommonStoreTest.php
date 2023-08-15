<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Config\Config;
use ItalyStrap\Storage\StoreInterface;

class CommonStoreTest extends UnitTestCase
{
    use \ItalyStrap\StorageTests\CommonStoreMultipleTestsTrait;

    public function makeInstance()
    {
        return new Config();
    }

    protected function prepareSetMultipleReturnFalse(): void
    {
    }
}
