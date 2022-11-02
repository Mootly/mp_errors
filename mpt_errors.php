<?php
require_once './src/mpi_errors.php';
require_once './src/mpc_errors.php';
$mpo_errors = new \mpc\mpc_errors();
function printResults($p_errObj) {
  $tCount = $p_errObj->getStatusCount();
  echo '<table class="error-report">';
  echo '<tr><th>#</th><th>success</th><th>code</th><th>source</th><th>severity</th><th>message</th></tr>';
  for ($x = 0; $x < $tCount; $x++) {
    $tError = $p_errObj->getStatus($x);
    $tSuccess = $tError['success'] ? 'true' : 'false';
    echo '<tr>';
    echo "<td>{$x}</td>";
    echo "<td>{$tSuccess}</td>";
    echo "<td>{$tError['code']}</td>";
    echo "<td>{$tError['source']}</td>";
    echo "<td>{$tError['severity']}</td>";
    echo "<td>{$tError['message']}</td>";
    echo '</tr>';
  }
  echo '</table>';
}
function printError($p_err, $p_label = 'Error: ') { echo $p_label, $p_err->getMessage(); }
function printVariables($p_dataObj, $p_string = 'Result: ') {
  echo '<pre>', $p_string , var_export($p_dataObj), '</pre>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>mpc_errors Test Page</title>
  <style type="text/css">
  body { background-color: #201920; color: #ffeeff; padding-bottom: 2.0em; }
  table.error-report { width: 100%; border-collapse: collapse; border: 1px solid #776677; }
  table.error-report th { text-align: left; font-weight: bold; padding: 0.25em; border-bottom: 1px solid #776677; }
  table.error-report td { padding: 0.25em; }
  h3 { margin-top: 3.0em; padding: 0.25em 0.125em 0.125em 0.125em; background-color: #302530; }
  h2 { margin-top: 2.0em; margin-bottom: 1.0em; padding: 0.5em 0.25em; background-color: #302530; border: 1px solid #776677; }
  a { color: #9999dd; }
  a:visited { color: #dd99dd; }
  .clean { background-color: #196019; padding: 0.125em 0.5em; }
  .alert { background-color: #605019; padding: 0.125em 0.5em; }
  .bug   { background-color: #601919; padding: 0.125em 0.5em; }
  </style>
</head>
<body>
<h1><code>mpc_errors</code> Test Page</h1>

<p>Not fancy, but found plenty of edge cases.</p>

<p>Last updated: October 27, 2022</p>

<!-- ***** TABLE OF CONTENTS ***** -->

<h2 id="toc">Sections</h2>

<p>Many of the tests of other methods will already occur during instantiation. Especially the addStatusCodes.</p>

<ul>
  <li><a href="#dependencies">Dependencies</a></li>
  <li><a href="#fix">Unexpected Behaviors</a></li>
</ul>
<ol>
  <li><a href="#instantiate">Instantiate</a></li>
  <li><a href="#setStatus">setStatus</a></li>
  <li><a href="#getStatus">getStatus</a></li>
  <li><a href="#getStatusCodes">getStatusCodes</a></li>
  <li><a href="#getStatusCount">GetStatusCount</a></li>
  <li><a href="#addStatusCodes">addStatusCodes</a></li>
</ol>

<!-- ***** DEPENDENCIES ***** -->

<h2 id="dependencies">Dependencies</h2>

<p>None.</p>

<!-- ***** UNEXPECTED BEHAVIORS ***** -->

<h2 id="fix">Unexpected Behaviors</h2>

<p>All cleaned up.</p>

<!-- ***** TEST __construct ***** -->

<h2 id="instantiate">1. Instantiate</h2>

<ol>
  <li>Instantiate with no arguments</li>
  <li>Instantiate with array</li>
  <li>Instantiate with non-array</li>
  <li>Instantiate with array - multiple values</li>
  <li>Instantiate with malformed array - too shallow</li>
  <li>Instantiate with malformed array - missing key</li>
  <li>Instantiate with malformed array - too many elements</li>
  <li>Instantiate with malformed array - error in first of set</li>
  <li>Instantiate with malformed array - error in last of set</li>
  <li>Instantiate with malformed array - too deep</li>
  <li>Instantiate with array with duplicate element</li>
  <li>Instantiate with array with duplicate element with replacements allowed</li>
  <li>Instantiate with specified history length</li>
  <li>Instantiate with invalid specified history length - string</li>
  <li>Instantiate with invalid specified history length - negative integer</li>
</ol>

<h3>Test 1.1: No Arguments</h3>
<p class="clean">Good.</p>
<?php
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors();
  printResults($mpo_errors);
?>

<h3>Test 1.2: With Array - Single Entry</h3>
<p class="clean">Good.</p>
<?php
  $t_array = ['techno' => ['Notice' ,'Best Success 1.2.']];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array);
  printResults($mpo_errors);
?>

<h3>Test 1.3: With Array - Multiple Entries</h3>
<p class="clean">Good.</p>
<?php
  $t_array = ['techno' => ['Notice' ,'Best Success 1.3.'],
              'techno1' => ['Notice' ,'Bestest Success.'],
              'techno2' => ['Notice' ,'Even Bester Success.']];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array);
  printResults($mpo_errors);
?>

<h3>Test 1.4: With Non-Array</h3>
<p class="alert">Fatal Error. Correct.</p>
<ul>
  <li>Error 1: Fatal error.</li>
  <li>Error 2: Object was not instatiated.</li>
</ul>
<?php
  $t_nonarray       = 'techno';
  $mpo_errors       = false;
  try { $mpo_errors = new \mpc\mpc_errors($t_nonarray);
} catch(Throwable $e) { printError($e, 'Error 1: '); }
  echo '<br />';
  try { printResults($mpo_errors);
} catch(Throwable $e) { printError($e, 'Error 2: '); }
?>

<h3>Test 1.5: With Malformed Array - Too Shallow</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<?php
  $t_array          = ['none' => 'Notice'];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array);
  printResults($mpo_errors);
?>

<h3>Test 1.6: With Malformed Array - Missing Key in Entry</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<?php
  $t_array          = ['none', 'Notice'];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array);
  printResults($mpo_errors);
?>

<h3>Test 1.7: With Malformed Array - Too Many Values in Entry</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<?php
  $t_array          = ['techno' => ['Notice' ,'Best Success 1.7.', 'Worst Sucess 1.7']];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array);
  printResults($mpo_errors);
?>

<h3>Test 1.8: With Malformed Array - Error in First of Set</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<?php
  $t_array = ['entry1' => ['Notice' ,'Best Success 1.8.', 'Worst Sucess 1.8'],
              'entry2' => ['Notice' ,'Bester Success.']];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array);
  $t_result         = $mpo_errors->getStatusCodes();
  printVariables($t_result['entry1']);
  printVariables($t_result['entry2']);
  printResults($mpo_errors);
?>

<h3>Test 1.9: With Malformed Array - Error in Last of Set</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<?php
  $t_array = ['entry1' => ['Notice' ,'Best Success 1.9.'],
              'entry2' => ['Notice']];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array);
  $t_result         = $mpo_errors->getStatusCodes();
  printVariables($t_result['entry1']);
  printVariables($t_result['entry2']);
  printResults($mpo_errors);
?>

<h3>Test 1.10: With Malformed Array - Too Deep</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<?php
  $t_array = ['none' => ['Notice', ['Notice' ,'Best Success 1.10.']]];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array);
  printResults($mpo_errors);
?>

<h3>Test 1.11 With Array - Duplicate Element</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<?php
  $t_array = ['none' => ['Notice' ,'Best Success.']];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array);
  printResults($mpo_errors);
?>

<h3>Test 1.12: With Array - Duplicate Element with Replacements Allowed</h3>
<p class="clean">Good.</p>
<pre>
<?php
  $t_array = ['none' => ['Notice'  ,'Best Success.']];
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors($t_array, true);
  printResults($mpo_errors);
?>
</pre>

<h3>Test 1.13: NULL Array - Change Status History Length (to 5)</h3>
<p class="clean">Good.</p>
<p>Add six errors. Instantiation message will drop off the history.</p>
<pre>
<?php
  $mpo_errors       = false;
  $mpo_errors       = new \mpc\mpc_errors(null, false, 5);
  for ($x=0; $x<6; $x++) { $t_stat = $mpo_errors->setStatus('noob'); }
  printResults($mpo_errors);
  ?>
</pre>

<h3>Test 1.13: NULL Array - Change Status History Length to Invalid Value - String</h3>
<p class="alert">Fatal Error. Correct.</p>
<p>Pass string when integer expected for status log length.</p>
<pre>
<?php
  $mpo_errors       = false;
  try { $mpo_errors = new \mpc\mpc_errors(null, false, 'badval');
  } catch(Throwable $e) { printError($e, 'Error 1: '); }
?>
</pre>

<h3>Test 1.14: NULL Array - Change Status History Length to Invalid Value - Negative Number</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<p>Pass negative number for status log length.</p>
<pre>
<?php
  $mpo_errors       = false;
  try { $mpo_errors = new \mpc\mpc_errors(null, false, -8);
  } catch(Throwable $e) { printError($e, 'Error 1: '); }
  printResults($mpo_errors);
?>
</pre>

<!-- ***** TEST setStatus ***** -->

<h2 id="setStatus">setStatus</h2>

<ol>
  <li>Set with no arguments</li>
  <li>Set with valid error code, no source</li>
  <li>Set with valid error code and source</li>
  <li>Set with invalid error code</li>
  <li>Set with invalid data type for error code</li>
  <li>Set with invalid data type for source</li>
</ol>

<?php
  $mpo_errors = false;
  $mpo_errors = new \mpc\mpc_errors();
?>

<h3>Test 2.1: Set with no arguments</h3>
<p class="alert">Fatal Error. Correct.</p>
<?php
  try { $mpo_errors->setStatus();
} catch(Throwable $e) { printError($e, 'Error 1: '); }
echo '<br />' ;
  try { printResults($mpo_errors);
} catch(Throwable $e) { printError($e, "Error 2: "); }
?>

<h3>Test 2.2: Set with valid error code, no source</h3>
<p class="clean">Good.</p>
<?php
  $mpo_errors->setStatus('none');
  printResults($mpo_errors);
?>

<h3>Test 2.3: Set with valid error code and source</h3>
<p class="clean">Good.</p>
<?php
  $mpo_errors->setStatus('mpe_badURL','bad url test');
  printResults($mpo_errors);
?>

<h3>Test 2.4: Set with invalid valid error code</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<?php
  $mpo_errors->setStatus('invalid_code','invalid code test');
  printResults($mpo_errors);
?>

<h3>Test 2.5: Set with invalid data type for error code</h3>
<p class="alert">Fatal error. Correct.</p>
<?php
  try { $mpo_errors->setStatus(['a','b'],'invalid type test');
  } catch(Throwable $e) { printError($e, 'Error 1: '); }
echo '<br />' ;
  try { printResults($mpo_errors);
  } catch(Throwable $e) { printError($e, 'Error 2: '); }
?>

<h3>Test 2.6: Set with invalid data type for source</h3>
<p class="alert">Fatal error. Correct.</p>
<?php
  try { $mpo_errors->setStatus('none',['a','b']);
  } catch(Throwable $e) { printError($e, 'Error 1: '); }
  echo '<br />' ;
  try { printResults($mpo_errors);
  } catch(Throwable $e) { printError($e, 'Error 12: '); }
?>

<!-- ***** TEST getStatus ***** -->

<h2 id="getStatus">getStatus</h2>

<p>getStatus should not add any new errors.</p>
<ol>
  <li>Get with no arguments</li>
  <li>Get with valid argument (0)</li>
  <li>Get with valid argument (count-1)</li>
  <li>Get with invalid argument (-1)</li>
  <li>Get with invalid argument (count+1)</li>
  <li>Get with invalid data type for argument</li>
</ol>

<h3>Test 3.1: Get with no arguments</h3>
<p class="clean">Good.</p>
<p>Defaults to most recent error.</p>
<?php
  $t_result         = $mpo_errors->getStatus();
  printVariables($t_result);
  printResults($mpo_errors);
?>

<h3>Test 3.2: Get with valid argument (0)</h3>
<p class="clean">Good.</p>
<?php
  $t_result         = $mpo_errors->getStatus(0);
  printVariables($t_result);
  printResults($mpo_errors);
?>

<h3>Test 3.3: Get with valid argument (count-1)</h3>
<p class="clean">Good.</p>
<?php
  $t_count          = $mpo_errors->getStatusCount();
  $t_result         = $mpo_errors->getStatus($t_count-1);
  printVariables($t_result);
  printResults($mpo_errors);
?>

<h3>Test 3.4: Get with invalid argument (-1)</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<p>Return false. Don't add status messages to the log.</p>
<?php
  $t_count          = $mpo_errors->getStatusCount();
  $t_result         = $mpo_errors->getStatus($t_count+1);
  printVariables($t_result);
  printResults($mpo_errors);
?>

<h3>Test 3.5: Get with invalid argument (count+1)</h3>
<p class="clean">Non-Fatal Error. Correct.</p>
<p>Return false. Don't add status messages to the log.</p>
<?php
  $t_count          = $mpo_errors->getStatusCount();
  $t_result         = $mpo_errors->getStatus(-1);
  printVariables($t_result);
  printResults($mpo_errors);
?>

<h3>Test 3.6: Get with invalid data type for argument</h3>
<p class="alert">Fatal error. Correct.</p>
<?php
  $t_result = 'have not run yet';
  try { $t_result = $mpo_errors->getStatus('steve');
  } catch(Throwable $e) { printError($e); }
  printVariables($t_result);
  printResults($mpo_errors);
?>

<!-- ***** TEST getStatusCodes ***** -->

<h2 id="getStatusCodes">getStatusCodes</h2>

<ol>
  <li>Get with no arguments</li>
  <li>Get with argument</li>
  <li>Get after addStatusCodes</li>
</ol>

<?php
  $mpo_errors = false;
  $mpo_errors = new \mpc\mpc_errors();
?>

<h3>Test 4.1: Get status list with no arguments</h3>
<p class="clean">Good.</p>

<?php
  $t_result         = $mpo_errors->getStatusCodes();
  printVariables($t_result);
  printResults($mpo_errors);
?>
<h3>Test 4.2: Get status list with argument</h3>
<p class="clean">Good.</p>
<p>No argument expected, so ignore.</p>

<?php
  $t_result         = $mpo_errors->getStatusCodes('anything');
  printVariables($t_result);
  printResults($mpo_errors);
?>

<h3>Test 4.2: Get status list after addStatusCodes</h3>
<p class="clean">Good.</p>

<?php
  $t_array = ['techno' => ['Notice' ,'Best Success.'],
              'techno1' => ['Notice' ,'Best Success.'],
              'techno2' => ['Notice' ,'Best Success.']];
  $t_result         = $mpo_errors->addStatusCodes($t_array);
  $t_result         = $mpo_errors->getStatusCodes();
  printVariables($t_result);
  printResults($mpo_errors);
?>

<!-- ***** TEST getStatusCount ***** -->

<h2 id="getStatusCount">GetStatusCount</h2>

<ol>
  <li>Get with no arguments</li>
  <li>Get with argument</li>
</ol>

<h3>Test 5.1: Get count with no arguments</h3>
<p class="clean">Good.</p>

<?php
  $t_result         = $mpo_errors->getStatusCount();
  printVariables($t_result);
  printResults($mpo_errors);
?>

<h3>Test 5.2: Get count with argument</h3>
<p class="clean">Good.</p>
<p>None expected. Ignore argument.</p>

<?php
  $t_result         = $mpo_errors->getStatusCount(1);
  printVariables($t_result);
  printResults($mpo_errors);
?>

<!-- ***** TEST getStatusCodes ***** -->

<h2 id="addStatusCodes">addStatusCodes</h2>

<p>Already tested under instantiation. These are additional edge cases.</p>

<ol>
  <li>Test adding duplicates with invalid duplicate flag - string.</li>
  <li>Test adding duplicates with invalid duplicate flag array.</li>
</ol>

<?php
  $mpo_errors = false;
  $mpo_errors = new \mpc\mpc_errors();
?>

<h3>Test 6.1: Invalid duplicate flag (string).</h3>

<p>PHP evaluates string to boolean.</p>

<?php
  $t_array = ['techno' => ['Notice' ,'Best Success.'],
              'techno1' => ['Notice' ,'Best Success.'],
              'techno2' => ['Notice' ,'Best Success.']];
  $t_result         = $mpo_errors->addStatusCodes($t_array, 'bad value');
  printVariables($t_result);
  $t_result         = $mpo_errors->getStatusCodes();
  printVariables($t_result);
  printResults($mpo_errors);
?>

<?php
  $mpo_errors = false;
  $mpo_errors = new \mpc\mpc_errors();
?>

<h3>Test 6.2: Invalid duplicate flag (array).</h3>
<p class="alert">Fatal error. Correct.</p>

<?php
  $t_array = ['techno' => ['Notice' ,'Best Success.'],
              'techno1' => ['Notice' ,'Best Success.'],
              'techno2' => ['Notice' ,'Best Success.']];
  $t_result = 'did not run';
  try { $t_result = $mpo_errors->addStatusCodes($t_array, $t_array); }
  catch(Throwable $e) { printError($e); }
  printVariables($t_result);
  printResults($mpo_errors);
?>

</body>
</html>
