<?php

namespace vfalies\tmdb\Items;

use PHPUnit\Framework\TestCase;

/**
 * @cover Movie
 */
class CollectionTest extends TestCase
{

    protected $tmdb          = null;
    protected $collection    = null;
    protected $collection_id = 10;

    public function setUp()
    {
        parent::setUp();

        $this->tmdb = $this->getMockBuilder(\vfalies\tmdb\Tmdb::class)
                ->setConstructorArgs(array('fake_api_key', 3, new \Monolog\Logger('Tmdb', [new \Monolog\Handler\StreamHandler('logs/unittest.log')])))
                ->setMethods(['sendRequest', 'getConfiguration'])
                ->getMock();
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->tmdb = null;
    }

    private function setRequestOk()
    {
        $json_object = json_decode(file_get_contents('tests/json/configurationOk.json'));
        $this->tmdb->method('getConfiguration')->willReturn($json_object);

        $json_object = json_decode(file_get_contents('tests/json/collectionOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);
    }

    private function setRequestCollectionEmpty()
    {
        $json_object = json_decode(file_get_contents('tests/json/configurationOk.json'));
        $this->tmdb->method('getConfiguration')->willReturn($json_object);

        $json_object = json_decode(file_get_contents('tests/json/collectionEmptyOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);
    }

    private function setRequestConfigurationEmpty()
    {
        $json_object = json_decode(file_get_contents('tests/json/configurationEmptyOk.json'));
        $this->tmdb->method('getConfiguration')->willReturn($json_object);

        $json_object = json_decode(file_get_contents('tests/json/collectionOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);
    }

    /**
     * @test
     * @expectedException \vfalies\tmdb\Exceptions\TmdbException
     */
    public function testContructFailure()
    {
        $this->tmdb->method('sendRequest')->will($this->throwException(new \vfalies\tmdb\Exceptions\TmdbException()));

        new Collection($this->tmdb, $this->collection_id);
    }

    /**
     * @test
     */
    public function testGetName()
    {
        $this->setRequestOk();

        $collection = new Collection($this->tmdb, $this->collection_id);

        $this->assertEquals('Star Wars - Saga', $collection->getName());
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function testGetNameFailure()
    {
        $this->setRequestCollectionEmpty();

        $collection = new Collection($this->tmdb, $this->collection_id);
        $collection->getName();
    }

    /**
     * @test
     */
    public function testCollectionId()
    {
        $this->setRequestOk();

        $collection = new Collection($this->tmdb, $this->collection_id);

        $this->assertInternalType('int', $collection->getId());
        $this->assertEquals($this->collection_id, $collection->getId());
    }

    /**
     * @test
     */
    public function testGetPosterPath()
    {
        $this->setRequestOk();

        $collection = new Collection($this->tmdb, $this->collection_id);

        $this->assertInternalType('string', $collection->getPosterPath());
        $this->assertNotEmpty($collection->getPosterPath());
    }

    /**
     * @test
     */
    public function testGetPosterPathFailure()
    {
        $this->setRequestCollectionEmpty();

        $collection = new Collection($this->tmdb, $this->collection_id);

        $this->assertInternalType('string', $collection->getPosterPath());
        $this->assertEmpty($collection->getPosterPath());
    }

    /**
     * @test
     */
    public function testGetParts()
    {
        $this->setRequestOk();

        $collection = new Collection($this->tmdb, $this->collection_id);
        $parts      = $collection->getParts();

        $this->assertInstanceOf(\Generator::class, $parts);
        //$this->assertInstanceOf(\vfalies\tmdb\Results\Movie::class, $parts->current());

        foreach ($parts as $part)
        {
            $this->assertInstanceOf(\vfalies\tmdb\Results\Movie::class, $part);
        }
    }

    /**
     * test
     */
    public function testGetPartsEmpty()
    {
        $this->setRequestCollectionEmpty();

        $collection = new Collection($this->tmdb, $this->collection_id);
        $parts      = $collection->getParts();

        $this->assertInstanceOf(\Generator::class, $parts);
        $this->assertNull($parts->current());
    }

    public function testGetBackdrops()
    {
        $collection = new Collection($this->tmdb, $this->collection_id);

        $json_object = json_decode(file_get_contents('tests/json/imagesOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $backdrops = $collection->getBackdrops();

        $this->assertInstanceOf(\Generator::class, $backdrops);

        foreach ($backdrops as $b)
        {
            $this->assertInstanceOf(\vfalies\tmdb\Results\Image::class, $b);
        }
    }

    public function testGetPosters()
    {
        $collection = new Collection($this->tmdb, $this->collection_id);

        $json_object = json_decode(file_get_contents('tests/json/imagesOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $posters = $collection->getPosters();

        $this->assertInstanceOf(\Generator::class, $posters);

        foreach ($posters as $p)
        {
            $this->assertInstanceOf(\vfalies\tmdb\Results\Image::class, $p);
        }
    }

}