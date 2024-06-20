<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\View\Components\AppLayout;
use Illuminate\View\View;

class AppLayoutTest extends TestCase
{
    public function testRender()
    {
        $component = new AppLayout();
        $view = $component->render();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('layouts.app', $view->name());
    }
}