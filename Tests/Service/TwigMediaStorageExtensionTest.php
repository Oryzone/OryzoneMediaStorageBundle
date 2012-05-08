<?php

namespace Oryzone\Bundle\MediaStorageBundle\Tests\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\TwigMediaStorageExtension;
use Oryzone\Bundle\MediaStorageBundle\Service\FilesystemMediaStorage;
use Oryzone\Bundle\MediaStorageBundle\Entity\IMedia;

class SampleTextMedia implements IMedia
{
    public function getMediaId()
    {
        return 1849;
    }

    public function getMediaName()
    {
        return "test.txt";
    }

    public function getMediaType()
    {
        return "txt";
    }

    public function isMediaExternal()
    {
        return false;
    }
}

class SampleExternalImageMedia implements IMedia
{
    public function getMediaId()
    {
        return 1849;
    }

    public function getMediaName()
    {
        return "https://www.google.it/logos/classicplus.png";
    }

    public function getMediaType()
    {
        return "image";
    }

    public function isMediaExternal()
    {
        return true;
    }
}

class TwigMediaStorageExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Twig_Environment $twig
     */
    private $twig;

    /**
     * @var FilesystemMediaStorage $mediaStorage
     */
    private $mediaStorage;

    protected function setUp()
    {
        if (!class_exists('Twig_Environment')) {
            $this->markTestSkipped('Twig is not installed.');
        }

        $this->mediaStorage = new FilesystemMediaStorage(
                                    __DIR__ . "/fixtures/stored",
                                    "/project/web/media/",
                                    "http://localhost/project/web/media/",
                                    FALSE);

        $this->twig = new \Twig_Environment();
        $this->twig->setLoader(new \Twig_Loader_Filesystem(__DIR__.'/fixtures/templates'));
        $this->twig->addExtension(new TwigMediaStorageExtension($this->mediaStorage, TRUE));
    }

    public function testGlobals()
    {
        $globals = $this->twig->getGlobals();
        $this->assertEquals($this->mediaStorage, $globals['MediaStorage_instance']);
        $this->assertTrue($globals['MediaStorage_instance_debug']);
    }

    public function testFilters()
    {
        $context = array(
            'testMedia' => new SampleTextMedia(),
            'testExternalMedia' => new SampleExternalImageMedia()
        );

        $rendered = $this->render('filter.twig.html', $context);

        $this->assertContains('/project/web/media/txt/cd/1849/summary/test.txt', $rendered);
        $this->assertContains('https://www.google.it/logos/classicplus.png', $rendered);
    }

    public function testFunctions()
    {
        $rendered = $this->render('function.twig.html');

        $this->assertContains('/project/web/media/txt/cd/1849/summary/test.txt', $rendered);
        $this->assertContains('https://www.google.it/logos/classicplus.png', $rendered);
    }

    protected function render($template, $context = array())
    {
        return $this->twig->loadTemplate($template)->render($context);
    }

}