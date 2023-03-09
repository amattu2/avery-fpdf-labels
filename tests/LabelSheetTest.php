<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require 'vendor/autoload.php';
require 'src/LabelSheet.php';

final class LabelSheetTest extends TestCase
{
    public function testAvery5160(): void
    {
        $c = new Avery\LabelSheet('Avery5160');
    }
}
