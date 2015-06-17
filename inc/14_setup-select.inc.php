<?php

	### 14_setup-select.inc.php ###
	
	# Sections in this inc-file: #
	# 14.1 - Decide setup parameters
	# 14.2 - Get Source filename #
	# 14.3 - Get Target filename #
	# 14.4 - Get log filename #
	
	# global variables created in this include-section: #
	# END global variables #
	
	// PHP script
	$strIncPhpScript = pathinfo(__file__)['basename'];
	
	// START inc-section
	if ($bLogInc) {
		$strDescription = PHP_EOL . 'START php include >>> ' . $strIncPhpScript . ' >>>' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	# 14.1 - Decide setup parameters
		
		//ToDo: Implement selection from PHP-, INI-file- and setup.xml-parameters
		
		// For now, use INI-file-parameters only!
		$arrRunParameter = $arrIniParameter;
		
		## Select CSV charsep ##
			// csv_charsep_ascii_num has priority and used if available
			// if not then use csv_charsep
			// if not then use default csv_charsep
			
			// csv_charsep_ascii_num
			$bTmp = false;
			if ( isset($arrRunParameter['csv_charsep_ascii_num']) ) {
				$strTemp = $arrRunParameter['csv_charsep_ascii_num'];
				if ('' !== $strTemp) {
					$nTemp = (integer) $strTemp;
					if ( !is_int($nTemp) ) {
						// ascii char number not an integer
						$strDescription = PHP_EOL . '## error: csv_charsep_ascii_num not an integer: "' . $strTemp . '"' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						$strDescription = '> PHP EXIT <' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						flushExit();
						exit;					
					} elseif ( !(32 < $nTemp) OR (255 < $nTemp) ) {
						// ascii char number out of valid range
						$strDescription = PHP_EOL . '## error: csv_charsep_ascii_num out of range: "' . $strTemp . '"' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						$strDescription = '> PHP EXIT <' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						flushExit();
						exit;
					}
					
					$bTmp = true;
					$strCSV = chr($nTemp);
					
					// Log selected CSV charsep
					$strDescription = '- selected CSV charsep ascii ' . $nTemp . ' = "' . $strCSV . '"' . PHP_EOL;
					$strLine = '';
					logLog(0, $strDescription, $strLine, 2);
				}
			}	// END csv_charsep_ascii_num
			
			// csv_charsep
			if ( !$bTmp ) {
				if ( isset($arrRunParameter['csv_charsep_string']) ) {
					$strTemp = $arrRunParameter['csv_charsep_string'];
					if ('' !== $strTemp) {
						$bTmp = true;
						$strCSV = $strTemp;
						
						// Log selected CSV charsep
						$strDescription = '- selected CSV charsep string = "' . $strCSV . '"' . PHP_EOL;
						$strLine = '';
						logLog(0, $strDescription, $strLine, 2);
					}
				}
			}	// END csv_charsep
			
			// default csv_charsep
			if ( !$bTmp ) {
				$strCSV = $strDefaultCSV;
				
				// Log selected CSV charsep
				$strDescription = '- selected default CSV charsep = "' . $strCSV . '"' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, 2);
			}
			
							
		## END Select CSV charsep ##
		
		## Select CSV entity ##			
			$bTmp = false;
			if ( isset($arrRunParameter['csv_entity']) ) {
				$strTemp = $arrRunParameter['csv_entity'];
				if ('' !== $strTemp) {
					$bTmp = true;
					$strCSVEntity = $strTemp;
				}
			}
			
			if ( !$bTmp ) {
				$strCSVEntity = $strDefaultCSVEntity;
			}
			
			// Log selected CSV entity
			$strDescription = '- selected CSV entity = "' . $strCSVEntity . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);				
		## END Select CSV entity ##
		
	# END 14.1 - Decide setup parameters
	
	# 14.2 - Get Source filename #
		
		$filenameSource = $arrRunParameter['source_drive'];
		if ('' !== $filenameSource) {
			$filenameSource .= ':\\';
		}
		$filenameSource .= $arrRunParameter['source_dir'];
		if ('' !== $filenameSource) {
			$filenameSource .= '\\';
		}
		$filenameSource .= $arrRunParameter['source_filename'] . '.' . $arrRunParameter['source_ext'];
		
		// Log source filename
		$strDescription = '- source filename [' . $filenameSource . ']' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	# END 14.2 - Get Source filename #
	
	# 14.3 - Get Target filename #
	
		if ('yyyymmdd-hhnn' == $arrRunParameter['target_timestamp']) {
			$strTargetTimestamp = date('Ymd-Hi', $timeStart);
		} else {
			$strTargetTimestamp = "";
		}
		
		$filenameTarget = $arrRunParameter['target_drive'];
		if ('' !== $filenameTarget) {
			$filenameTarget .= ':\\';
		}
		$filenameTarget .= $arrRunParameter['target_dir'];
		if ('' !== $filenameTarget) {
			$filenameTarget .= '\\';
		}
		if ('' == $arrRunParameter['target_filename']) {
			// ToDO: Implement multiple versions of "timestamp"
			if ('' == $arrRunParameter['target_postfix'] AND '' == $arrRunParameter['target_timestamp']) {
				// Error: target filename ini-parameter
				$strDescription = PHP_EOL . '## error target filename cannot be set ##' .  PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				$strDescription = '> PHP EXIT <' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				flushExit();
				exit;
			} else {
				$filenameTarget .= $arrRunParameter['source_filename'] . '_' . $arrRunParameter['target_postfix'];
				if ('' !== $strTargetTimestamp) {
					$filenameTarget .= '_' . $strTargetTimestamp;
				}
				$filenameTarget .= '.' . $arrRunParameter['source_ext'];
			}
		} else {
			$filenameTarget .= $arrRunParameter['target_filename'];
			if ('' !== $arrRunParameter['target_postfix']) {
				$filenameTarget .= '_' . $arrRunParameter['target_postfix'];
			}
			if ('' !== $strTargetTimestamp) {
				$filenameTarget .= '_' . $strTargetTimestamp;
			}
			$filenameTarget .= '.' . $arrRunParameter['target_ext'];
		}
		
		// Log target filename
		$strDescription = '- target filename [' . $filenameTarget . ']' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);	
	# END 14.3 - Get Target filename #
	
	# 14.4 - Get log filename #
	
		// ToDO: Implement multiple versions of "timestamp"
		//if ('yyyymmdd-hhnn' == getRunParameter('log_timestamp', false) ) {
		if ('yyyymmdd-hhnn' == $arrRunParameter['log_timestamp']) {
			$strLogTimestamp = date('Ymd-Hi', $timeStart);
		} else {
			$strLogTimestamp = "";
		}
		
		// log_drive
		if ('' == $arrRunParameter['log_drive']) {
			$filenameLog = '';
		} else {
			$filenameLog = $arrRunParameter['log_drive'] . ':\\';
		}
		
		// log_dir (default = "log")
		if ('' == $arrRunParameter['log_dir']) {
			$filenameLog .= 'log\\';
		} else {
			$filenameLog .= $arrRunParameter['log_dir'] . '\\';
		}
		
		// log_filename (default = <target_filename> or if empty <source_filename>)
		if ('' == $arrRunParameter['log_filename']) {
			if ('' !== $arrRunParameter['target_filename']) {
				$filenameLog .= $arrRunParameter['target_filename'];
			} elseif ('' !== $arrRunParameter['source_filename']) {
				$filenameLog .= $arrRunParameter['source_filename'];
			} else {
				$filenameLog .= 'log';
			}
		} else {
			$filenameLog .= $arrRunParameter['log_filename'];
		}
		
		// log_timestamp
		if ('' !== $strLogTimestamp) {
			$filenameLog .= '_' . $strLogTimestamp;
		}

		// error_postfix
		$filenameError = $filenameLog;
		if ('' == $arrRunParameter['error_postfix']) {
			$filenameError .= '_error';
		} else {
			$filenameError .= '_' . $arrRunParameter['error_postfix'];
		}
		
		// Finish log_ext
		if ('' == $arrRunParameter['log_ext']) {
			$filenameLog .= '.log';
			$filenameError .= '.log';
		} else {
			$filenameLog .= '.' . $arrRunParameter['log_ext'];
			$filenameError .= '.' . $arrRunParameter['log_ext'];
		}
		
		// Log log-filename
		$strDescription = '- log filename [' . $filenameLog . ']' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
		
		// Log error-filename
		$strDescription = '- error filename [' . $filenameError . ']' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
		
		if ($bLogFile) {
			// Open log-file
			openLogFile();
		}
	# END 14.4 - Get log filename #
	
	$bInitDone = true;
	
	// END inc-section
	if ($bLogInc) {
		$strDescription = 'END php include <<< ' . $strIncPhpScript . ' <<<' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
?>
