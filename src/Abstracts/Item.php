<?php
/**
 * This file is part of the Tmdb package.
 *
 * (c) Vincent Faliès <vincent@vfac.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Vincent Faliès <vincent@vfac.fr>
 * @copyright Copyright (c) 2017
 */


namespace vfalies\tmdb\Abstracts;

use vfalies\tmdb\lib\Guzzle\Client as HttpClient;
use vfalies\tmdb\Exceptions\TmdbException;
use vfalies\tmdb\Interfaces\TmdbInterface;

/**
 * abstract item class
 * @package Tmdb
 * @author Vincent Faliès <vincent@vfac.fr>
 * @copyright Copyright (c) 2017
 */
abstract class Item
{
    /**
     * id
     * @var int
     */
    protected $id = null;
    /**
     * Tmdb object
     * @var TmdbInterface
     */
    protected $tmdb = null;
    /**
     * Logger
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger = null;
    /**
     * Configuration
     * @var \stdClass
     */
    protected $conf = null;
    /**
     * Params
     * @var array
     */
    protected $params = null;
    /**
     * Data
     * @var \stdClass
     */
    protected $data = null;

    /**
     * Constructor
     * @param \vfalies\tmdb\Interfaces\TmdbInterface $tmdb
     * @param int $item_id
     * @param array $options
     * @param string $item_name
     * @throws \Exception
     */
    public function __construct(TmdbInterface $tmdb, $item_id, array $options, $item_name)
    {
        try
        {
            $this->id     = (int) $item_id;
            $this->tmdb   = $tmdb;
            $this->logger = $tmdb->getLogger();
            $this->conf   = $this->tmdb->getConfiguration();
            $this->params = $this->tmdb->checkOptions($options);
            $this->data   = $this->tmdb->sendRequest(new HttpClient(new \GuzzleHttp\Client()), $item_name . '/' . (int) $item_id, null, $this->params);
        }
        catch (TmdbException $ex)
        {
            throw $ex;
        }
    }

}