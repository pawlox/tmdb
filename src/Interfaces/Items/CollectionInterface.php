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


namespace vfalies\tmdb\Interfaces\Items;

/**
 * Interface for Collection type object
 * @package Tmdb
 * @author Vincent Faliès <vincent@vfac.fr>
 * @copyright Copyright (c) 2017
 */
interface CollectionInterface
{

    /**
     * Get collection ID
     * @return int
     */
    public function getId();

    /**
     * Get collection name
     * @return string
     */
    public function getName();

    /**
     * Get collection parts
     * @return Generator|SearchMovieResult
     */
    public function getParts();

    /**
     * Get poster path
     */
    public function getPosterPath();

    /**
     * Get backdrop path
     */
    public function getBackdropPath();
}