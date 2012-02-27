<?php

namespace Oryzone\Bundle\MediaStorageBundle\Tests\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\FilesystemMediaStorage;

class FilesystemMediaStorageTest extends \PHPUnit_Framework_TestCase
{

    protected $basePath;
    protected $baseUrl;
    protected $absoluteBaseUrl;
    protected $useAbsoluteUrls;
    protected $id;
    protected $name;
    protected $type;
    protected $variant;

    /**
     * @var FilesystemMediaStorage
     */
    protected $fs;

    protected function setUp()
    {
        $this->basePath = __DIR__ . "/fixtures/media";
        $this->baseUrl = "/project/web/media/";
        $this->absoluteBaseUrl = "http://localhost/project/web/media/";
        $this->useAbsoluteUrls = false;

        $this->file = $file = __DIR__."/fixtures/test.txt";
        $this->id = 1849;
        $this->name = "test.txt";
        $this->type = "txt";
        $this->variant = NULL;

        $this->fs = new FilesystemMediaStorage($this->basePath, $this->baseUrl, $this->absoluteBaseUrl, $this->useAbsoluteUrls);
    }

    public function testStore()
    {
        $this->fs->store($this->file, $this->id, $this->name, $this->type, $this->variant);
    }

    public function testLocate()
    {
        $url = $this->fs->locate($this->id, $this->name, $this->type, $this->variant);
        $this->assertEquals($this->baseUrl.'txt/cd/1849/test.txt', $url);
    }

    public function testLocateAbsolute()
    {
        $this->fs->setUseAbsoluteUrls(TRUE);
        $url = $this->fs->locate($this->id, $this->name, $this->type, $this->variant);
        $this->assertEquals($this->absoluteBaseUrl.'txt/cd/1849/test.txt', $url);
    }

    public function testStore2() //with weird id
    {
        $this->id = '$tr@ngE(ID)';
        $this->fs->store($this->file, $this->id, $this->name, $this->type, $this->variant);
    }

    public function testLocate2() //with weird id
    {
        $this->id = '$tr@ngE(ID)';
        $url = $this->fs->locate($this->id, $this->name, $this->type, $this->variant);
        $this->assertEquals($this->baseUrl.'txt/fa/-tr-ngE-ID-/test.txt', $url);
    }

    public function testStore3() //with variant
    {
        $this->variant = "summary";
        $this->fs->store($this->file, $this->id, $this->name, $this->type, $this->variant);
    }

    public function testLocate3()
    {
        $this->variant = "summary";
        $url = $this->fs->locate($this->id, $this->name, $this->type, $this->variant);
        $this->assertEquals($this->baseUrl.'txt/cd/1849/summary/test.txt', $url);
    }

    /**
     * @expectedException Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotLocateMediaException
     */
    public function testLocateException()
    {
        $this->fs->locate($this->id, "i don't exist!", $this->type, $this->variant);
    }

    /**
     * @expectedException Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotStoreMediaException
     */
    public function testStoreException()
    {
        $this->fs->setMediaPath('http://invalid inexistent path');
        $this->fs->store($this->file, $this->id, $this->name, $this->type);
    }

    /*
    public function testStore2()
    {
        $this->id = 2222;
        $this->variant = "xyz";
        $this->name = "copied.txt";

        $file = __DIR__."/fixtures/source.txt";
        $this->fs->store($file, $this->id, $this->name, $this->type, $this->variant);

        $this->assertEquals("/sbaam/web/images/{$this->type}/". $this->id % 256 ."/{$this->id}/{$this->variant}/{$this->name}",
            $this->fs->locate($this->id, $this->name, $this->type, $this->variant));
    }


    public function testLocate2()
    {
        $this->fs->setUseAbsoluteUrls(true);
        $url = $this->fs->locate($this->id, $this->name, $this->type, $this->variant);
        $this->assertEquals("{$this->absoluteBaseUrl}{$this->type}/". $this->id % 256 ."/{$this->id}/{$this->variant}/{$this->name}", $url);
    }

    */

    /**
     * @expectedException Oinm\ServiceBundle\Service\Image\Exception\CannotLocateImageException
     */

    /*
    public function testLocateException()
    {
        $this->fs->locate($this->id, "i don't exist!", $this->type, $this->variant);
    }

    public function testStoreException()
    {
        //TODO
    }
    */

    public static function tearDownAfterClass()
    {
        self::rrmdir(__DIR__ . "/fixtures/media");
    }

    private static function rrmdir($dir)
    {
        if (is_dir($dir))
        {
            $objects = scandir($dir);
            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if ( is_dir($dir."/".$object) )
                        self::rrmdir($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

}
