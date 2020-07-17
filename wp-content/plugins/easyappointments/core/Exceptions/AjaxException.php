<?php
/* ----------------------------------------------------------------------------
 * Easy!Appointments - WordPress Plugin
 *
 * @license GPLv3
 * @copyright A.Tselegidis (C) 2017
 * @link http://easyappointments.org
 * @since v1.0.0
 * ---------------------------------------------------------------------------- */

namespace EAWP\Core\Exceptions;

/**
 * AJAX Exception
 *
 * This exception class is used to convert normal caught exceptions to JSON-encoded strings that will be
 * returned back to JavaScript.
 */
class AjaxException extends \Exception
{
    /**
     * Get a JSON encoded exception response.
     *
     * @param \Exception $ex The exception object to be serialized.
     * @param bool $encode Whether to "json_encode" the result or not.
     *
     * @return mixed Associative array or JSON-encoded information of the provided exception.
     */
    public static function response(\Exception $ex, $encode = false)
    {
        $exceptionInformation = array(
            'exception' => true,
            'message' => $ex->getMessage(),
            'code' => $ex->getCode(),
            'file' => $ex->getFile(),
            'line' => $ex->getLine(),
            'trace' => $ex->getTrace(),
            'traceAsString' => $ex->getTraceAsString()
        );

        return ($encode) ? json_encode($exceptionInformation) : $exceptionInformation;
    }
}
