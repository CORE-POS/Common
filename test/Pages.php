<?php

class Pages extends PHPUnit_Framework_TestCase
{   
    public function testPages()
    {
        $page = new COREPOS\common\ui\CorePage();
        $this->assertEquals($page->bodyContent(), $page->body_content());
        ob_start();
        $page->drawPage();
        $this->assertNotEquals('', ob_get_clean());
        ob_start();
        $page->draw_page();
        $this->assertNotEquals('', ob_get_clean());
        $this->assertEquals(null, $page->errorContent());

        $router = new COREPOS\common\ui\CoreRESTfulRouter();
        $router->unitTest($this);
        $router->addRoute('get<id><id2>');
        ob_start();
        $this->assertEquals(false, $router->handler($page));
        ob_get_clean();

        $page->baseTest($this);

        $mc = new MockConfig();
        $page->setConfig($mc);
        $bl = new COREPOS\common\BaseLogger();
        $page->setLogger($bl);
        $page->setConnection(new MockSQL());

        $page->unitTest($this);
    }


}

