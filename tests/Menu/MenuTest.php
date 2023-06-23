<?php

namespace App\Tests\Menu;

use App\Menu\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    public function testMainMenu(): void
    {
        $menu = new Menu();
        $result = $menu->getTopMenu(3);
        $this->assertCount(3, $result);
    }
}
