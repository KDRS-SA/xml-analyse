<?php

	### 41_parse-write.inc.php ###
	
	# Sections in this inc-file: #
	# 41.1 - Write Target #
	
	# global variables created in this include-section: #
	$nLineTargetWrite = 0;
	$nLineTargetEmpty = 0;
	# END global variables #
	
	// PHP script
	$strIncPhpScript = pathinfo(__file__)['basename'];
	
	// START inc-section
	if ($bLogInc) {
		$strDescription = PHP_EOL . 'START php include >>> ' . $strIncPhpScript . ' >>>' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	# 41.1 - Write Target #
	
	if (!$fTarget = fopen($filenameTarget, 'w')) {
		// Error: failed to open file
		$strDescription = PHP_EOL . '## error opening target filename [' . $filenameTarget . '] ##' .  PHP_EOL;
		$strLine = '';
		logError(0, $strDescription, $strLine, 2);
									  
		$strDescription = '> PHP EXIT <' . PHP_EOL;
		$strLine = '';
		logError(0, $strDescription, $strLine, 2);
									  
		flushExit();
		exit;
	} else {
		// Log open target file
		$strDescription = '- open target filename [' . $filenameTarget . ']' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	foreach ($arrTarget as $arrLine) {
		$strLineFile = implode($strCSV, $arrLine) .  PHP_EOL;
		if ('' == $strLineFile) {
			// Empty line not written to file
			$nLineTargetEmpty++;
		} else {
			// Write target-file line
			fwrite ($fTarget, $strLineFile);
			$nLineTargetWrite++;
		}
	}
	
	// Log writing target results
	$strDescription = '- done writing ' . $nLineTargetWrite . ' lines to file' . PHP_EOL;
	$strLine = '';
	logLog(0, $strDescription, $strLine, 2);
	
	if (0 !== $nLineTargetEmpty) {
		// Log empty target lines
		$strDescription = '- encountered ' . $nLineTargetWrite . ' empty target lines not writen to file' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	// Close target file
	fclose($fTarget);
	# END 41.1 - Write Target #
	
	// END inc-section
	if ($bLogInc) {
		$strDescription = 'END php include <<< ' . $strIncPhpScript . ' <<<' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
?>
