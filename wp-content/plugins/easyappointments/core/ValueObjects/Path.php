<?php
/* ----------------------------------------------------------------------------
 * Easy!Appointments - WordPress Plugin
 *
 * @license GPLv3
 * @copyright A.Tselegidis (C) 2017
 * @link http://easyappointments.org
 * @since v1.0.0
 * ---------------------------------------------------------------------------- */

namespace EAWP\Core\ValueObjects;

/**
 * Path Value Object
 *
 * Use this value object to validate server paths and check that they exist in the server.
 */
class Path
{
    /**
     * @var string
     */
    protected $path;

    /**
     * Class Constructor
     *
     * @param string $path Provide the final destination path of the application (even if it doesn't exist yet).
     *
     * @throws \InvalidArgumentException When provided path is not valid.
     */
    public function __construct($path)
    {
        if (!is_string($path) || empty($path) || !file_exists($path)) {
            throw new \InvalidArgumentException('Invalid $path argument provided: ' . print_r($path, true));
        }

        $this->path = rtrim($path, '/');
    }

    /**
     * Get path as string literal.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->path;
    }
}
