<?php

namespace Oryzone\Bundle\MediaStorageBundle\Tests\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\FilesystemMediaStorage;
use Oryzone\Bundle\MediaStorageBundle\Entity\Media;

class SimpleMedia extends Media
{

}

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
        $this->fs->enableAbsoluteUrl(TRUE);
        $url = $this->fs->locate($this->id, $this->name, $this->type, $this->variant);
        $this->assertEquals($this->absoluteBaseUrl.'txt/cd/1849/test.txt', $url);
    }

    public function testLocateExternal()
    {
        $externalFile = 'https://www.google.it/logos/classicplus.png';
        $this->name = $externalFile;
        $url = $this->fs->locate($this->id, $this->name, $this->type);
        $this->assertEquals($url, $externalFile);
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

    public function testLocate3() //with variant
    {
        $this->variant = "summary";
        $url = $this->fs->locate($this->id, $this->name, $this->type, $this->variant);
        $this->assertEquals($this->baseUrl.'txt/cd/1849/summary/test.txt', $url);
    }

	public function testStoreMedia() // using storeMedia
	{
		$this->variant = "summary";
		$this->name = 'ciao-ciao';
		$media = new SimpleMedia($this->id, $this->name, $this->type);
		$this->fs->storeMedia($this->file, $media, $this->variant);
	}

	public function testLocateMedia()
	{
		$this->variant = "summary";
		$this->name = 'ciao-ciao';
		$media = new SimpleMedia($this->id, $this->name, $this->type);
		$url = $this->fs->locateMedia($media, $this->variant);
		$this->assertEquals($this->baseUrl.'txt/cd/1849/summary/ciao-ciao', $url);
	}

	public function testMoveFiles()
	{
		$newFile = $this->file.'_clone';
		if(file_exists($newFile))
			unlink($newFile); //ensure files will be always copied

		copy($this->file, $newFile);

		$this->file = $newFile;
		$this->name .= '_clone';
		$this->variant = "backup";

		$this->fs->enableMoveFile()->store($this->file, $this->id, $this->name, $this->type, $this->variant);
		$this->fs->enableMoveFile(false);
	}

	public function testMovedFileLocate()
	{
		$this->file .= '_clone';
		$this->name .= '_clone';
		$this->variant = "backup";

		$url = $this->fs->locate($this->id, $this->name, $this->type, $this->variant);

		$this->assertEquals($this->baseUrl.'txt/cd/1849/backup/test.txt_clone', $url);

		$this->assertTrue(!file_exists($this->file));
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
