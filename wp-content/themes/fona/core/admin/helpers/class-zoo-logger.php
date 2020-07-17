<?php
/**
 * Zoo_Logger
 *
 * @package  Zoo_Theme\Core\Admin\Migration
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

final class Zoo_Logger
{
    // Log levels.
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';

    /**
     * Logged messages
     *
     * @var  array
     */
    private $messages;

    /**
     * File to write logged messages
     *
     * @var  bool
     */
    private $log_file = false;

    /**
     * Constructor
     */
    function __construct($log_file = false)
    {
        $this->messages = [];

        if ($log_file && file_exists($log_file)) {
            $this->log_file = $log_file;
        }
    }

	/**
	 * System is unusable.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 */
	function emergency($message, array $context = [])
    {
		return $this->log(self::EMERGENCY, $message, $context);
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 */
	function alert($message, array $context = [])
    {
		return $this->log(self::ALERT, $message, $context);
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 */
	function critical($message, array $context = [])
    {
		return $this->log(self::CRITICAL, $message, $context);
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 */
	function error($message, array $context = [])
    {
		return $this->log(self::ERROR, $message, $context);
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 */
	function warning($message, array $context = [])
    {
		return $this->log(self::WARNING, $message, $context);
	}

	/**
	 * Normal but significant events.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 */
	function notice($message, array $context = [])
    {
		return $this->log(self::NOTICE, $message, $context);
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 */
	function info($message, array $context = [])
    {
		return $this->log(self::INFO, $message, $context);
	}

	/**
	 * Detailed debug information.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 */
	function debug($message, array $context = [])
    {
		return $this->log(self::DEBUG, $message, $context);
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param  mixed  $level
	 * @param  string  $message
	 * @param  array  $context
	 */
	function log($level, $message, array $context = [])
    {
		$this->messages[] = [
			'timestamp' => time(),
			'level'     => $level,
			'message'   => $message,
			'context'   => $context,
		];
	}

    /**
     * Get logged messages
     *
     * @return  array
     */
    function getLog()
    {
        return $this->messages;
    }
}
