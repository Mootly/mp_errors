<?php
/**
  * Manage error message handling for objects and applications.
  * Implements mpi_errors which see for detailed description.
  *
  * Public Properties:
  *   None.
  * Methods:
  * @method bool              __construct(array, bool, int)
  *   On instantiation, can add or replace status messages.
  * @method bool              setStatus(string, string)
  * @method array|bool        getStatus(int)
  * @method array             getStatusCodes()
  * @method int               getStatusCount()
  * @method bool              addStatusCodes(array, bool)
  *
  * @copyright 2017-2022 Mootly Obviate
  * @package   mooseplum
  * @version   1.0.0
  * --- Revision History ------------------------------------------------------ *
  * 2022-07-01 | New PHP 8.0 version ready.
  * --------------------------------------------------------------------------- */
class mpc_errors implements mpi_errors {
  protected $iName;
  protected static $iCount = 0;
  protected $eList            = array();
  protected $eCount           = 100;
  protected $response         = array(
    'success'       => false,
    'code'          => 'none',
    'source'        => '',
    'severity'      => '',
    'message'       => ''
  );
  protected $status            = array(
    'none'          => ['Notice'  ,'Success.'],
    'noAction'      => ['Notice'  ,'No action taken.'],
    'mpe_set00'     => ['Warning' ,'Some properties already exist and were not replaced.'],
    'mpe_set01'     => ['Warning' ,'This property already exists and was not replaced.'],
    'mpe_locked'    => ['Warning' ,'Requested property is locked from updates.'],
    'mpe_secure'    => ['Warning' ,'Requested property is secured from further updates.'],
    'unknown'       => ['Error'   ,'Unknown error code.'],
    'mpe_null'      => ['Error'   ,'Requested value or property does not exist.'],
    'mpe_nomethod'  => ['Error'   ,'Requested method does not exist.'],
    'mpe_badURL'    => ['Error'   ,'Invalid URL.'],
    'mpe_param00'   => ['Error'   ,'Invalid parameter.'],
    'mpe_param01'   => ['Error'   ,'Invalid history length.'],
    'mpe_param02'   => ['Error'   ,'Attempted to add invalid error message entry.'],
    'mpe_param03'   => ['Error'   ,'Too few parameters.'],
    'mpe_param04a'  => ['Error'   ,'Too many parameters.'],
    'mpe_param04b'  => ['Warning' ,'Too many parameters. Not all were processed.'],
  );
# *** END property assignments ------------------------------------------------ *
#
# *** BEGIN constructor ------------------------------------------------------- *
/**
  * Constructor
  * Create object. Add instance specific status messages to the $status array.
  *
  * @param  array   $plist    - List of status messages to add.
  * @param  bool    $pReplace - Whether to replace existing errors with same code.
  * @param  int     $pCache   - Number of status records to store.
  * @return bool
  */
  public function __construct(array $pList=NULL, bool $pReplace=false, int $pCache = 100) {
# Constructor does not return values.
# Remember to check getStatus for errors
    $this->iName    = get_class().'_'.self::$iCount++;
    $tMethod        = $this->iName.'::'.__METHOD__;
    if ($pList) {
      $this->response['success'] = $this->addStatusCodes($pList, $pReplace);
    } else {
      $this->response['success'] = $this->setStatus('none', $tMethod);
    }
    if (is_int($pCache) && ($pCache > 0)) {
      $this->eCount = $pCache;
    } else {
      $this->response['success'] = $this->setStatus('mpe_param01', $tMethod);
    }
    return true;
  }
# *** END constructor --------------------------------------------------------- *
#
# *** BEGIN setStatus --------------------------------------------------------- *
# If the status code is in error, call self to record mpe_null error
  public function setStatus(string $pCode, string $pSource=NULL) : bool {
    $tMethod        = $this->iName.'::'.__METHOD__;
    if (!empty($this->status[$pCode])) {
      $this->response['success']        = ($pCode == 'none') ? true : false;
      $this->response['code']           = (empty($this->status[$pCode])) ? 'unknown' : $pCode;
      $this->response['source']         = (empty($pSource))  ? 'source not specified' : $pSource;
      $this->response['severity']       = $this->status[$pCode][0];
      $this->response['message']        = $this->status[$pCode][1];
      array_unshift($this->eList, $this->response);
      if (count($this->eList) > $this->eCount) { array_pop($this->eList); }
    } else {
      $this->response['success'] = $this->setStatus('mpe_null', $tMethod);
    }
    return $this->response['success'] ;
  }
# *** END setStatus ----------------------------------------------------------- *
#
# *** BEGIN getStatus --------------------------------------------------------- *
  public function getStatus(int $pWhich=0) : array|bool {
# Do not create new error record if out of range,
# just return false
    $tReturn = false;
    if (!empty($this->eList[$pWhich])) { $tReturn = $this->eList[$pWhich]; }
    return $tReturn;
  }
# *** BEGIN getStatusCodes ---------------------------------------------------- *
  public function getStatusCodes() : array { return $this->status; }
# *** END - getStatusCodes ---------------------------------------------------- *
#
#*** BEGIN getStatusCount ----------------------------------------------------- *
 public function getStatusCount() : int { return count($this->eList); }
# *** END - getStatusCount ---------------------------------------------------- *
#
# *** BEGIN addStatusCodes ---------------------------------------------------- *
  public function addStatusCodes(array $pList=NULL, bool $pReplace=false) : bool {
    $tMethod        = $this->iName.'::'.__METHOD__;
    if (!empty($pList)) {
      foreach($pList as $tKey => $tVal) {
        if ((is_string($tKey)) && (is_array($tVal)) && (count($tVal) == 2) && is_string($tVal[0]) && is_string($tVal[1])) {
          if (!empty($this->status[$tKey]) && !($pReplace)) {
            $this->response['success']  = $this->setStatus('mpe_set00', $tMethod);
          } else {
            $this->status[$tKey]        = $tVal;
            $this->response['success']  = $this->setStatus('none', $tMethod);
          }
        } else {
          $this->response['success']    = $this->setStatus('mpe_param02', $tMethod);
        }
      }
    } else {
      $this->response['success']        = $this->setStatus('noAction', $tMethod);
    }
    return $this->response['success'];
  }
# *** END - addStatusCodes --------------------------------------------------- *
}
// End mpc_parts -------------------------------------------------------------- *
