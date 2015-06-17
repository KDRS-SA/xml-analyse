<?php

	### 21_parse-read.inc.php ###
	
	# Sections in this inc-file: #
	# 21.1 - Read Source #
	
	# global variables created in this include-section: #
	
	// Input header rows
	$nInputHeaderRows = $arrRunParameter['input_header_rows'];
	
	// Log Error for empty source rows?
	$bLogSourceEmptyRowError = true;
	
	// $arrSource = input lines of data to be processed after reading from source-file
	$arrSource = array ();
	$nLineSourceHeaderLimit = $nInputHeaderRows - 1;	// counting from 0
	$nLineSourceHeader = 0;
	$nLineSource = 0;
	$nLineSourceData = 0;
	$nLineSourceNoData = 0;
	$nSourceError = 0;
	# END global variables #
	
	// PHP script
	$strIncPhpScript = pathinfo(__file__)['basename'];
	
	// START inc-section
	if ($bLogInc) {
		$strDescription = PHP_EOL . 'START php include >>> ' . $strIncPhpScript . ' >>>' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	if ($bDebugLogErrorFiles) {
		// DEBUG ERROR
		$strDescription = PHP_EOL . 'PHP DEBUG ERROR (testing log to error-file)' . PHP_EOL;
		$strLine = 'SECOND line with semicolon ";" debugging' . PHP_EOL;
		logError(0, $strDescription, $strLine, 2);
	}
	
	# 21.1 - Read Source #
	
	if (!$fSource = fopen($filenameSource, 'r')) {
		// Error: failed to open file
		$strDescription = PHP_EOL . '## error opening source filename [' . $filenameSource . '] ##' .  PHP_EOL;
		$strLine = '';
		logError(0, $strDescription, $strLine, 2);
									  
		$strDescription = '> PHP EXIT <' . PHP_EOL;
		$strLine = '';
		logError(0, $strDescription, $strLine, 2);
									  
		flushExit();
		exit;
	} else {
		// Log open source file
		$strDescription = '- open source filename [' . $filenameSource . ']' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	while ( !feof($fSource) ) {
		$strLineFile = trim(fgets($fSource));
		if (false === $strLineFile) {
			$strDescription = '-- done reading source file (readline === false)' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		} else {
			if ( $nLineSourceHeaderLimit >= $nLineSource ) {
				$nLineSourceHeader++;
			} else {
				// Datarow (all above belongs to header)
				if ('' == $strLineFile) {
					// Count nodata-line
					$nLineSourceNoData++;
					
					if ($bLogSourceEmptyRowError) {
						// Count as error
						$nSourceError++;
						
						// Log error (file only)
						$strDescription = 'SOURCE: Empty line in source row nr. ' . ($nLineSource + 1) . PHP_EOL;
						$strLine = '';
						logError( ($nLineSource+1) , $strDescription, $strLine, 0);
					}		
				} else {
					// Check Source row for char-separator only
					$strTemp = str_replace($strCSV, '', $strLineFile);
					
					if ('' == $strTemp) {
						// Count empty-all-columns line
						$nLineSourceNoData++;
						
						if ($bLogSourceEmptyRowError) {
							// Count as error
							$nSourceError++;
							
							// Log error (file only)
							$strDescription = 'SOURCE: All columns empty line in source row nr. ' . ($nLineSource + 1) . PHP_EOL;
							$strLine = $strLineFile;
							logError( ($nLineSource+1) , $strDescription, $strLine, 0);
						}
					} else {
						// Count data-line
						$nLineSourceData++;
						
						// Populate array with this lines columns from char-seperated line
						$arrTemp = explode($strCSV, $strLineFile);
						
						// add new row to Source array
						$arrSource[$nLineSource] = $arrTemp;
					}	// if empty-all-columns line
				}	// if nodata line
			}
			// Count all lines
			$nLineSource++;
		}
	}
	
	// Log reading source results
	$strDescription = '- done reading ' . $nLineSource . ' lines from file (';
	$strDescription .= $nLineSourceHeader . ' header, ' . $nLineSourceData . ' data, +';
	$strDescription .= $nLineSourceNoData . ' empty)';
	$strLine = '';
	logLog(0, $strDescription, $strLine, 2);
	
	if (0 == $nSourceError) {
		// Log no errors
		$strDescription = '- no source errors' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	} else {
		// Error: source errors detected
		$strDescription = PHP_EOL . '- source errors detected: ' . $nSourceError .  PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
		
	// Close source file
	fclose($fSource);
	# END 21.1 - Read Source #
	
	// END inc-section
	if ($bLogInc) {
		$strDescription = 'END php include <<< ' . $strIncPhpScript . ' <<<' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
?>
