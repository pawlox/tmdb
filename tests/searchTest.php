<?php

namespace vfalies\tmdb;

use PHPUnit\Framework\TestCase;

/**
 * @cover Search
 */
class SearchTest extends TestCase
{

    protected $tmdb = null;

    public function setUp()
    {
        parent::setUp();

        $this->tmdb = $this->getMockBuilder(Tmdb::class)
                ->setConstructorArgs(array('fake_api_key', new \Monolog\Logger('Tmdb', [new \Monolog\Handler\StreamHandler('logs/unittest.log')])))
                ->setMethods(['sendRequest'])
                ->getMock();
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->tmdb = null;
    }

    /**
     * @test
     */
    public function testSearchMovieValid()
    {
        $json_object = json_decode(file_get_contents('tests/json/searchMovieOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $search    = new Search($this->tmdb);
        $responses = $search->searchMovie('star wars', array('language' => 'fr-FR'));

        $this->assertInstanceOf(\Generator::class, $responses);
        $this->assertInstanceOf(Results\Movie::class, $responses->current());

        return $search;
    }

    /**
     * @test
     */
    public function testSearchMovieEmptyValid()
    {
        $json_object = json_decode(file_get_contents('tests/json/searchMovieEmptyOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $search    = new Search($this->tmdb);
        $responses = $search->searchMovie('search_with_no_result', array('language' => 'fr-FR'));

        $this->assertInstanceOf(\Generator::class, $responses);
        $this->assertNull($responses->current());
    }

    /**
     * @test
     * @expectedException \vfalies\tmdb\Exceptions\TmdbException
     */
    public function testSearchMovieInvalidOption()
    {
        $search = new Search($this->tmdb);

        $search->searchMovie('star wars', array('fake_option' => 'test'));
    }

    /**
     * @test
     * @expectedException vfalies\tmdb\Exceptions\IncorrectParamException
     */
    public function testSearchMovieEmptyQuery()
    {
        $search = new Search($this->tmdb);

        $search->searchMovie('');
    }

    /**
     * @test
     */
    public function testSearchTVShowValid()
    {
        $json_object = json_decode(file_get_contents('tests/json/searchTVShowOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $search    = new Search($this->tmdb);
        $responses = $search->searchTVShow('star trek', array('language' => 'fr-FR'));

        $this->assertInstanceOf(\Generator::class, $responses);
        $this->assertInstanceOf(Results\TVShow::class, $responses->current());

        return $search;
    }

    /**
     * @expectedException \vfalies\tmdb\Exceptions\TmdbException
     * @test
     */
    public function testSearchTVShowInvalidOption()
    {
        $search = new Search($this->tmdb);

        $search->searchTVShow('star trek', array('fake_option' => 'test'));
    }

    /**
     * @expectedException \vfalies\tmdb\Exceptions\TmdbException
     * @test
     */
    public function testSearchTVShowEmptyQuery()
    {
        $search = new Search($this->tmdb);

        $search->searchTVShow('');
    }

    /**
     * @test
     */
    public function testSearchCollectionValid()
    {
        $json_object = json_decode(file_get_contents('tests/json/searchCollectionOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $search    = new Search($this->tmdb);
        $responses = $search->searchCollection('star wars', array('language' => 'fr-FR'));

        $this->assertInstanceOf(\Generator::class, $responses);
        $this->assertInstanceOf(Results\Collection::class, $responses->current());

        return $search;
    }

    /**
     * @expectedException \vfalies\tmdb\Exceptions\TmdbException
     * @test
     */
    public function testSearchCollectionInvalidOption()
    {
        $search = new Search($this->tmdb);

        $search->searchCollection('star wars', array('fake_option' => 'test'));
    }

    /**
     * @expectedException \vfalies\tmdb\Exceptions\TmdbException
     * @test
     */
    public function testSearchCollectionEmptyQuery()
    {
        $search = new Search($this->tmdb);

        $search->searchCollection('');
    }

    /**
     * @test
     * @depends testSearchMovieValid
     */
    public function testGetPage($search)
    {
        $this->assertEquals(1, $search->getPage());
    }

    /**
     * @test
     * @depends testSearchMovieValid
     */
    public function testTotalPages($search)
    {
        $this->assertEquals(6, $search->getTotalPages());
    }

    /**
     * @test
     * @depends testSearchMovieValid
     */
    public function testTotalResults($search)
    {
        $this->assertEquals(106, $search->getTotalResults());
    }

    /**
     * @test
     */
    public function testSearchPeopleValid()
    {
        $json_object = json_decode(file_get_contents('tests/json/searchPeopleOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $search    = new Search($this->tmdb);
        $responses = $search->searchPeople('Bradley Cooper', array('language' => 'fr-FR'));

        $this->assertInstanceOf(\Generator::class, $responses);
        $this->assertInstanceOf(Results\People::class, $responses->current());

        return $search;
    }

    /**
     * @test
     */
    public function testSearchPeopleEmptyValid()
    {
        $json_object = json_decode(file_get_contents('tests/json/searchPeopleEmptyOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $search    = new Search($this->tmdb);
        $responses = $search->searchPeople('search_with_no_result', array('language' => 'fr-FR'));

        $this->assertInstanceOf(\Generator::class, $responses);
        $this->assertNull($responses->current());
    }

    /**
     * @test
     * @expectedException \vfalies\tmdb\Exceptions\TmdbException
     */
    public function testSearchPeopleInvalidOption()
    {
        $search = new Search($this->tmdb);

        $search->searchPeople('Bradley Cooper', array('fake_option' => 'test'));
    }

    /**
     * @test
     * @expectedException vfalies\tmdb\Exceptions\IncorrectParamException
     */
    public function testSearchPeopleEmptyQuery()
    {
        $search = new Search($this->tmdb);

        $search->searchPeople('');
    }
    /**
     * @test
     */
    public function testSearchCompanyValid()
    {
        $json_object = json_decode(file_get_contents('tests/json/searchCompanyOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $search    = new Search($this->tmdb);
        $responses = $search->searchCompany('lucasfilm', array('language' => 'fr-FR'));

        $this->assertInstanceOf(\Generator::class, $responses);
        $this->assertInstanceOf(Results\Company::class, $responses->current());

        return $search;
    }

    /**
     * @test
     */
    public function testSearchCompanyEmptyValid()
    {
        $json_object = json_decode(file_get_contents('tests/json/searchCompanyEmptyOk.json'));
        $this->tmdb->method('sendRequest')->willReturn($json_object);

        $search    = new Search($this->tmdb);
        $responses = $search->searchCompany('search_with_no_result', array('language' => 'fr-FR'));

        $this->assertInstanceOf(\Generator::class, $responses);
        $this->assertNull($responses->current());
    }

    /**
     * @test
     * @expectedException \vfalies\tmdb\Exceptions\TmdbException
     */
    public function testSearchCompanyInvalidOption()
    {
        $search = new Search($this->tmdb);

        $search->searchCompany('lucasfilm', array('fake_option' => 'test'));
    }

    /**
     * @test
     * @expectedException vfalies\tmdb\Exceptions\IncorrectParamException
     */
    public function testSearchCompanyEmptyQuery()
    {
        $search = new Search($this->tmdb);

        $search->searchCompany('');
    }

}
