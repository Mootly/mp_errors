<?php
/**
  * Basic error reporting methods for objects.
  * Recommended storage/return format for responses (array):
  * - success       bool    - Was the last call successful?
  * - code          string  - Status code
  * - source        string  - Source of status code.
  * - severity      string  - Note, Warning, Error
  * - message       string  - Results or status message.
  *
  * Code, severity and message come from the $status array.
  * $status[code] = [severity, message]
  *
  * Source comes from calling method:
  * Preferred format for source is use a pseudo-namespace.
  * Core objects use class + instance count + method
  * e.g. mpc_errors_1::getStatus
  * Code is as follows:
  * __construct()   - $this->iName  = get_class().'_'.self::$iCount++;
  * calling methods - $tMethod      = $this->iName.'::'.__METHOD__;
  *
  * @copyright 2021-2022 Mootly Obviate
  * @package   mooseplum
  * --- Revision History ------------------------------------------------------ *
  * 2022-01-01 | New version.
  * --------------------------------------------------------------------------- */
interface mpi_errors {
  # *** BEGIN setStatus ------------------------------------------------------- *
  /**
  * Set a status message for last action.
  *
  * @param string   $pCode    New value for current status.
  * @param string   $pSource  (optional) Where error originated.
  * @return bool
  */
  public function setStatus(string $pCode, ?string $pSource) : bool;
# *** END - setStatus --------------------------------------------------------- *
#
# *** BEGIN getStatus --------------------------------------------------------- *
/**
  * Return status message.
  * If storing past messages add optional parameter to specify which to return,
  * otherwise return the last status code.
  * Use string parameter since this allows keywords and numeric values.
  * Remember to only update the status array on error, not on success.
  *
  * @param int      $pWhich   Which status message to return from the stack.
  * @return array|bool
  */
  public function getStatus(int $pWhich) : array|bool;
# *** END - getStatus --------------------------------------------------------- *
#
# *** BEGIN getStatusCodes ---------------------------------------------------- *
/**
  * Return array of available error codes for reference.
  *
  * @return array|bool
  */
  public function getStatusCodes() : array|bool;
# *** END - getStatusCodes ---------------------------------------------------- *
#
# *** BEGIN getStatusCount ---------------------------------------------------- *
/**
  * Return the number of messages in the status queue.
  *
  * @return int
  */
  public function getStatusCount() : int;
# *** END - getStatusCount ---------------------------------------------------- *
#
# *** BEGIN addStatusCodes ---------------------------------------------------- *
/**
  * Submit an array of error codes to add to the list.
  * If not overwrite flag, method should not overwrite exsting codes.
  * Optional: Return a list of error codes that could not be set.
  *
  * @param array    $pList    Array of status codes.
  * @param bool     $pReplace Whether to overwrite matched existing codes.
  * @return bool
  */
  public function addStatusCodes(array $pList, bool $pReplace) : bool;
# *** END - addStatusCodes ---------------------------------------------------- *
}
// End mpc_paths -------------------------------------------------------------- *
