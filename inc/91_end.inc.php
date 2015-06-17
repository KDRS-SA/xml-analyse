<?php

	### 91_end.inc.php ###
	
	// PHP script
	$strIncPhpScript = pathinfo(__file__)['basename'];
	
	// START inc-section
	if ($bLogInc) {
		$strDescription = PHP_EOL . 'START php include >>> ' . $strIncPhpScript . ' >>>' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	// flush output to log-file and error-file (if content still inside arrays)
	flushLog();
	flushError();
	
	// END inc-section
	if ($bLogInc) {
		$strDescription = 'END php include <<< ' . $strIncPhpScript . ' <<<' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	// PHP slutt
	$strDescription = PHP_EOL . 'PHP start [' . $strStartDateTime . ']' . PHP_EOL;
	$strLine = '';
	logLog(0, $strDescription, $strLine, 2);

	// Error count
	if (0 == $nError) {
		// Log no errors
		$strDescription = '- no errors detected' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	} else {
		// Log error count
		$strDescription = '- error count = ' . $nError . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	$timeEnd = time();
	$strEndDateTime = date('Y-m-d\TH:i:sP', $timeEnd);

	$strDescription = 'PHP slutt [' . $strEndDateTime . ']' . PHP_EOL;
	$strLine = '';
	logLog(0, $strDescription, $strLine, 2);
	
	// final output to log-file and error-file (if content still inside arrays)
	flushLog();
	flushError();
	
	// Close log-file
	if ($bLogFileOpen) {
		fclose($fLog);
		$bLogFileOpen = false;
	}
	
	// Close error-file
	if ($bErrorFileOpen) {
		fclose($fError);
		$bErrorFileOpen = false;
	}
	
?>
