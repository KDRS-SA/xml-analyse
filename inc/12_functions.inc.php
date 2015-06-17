<?php

	### 12_functions.inc.php ###
	
	# Sections in this inc-file: #
	# 12.1 getRunParameter #
	# 12.2 logLog #
	# 12.3 openLogFile #
	# 12.4 writeLog #
	# 12.5 flushLog #
	# 12.6 logError #
	# 12.7 openErrorFile #
	# 12.8 writeError #
	# 12.9 flushError #
	
	// ToDo: Write compact function with print_r functionality with only minimum output lines
	// 	function "print_array($arrInput)" as multi-line string
	
	## Global variables created in this include-section: ##	
	## END global variables ##
	
	// General parameters
	
	// PHP script
	$strIncPhpScript = pathinfo(__file__)['basename'];
	
	// START inc-section
	if ($bLogInc) {
		$strDescription = PHP_EOL . 'START php include >>> ' . $strIncPhpScript . ' >>>' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	# 12.1 getRunParameter #
		// get value from array $arrRunParameter
		// $bExit = true; return = NULL if key not exists
		// $bExit = false; return = '' if key not exists
		function getRunParameter($strKey, $bExit) {
			// Declare global variables
			global $arrRunParameter;
			
			if ( array_key_exists($strKey, $arrRunParameter) ) {
				return $arrRunParameter[$strKey];
			} else {
				if (!$bExit) {
					// return empty string (if not NULL is returnet by default)
					return '';
				}
			}
		}	// end function
	# END 12.1 getRunParameter #
	
	# 12.2 logLog #
		function logLog($nLine, $strDescription, $strLine, $nMode) {
			// Log log
			
			// Declare global variables
			global $arrLog, $nArrLog, $nLog, $strCSV, $strCSVEntity, $nFlushLogFile, $bInitDone;
			
			// Output:
			// $nMode =0 - file only; =1 console only; =2 both console and file
			
			// Console
			if (0 !== $nMode) {
				print $strDescription;
				if ('' !== $strLine) {
					print $strLine;
				}
			}
			
			// File
			if (1 !== $nMode) {
				// $nLine; Log line nr (=0 if not inside data rows)
				$arrLog[$nArrLog][0] = $nLine;
				// $strDescription; Log description
				$arrLog[$nArrLog][1] = str_replace ($strCSV, $strCSVEntity, $strDescription);
				// $strLine; Complete line with data to log
				$arrLog[$nArrLog][2] = str_replace ($strCSV, $strCSVEntity, $strLine);
				// Counters
				$nArrLog++;
				$nLog++;
				
				if ((0 == $nFlushLogFile) AND $bInitDone) {
					// flush to file immediately
					flushLog();
				} else {
					if ((0 == $nArrLog % $nFlushLogFile) AND $bInitDone) {
						// flush to file if counter Modulus limit = 0
						flushLog();
					}
				}
			}
		}	// end function
	# END 12.2 logLog #
	
	# 12.3 openLogFile #
		function openLogFile() {
			// Open log-file for append if not already opened
			
			// Declare global variables
			global $bLogFileOpen, $fLog, $filenameLog;
			
			if (!$bLogFileOpen) {
				// Open Log-file for writing
				if (!$fLog = fopen($filenameLog, 'a')) {
					// Error: failed to open file
					$strDescription = PHP_EOL . '## error opening log filename [' . $filenameLog . '] ##' .  PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, 2);
												  
					$strDescription = '> PHP EXIT <' . PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, 2);
												  
					flushExit();
					exit;
				} else {
					$bLogFileOpen = true;
					
					// Log open log-filename					
					$strDescription = '- open log filename [' . $filenameLog . ']' . PHP_EOL;
					$strLine = '';
					logLog(0, $strDescription, $strLine, 2);
				}
			}	// Log-file open
			
		}	// end function
	# END 12.3 openLogFile #
	
	# 12.4 writeLog #
		function writeLog($nLine, $strDescription, $strLine) {
			// Declare global variables
			global $bInitDone, $fLog, $strCSV, $strCSVEntity;
			
			if (!$bInitDone) {
				// If init-section not finished temporary write to array
				logLog($nLine, $strDescription, $strLine);
			} else {
				// Open log-file for append if not already opened
				openLogFile();
				
				// Write log
				$strDescription = str_replace ($strCSV, $strCSVEntity, $strDescription);
				$strLine = str_replace ($strCSV, $strCSVEntity, $strLine);
				$strTemp = $nLine . $strCSV . $strDescription . $strCSV . $strLine . PHP_EOL;
				fwrite ($fLog, $strTemp);
			}
		}	// end function
	# END 12.4 writeLog #
	
	# 12.5 flushLog #
		function flushLog() {
			// Declare global variables
			global $bInitDone, $arrLog, $nArrLog, $fLog, $strCSV;
			
			if ($bInitDone AND 0 < $nArrLog) {
				// Open log-file for append if not already opened
				openLogFile();
				
				// Write log
				foreach ($arrLog as $arrLine) {
					$arrLine[1] = trim($arrLine[1]);
					$arrLine[2] = trim($arrLine[2]);
					$strLine = implode($strCSV, $arrLine) .  PHP_EOL;
					fwrite ($fLog, $strLine);
				}
				$arrLog = array();
				$nArrLog = 0;
			}
		}	// end function
	# END12.5 flushLog #
	
	# 12.6 logError #
		function logError($nLine, $strDescription, $strLine, $nMode) {
			// Log error
			
			// Declare global variables
			global $arrError, $nArrError, $nError, $strCSV, $strCSVEntity, $nFlushErrorFile, $bInitDone;
			
			// Error count
			$nError++;
			
			// Output:
			// $nMode =0 - file only; =1 console only; =2 both console and file
			
			// Console
			if (0 !== $nMode) {
				print $strDescription;
				if ('' !== $strLine) {
					print $strLine;
				}
			}
			
			// File
			if (1 !== $nMode) {				
				// $nLine; Error line nr (=0 if not inside data rows)
				$arrError[$nArrError][0] = $nLine;
				// $strDescription; Log description
				$arrError[$nArrError][1] = str_replace ($strCSV, $strCSVEntity, $strDescription);
				// $strLine; Complete line with data to report error
				$arrError[$nArrError][2] = str_replace ($strCSV, $strCSVEntity, $strLine);
				// Counters
				$nArrError++;
				
				if ((0 == $nFlushErrorFile) AND $bInitDone) {
					// flush to file immediately
					flushError();
				} else {
					if ((0 == $nArrError % $nFlushErrorFile) AND $bInitDone) {
						// flush to file if counter Modulus limit = 0
						flushError();
					}
				}
			}
		}	// end function
	# END 12.6 logError #
	
	# 12.7 openErrorFile #
		function openErrorFile() {
			// Open error-file for append if not already opened
			
			// Declare global variables
			global $bErrorFileOpen, $fError, $filenameError;
			
			if (!$bErrorFileOpen) {
				// Open Error-file for writing
				if (!$fError = fopen($filenameError, 'a')) {
					// Error: failed to open file
					$strDescription = PHP_EOL . '## error opening error filename [' . $filenameError . '] ##' .  PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, 2);
												  
					$strDescription = '> PHP EXIT <' . PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, 2);
												  
					flushExit();
					exit;
				} else {
					$bErrorFileOpen = true;
					
					// Log open error-filename					
					$strDescription = '- open error filename [' . $filenameError . ']' . PHP_EOL;
					$strLine = '';
					logLog(0, $strDescription, $strLine, 2);
				}
			}	// Error-file open
		}	// end function
	# END 12.2 openLogFile #
	
	# 12.8 writeError #
		function writeError($nLine, $strDescription, $strLine) {
			// Declare global variables
			global $bInitDone, $fError, $strCSV, $strCSVEntity;
			
			if (!$bInitDone) {
				// If init-section not finished temporary write to array
				logError($nLine, $strDescription, $strLine);
			} else {
				// Open error-file for append if not already opened
				openErrorFile();
				
				// Write error
				$strDescription = str_replace ($strCSV, $strCSVEntity, $strDescription);
				$strLine = str_replace ($strCSV, $strCSVEntity, $strLine);
				$strTemp = $nLine . $strCSV . $strDescription . $strCSV . $strLine . PHP_EOL;
				fwrite ($fTarget, $strTemp);
			}
		}	// end function
	# END 12.8 writeError #
	
	# 12.9 flushError #
		function flushError() {
			// Declare global variables
			global $bInitDone, $arrError, $nArrError, $fError, $strCSV;
			
			if ($bInitDone AND 0 < $nArrError) {
				// Open error-file for append if not already opened
				openErrorFile();
				
				// Write error
				foreach ($arrError as $arrLine) {
					$arrLine[1] = trim($arrLine[1]);
					$arrLine[2] = trim($arrLine[2]);
					$strLine = implode($strCSV, $arrLine) .  PHP_EOL;
					fwrite ($fError, $strLine);
				}
				$arrError = array();
				$nArrError = 0;
			}
		}	// end function
	# END 12.9 flushError #
	
	# 12.10 flushExit #
		function flushExit() {
			global $nArrError, $strStartDateTime, $bLogFileOpen, $fLog, $bErrorFileOpen, $fError;
			
			// PHP slutt through flushExit
			$strDescription = PHP_EOL . 'PHP start [' . $strStartDateTime . ']' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);

			// Error count
			if (0 < $nArrError) {
				$strDescription = '- error count = ' . $nArrError . PHP_EOL;
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
		}
	# END12.10 flushExit #
	
	function display_xml_error($error) {
		$return = str_repeat('-', $error->column) . PHP_EOL;
		
		switch ($error->level) {
			case LIBXML_ERR_WARNING:
				$return .= 'Warning ' . $error->code . ': ';
				break;
			case LIBXML_ERR_ERROR:
				$return .= 'Error ' . $error->code . ': ';
				break;
			case LIBXML_ERR_FATAL:
				$return .= 'Fatal error ' . $error->code . ': ';
				break;
		}
		
		$return .= trim($error->message) . PHP_EOL;
		$return .= ' Line: ' . $error->line . PHP_EOL;
		$return .= ' Column: ' . $error->column;
		
		if ($error->file) {
			$return .= PHP_EOL . ' File: ' . $error->file;
		}
		
		$strTemp = '------------------------------------';
		return $return . PHP_EOL . PHP_EOL . $strTemp . PHP_EOL . PHP_EOL;
	}
	
	// END inc-section
	if ($bLogInc) {
		$strDescription = 'END php include <<< ' . $strIncPhpScript . ' <<<' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
?>
