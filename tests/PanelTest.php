<?php

namespace Yiisoft\Yii\Debug\Viewer\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\View\View;
use Yiisoft\Yii\Debug\Module;
use Yiisoft\Yii\Debug\Viewer\Panel;

class PanelTest extends TestCase
{
    public function testGetTraceLine_DefaultLink()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10
        ];
        $panel = $this->getPanel();
        $this->assertEquals('<a href="ide://open?url=file://file.php&line=10">file.php:10</a>', $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_DefaultLink_CustomText()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10,
            'text' => 'custom text'
        ];
        $panel = $this->getPanel();
        $this->assertEquals('<a href="ide://open?url=file://file.php&line=10">custom text</a>', $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_TextOnly()
    {
        $panel = $this->getPanel();
        $panel->module->traceLine = false;
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10
        ];
        $this->assertEquals('file.php:10', $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_CustomLinkByString()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10
        ];
        $panel = $this->getPanel();
        $panel->module->traceLine = '<a href="phpstorm://open?url=file://file.php&line=10">my custom phpstorm protocol</a>';
        $this->assertEquals('<a href="phpstorm://open?url=file://file.php&line=10">my custom phpstorm protocol</a>', $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_CustomLinkByCallback()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10,
        ];
        $panel = $this->getPanel();
        $expected = 'http://my.custom.link';
        $panel->module->traceLine = function () use ($expected) {
            return $expected;
        };
        $this->assertEquals($expected, $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_CustomLinkByCallback_CustomText()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10,
            'text' => 'custom text'
        ];
        $panel = $this->getPanel();
        $panel->module->traceLine = function () {
            return '<a href="ide://open?url={file}&line={line}">{text}</a>';
        };
        $this->assertEquals('<a href="ide://open?url=file.php&line=10">custom text</a>', $panel->getTraceLine($traceConfig));
    }

    private function getPanel()
    {
        $view = $this->getMockBuilder(View::class)
            ->disableOriginalConstructor()
            ->getMock();
        $panel = new Panel($view);
        $panel->module = new Module();

        return $panel;
    }
}