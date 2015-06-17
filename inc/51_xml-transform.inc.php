<?php
	
	### 51_xml-transform.inc.php ###
	
	# Sections in this inc-file: #
	# 51.1 - XML Transform definition #
	# 51.2 - XML Transform parameters #
	# 51.3 - XML Transform implementation #
	
	# global variables created in this include-section: #
	
	// Transform definition
	$filenameXmlTransformDefinition = 'README.md';
	$nLineXmlTransformDefinition = 0;
	$nCharXmlTransformDefinition = 0;
	
	$nXmlTransformError = 0;
	
	$bDebugLogFileOpen = false;
	
	# END global variables #
	
	// PHP script
	$strIncPhpScript = pathinfo(__file__)['basename'];
	
	// START inc-section
	if ($bLogInc) {
		$strDescription = PHP_EOL . 'START php include >>> ' . $strIncPhpScript . ' >>>' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	# 51.1 - XML Transform definition #
	
		if ('' == $filenameXmlTransformDefinition) {
			// Error: missing transform logic specification
			$strDescription = '## error: missing transform logic specification ##' . PHP_EOL;
			$strLine = '';
			logError(0, $strDescription, $strLine, 2);
			
			// Log til log-file only
			logLog(0, $strDescription, $strLine, 0);
		} else  {			
			if (!$fTmp = fopen($filenameXmlTransformDefinition, 'r')) {
				// Error: failed to open file
				$strDescription = PHP_EOL . '## error opening transform definition filename [';
				$strDescription .= $filenameXmlTransformDefinition . '] ##' .  PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
				
				// Log til log-file only
				logLog(0, $strDescription, $strLine, 0);
			} else {
				// Log open transform specification filename
				$strDescription = '- open transform definition filename "' . $filenameXmlTransformDefinition . '"' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, 2);
				
				// Read trough transform definition
				while ( !feof($fTmp) ) {
					$strLineFile = trim(fgets($fTmp));
					if (false === $strLine) {
						$strDescription = '-- done reading transform definition file (readline === false)' . PHP_EOL;
						$strLine = '';
						logLog(0, $strDescription, $strLine, 2);
					} else {
						// Count all lines
						$nLineXmlTransformDefinition++;
						
						// Count all characters in lines
						$nCharXmlTransformDefinition += strlen($strLineFile);
					}
				}
				$strDescription = '- transform definition file has ' . $nLineXmlTransformDefinition . ' lines and ' ;
				$strDescription .= $nCharXmlTransformDefinition . ' characters' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, 2);
				
				fclose($fTmp);
			}
		}
	# END 51.1 - XML Transform definition #
	
	# 51.2 - XML Transform parameters #
		
		## Custom values section ##
			// Section run
			$strTemp = 'custom_run';
			if ( !isset($arrRunParameter[$strTemp]) ) {
				// Error: missing parameter 'custom_run'
				$strDescription = PHP_EOL . '## error: missing parameter "' . $strTemp . '" ##' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
				
				$strDescription = '> PHP EXIT <' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
				
				flushExit();
				exit;
			}
			$strCustomRun = $arrRunParameter['custom_run'];
			
			$bArrCustom = array();
			for ($n=1; $n<=6; $n++) {
				$bArrCustom[$n] = strpos($strCustomRun, (string) $n);
			}
			
			// Log custom run
			$strDescription = '-- custom run parameter "' . $strCustomRun . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
			
			
			// Arkivstruktur add mappe count
			$strTemp = 'arkivstruktur_add_mappe_count';
			if ( !isset($arrRunParameter[$strTemp]) ) {
				// Error: missing parameter 'custom_run'
				$strDescription = PHP_EOL . '## error: missing parameter "' . $strTemp . '" ##' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
				
				$strDescription = '> PHP EXIT <' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
				
				flushExit();
				exit;
			}
			
			$nArkivstrukturAddMappeCount = $arrRunParameter[$strTemp];
			$nTemp = (integer) $nArkivstrukturAddMappeCount;
			if (!is_int($nTemp)) {	
				$nXmlTransformError++;
				
				// Error: value not numeric
				$strDescription = PHP_EOL . '## error add mappe count not numeric [' . $nArkivstrukturAddMappeCount . '] ##' .  PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				$strDescription = '> PHP EXIT <' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				flushExit();
				exit;
			}
			
			if (0 > $nTemp) {
				$nXmlTransformError++;
				
				// Error: value negative
				$strDescription = PHP_EOL . '## error add mappe count negative [' . $nArkivstrukturAddMappeCount . '] ##' .  PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				$strDescription = '> PHP EXIT <' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				flushExit();
				exit;
			}
			
			// Log arkivstruktur add mappe count
			$strDescription = '-- arkivstruktur add mappe count "' . $nArkivstrukturAddMappeCount . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
			
			// Arkivstruktur add mappe error count
			$strTemp = 'arkivstruktur_add_mappe_error_count';
			if ( !isset($arrRunParameter[$strTemp]) ) {
				// Error: missing parameter 'custom_run'
				$strDescription = PHP_EOL . '## error: missing parameter "' . $strTemp . '" ##' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
				
				$strDescription = '> PHP EXIT <' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
				
				flushExit();
				exit;
			}
			
			$nArkivstrukturAddMappeErrorCount = $arrRunParameter[$strTemp];
			$nTemp = (integer) $nArkivstrukturAddMappeErrorCount;
			if (!is_int($nTemp)) {	
				$nXmlTransformError++;
				
				// Error: value not numeric
				$strDescription = PHP_EOL . '## error add mappe error count not numeric [' . $nArkivstrukturAddMappeErrorCount . '] ##' .  PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				$strDescription = '> PHP EXIT <' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				flushExit();
				exit;
			}
			
			if (0 > $nTemp) {
				$nXmlTransformError++;
				
				// Error: value negative
				$strDescription = PHP_EOL . '## error add mappe error count negative [' . $nArkivstrukturAddMappeErrorCount . '] ##' .  PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				$strDescription = '> PHP EXIT <' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
											  
				flushExit();
				exit;
			}
			
			// Log arkivstruktur add mappe error count
			$strDescription = '-- arkivstruktur add mappe error count "' . $nArkivstrukturAddMappeErrorCount . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		## END Custom values section ##
		
	# END 51.2 - XML Transform parameters #
	
	# 51.3 - XML Transform implementation #
		
		// ToDo: move parameter section elsewhere!
		
		$strTemp = 'xml_single_file';
		if ( !isset($arrRunParameter[$strTemp]) ) {
			// Error: missing parameter
			$strDescription = PHP_EOL . '## error: missing parameter "' . $strTemp . '" ##' . PHP_EOL;
			$strLine = '';
			logError(0, $strDescription, $strLine, 2);
			
			$strDescription = '> PHP EXIT <' . PHP_EOL;
			$strLine = '';
			logError(0, $strDescription, $strLine, 2);
			
			flushExit();
			exit;
		}
		
		if ('1' == $arrRunParameter[$strTemp]) {
			$bXmlSingleFile = true;
		} else {
			$bXmlSingleFile = false;
		}
		
		if (!$bXmlSingleFile) {
			// Error: directory mode not implemented yet
			$strDescription = PHP_EOL . '## error: directory mode not implemented yet ##' . PHP_EOL;
			$strLine = '';
			logError(0, $strDescription, $strLine, 2);
			
			$strDescription = '> PHP EXIT <' . PHP_EOL;
			$strLine = '';
			logError(0, $strDescription, $strLine, 2);
			
			flushExit();
			exit;
		} else {
			// Log single file mode
			$strDescription = '-- single file mode detected' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		}
		
		## Dataset dir
			$strDatasetDir = $arrRunParameter['dataset_dir'];
			
			// Log dataset dir
			$strDescription = '-- dataset dir "' . $strDatasetDir . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		
		## XML Reader in filename
			$filenameXmlReaderIn = $arrRunParameter['xml_single_filename'];
			
			// XML Reader out filename
			$filenameXmlReaderOut = $filenameXmlReaderIn;
			
			// Debug log filename
			$filenameDebugLog = $filenameXmlReaderIn;
			
			if ('' !== $arrRunParameter['xml_single_filename_ext']) {
				$filenameXmlReaderIn .= '.' . $arrRunParameter['xml_single_filename_ext'];
			}
			
			// Log XML Reader in filename
			$strDescription = '-- XML Reader in filename "' . $filenameXmlReaderIn . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		
		## XML Reader in filepath
			if ('' == $strDatasetDir) {
				$filepathXmlReaderIn = $filenameXmlReaderIn;
			} else {	
				 $filepathXmlReaderIn = $strDatasetDir . '/' . $filenameXmlReaderIn;
			}
			
			// Log XML Reader in filepath
			$strDescription = '-- XML Reader in filepath "' . $filepathXmlReaderIn . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		
		## XML validator filename
			$filenameXmlValidate = $arrRunParameter['xml_single_filename_validate'];
			
			if ('' !== $arrRunParameter['xml_single_filename_validate_ext']) {
				$filenameXmlValidate .= '.' . $arrRunParameter['xml_single_filename_validate_ext'];
			}
			
			// Log XML validator filename
			$strDescription = '-- XML validator filename "' . $filenameXmlValidate . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		
		## XML validator filepath
			if ('' == $strDatasetDir) {
				$filepathXmlValidate = $filenameXmlValidate;
			} else {	
				 $filepathXmlValidate = $strDatasetDir . '/' . $filenameXmlValidate;
			}
			
			// Log XML validate filepath
			$strDescription = '-- XML Validate filepath "' . $filepathXmlValidate . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		
		## XML Reader out filename		
			if ('' !== $arrRunParameter['custom_postfix']) {
				$filenameXmlReaderOut .= '_' . $arrRunParameter['custom_postfix'];
			}
			// ToDo: Implement timestamp for custom_timestamp
			if ('' !== $strTargetTimestamp) {
				$filenameXmlReaderOut .= '_' . $strTargetTimestamp;
			}
			if ('' !== $arrRunParameter['xml_single_filename_ext']) {
				$filenameXmlReaderOut .= '.' . $arrRunParameter['xml_single_filename_ext'];
			}
			
			// Log filename XML Reader out
			$strDescription = '-- XML Reader filename out "' . $filenameXmlReaderOut . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
			
		## XML Reader out filepath
			if ('' == $strDatasetDir) {
				$filepathXmlReaderOut = $filenameXmlReaderout;
			} else {	
				 $filepathXmlReaderOut = $strDatasetDir . '/' . $filenameXmlReaderOut;
			}
			
			// Log XML Reader out filepath
			$strDescription = '-- XML Reader out filepath "' . $filepathXmlReaderOut . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		
		## Debug log filename
			// ToDo: replace hardcoding
			$filenameDebugLog .= '_debug';
			
			// ToDo: Implement timestamp for custom_timestamp
			if ('' !== $strTargetTimestamp) {
				$filenameDebugLog .= '_' . $strTargetTimestamp;
			}
			$filenameDebugLog .= '.log';
			
		## Debug log filepath
			// log_dir (default = "log")
			if ('' == $arrRunParameter['log_dir']) {
				$filepathDebugLog = 'log/' . $filenameDebugLog;
			} else {
				$filepathDebugLog = $arrRunParameter['log_dir'] . '/' . $filenameDebugLog;
			}
			
		// Log debug log filepath
			$strDescription = '-- debug log filepath "' . $filepathDebugLog . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);

		### Test well-formed
		
		if ($bArrCustom[1]) {
		
			$thisTestNameWellFormed = 'Well formed test';
			$thisDirectory = $strDatasetDir;
			$thisFilename = $filenameXmlReaderIn; 
			$thisXMLWellFormedTestProperty = new XMLWellFormedTestProperty(Constants::TEST_XMLTEST_VALIDATION_WELLFORMED);
			
			$strTemp = implode ('/', array($thisDirectory, $thisFilename));
			print $strTemp . PHP_EOL;
			
			$thisXMLTestWellFormed = new XMLTestWellFormed($thisTestNameWellFormed, $thisDirectory, $thisFilename, $thisXMLWellFormedTestProperty);
			$thisXMLTestWellFormed->runTest();
			
			// Log XML Reader in description report
			$strDescription = $thisXMLWellFormedTestProperty->getDescriptionReport() . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
				
			// Log XML Reader in description
			$strDescription = $thisXMLWellFormedTestProperty->getDescription() . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
			
			## well-formed errors? ##
				$nXmlWellFormedError = count(libxml_get_errors());
				
				if (0 < $nXmlWellFormedError ) {			
					// Error: XML not well-formed
					$strDescription = PHP_EOL . '## error: xml file is NOT well-formed, error count = ' . $nXmlWellFormedError . ' ##' . PHP_EOL;
					$strLine = $thisDirectory . '/' . $thisFilename;
					logError(0, $strDescription, $strLine, 2);
					
					// Open error logfile for this xml-file
					
					if (!$bDebugLogFileOpen) {
						// Open Log-file for writing
						if (!$fDebugLog = fopen($filepathDebugLog, 'a')) {
							// Error: failed to open file
							$strDescription = PHP_EOL . '## error opening debug log filename [' . $filepathDebugLog . '] ##' .  PHP_EOL;
							$strLine = '';
							logError(0, $strDescription, $strLine, 2);
						} else {
							$bDebugLogFileOpen = true;
							
							// Log open log-filename					
							$strDescription = '- open debug log filename [' . $filepathDebugLog . ']' . PHP_EOL;
							$strLine = '';
							logLog(0, $strDescription, $strLine, 2);
						}
					}	// Log-file open
					
					// write xml well-formed error to debug log
					if ($bDebugLogFileOpen) {
						$errors = libxml_get_errors();
						foreach ($errors as $error) {
							fwrite ($fDebugLog, display_xml_error($error));
						}
						
						libxml_clear_errors();
					}
				}	// END if (0 < $nXmlWellFormedError )
			## END well-formed errors? ##
			
		}	// END IF (true)
		
		### Test validation
		
		if ($bArrCustom[2]) {
		
			$thisTestNameValidation = 'Validation test';
			$thisDirectory = $strDatasetDir;
			$thisFilename = $filenameXmlReaderIn;
			$thisFilenameValidate = $filenameXmlValidate;
			
			$thisXMLValidationTestProperty = new XMLValidationTestProperty(Constants::TEST_XMLTEST_VALIDATION_VALID);
			
			print ' ##############' . PHP_EOL . PHP_EOL;
			$strTemp = implode ('/', array($thisDirectory, $thisFilename));
			print $strTemp . PHP_EOL;
			
			$thisXMLTestValidation = new XMLTestValidation($thisTestNameValidation, $thisDirectory, $thisFilename, $thisFilenameValidate, $thisXMLValidationTestProperty);
			$thisXMLTestValidation->runTest();
			
			// Log XML Reader in description report
			$strDescription = $thisXMLValidationTestProperty->getDescriptionReport() . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
				
			// Log XML Reader in description
			$strDescription = $thisXMLValidationTestProperty->getDescription() . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
			
			## validate errors? ##
				$nXmlValidateError = count(libxml_get_errors());
				
				if (0 < $nXmlValidateError ) {			
					// Error: XML not valid
					$strDescription = PHP_EOL . '## error: xml file is NOT valid, error count = ' . $nXmlValidateError . ' ##' . PHP_EOL;
					$strLine = $thisDirectory . '/' . $thisFilename;
					logError(0, $strDescription, $strLine, 2);
					
					// Open error logfile for this xml-file
					
					if (!$bDebugLogFileOpen) {
						// Open Log-file for writing
						if (!$fDebugLog = fopen($filepathDebugLog, 'a')) {
							// Error: failed to open file
							$strDescription = PHP_EOL . '## error opening debug log filename [' . $filepathDebugLog . '] ##' .  PHP_EOL;
							$strLine = '';
							logError(0, $strDescription, $strLine, 2);
						} else {
							$bDebugLogFileOpen = true;
							
							// Log open log-filename					
							$strDescription = '- open debug log filename [' . $filepathDebugLog . ']' . PHP_EOL;
							$strLine = '';
							logLog(0, $strDescription, $strLine, 2);
						}
					}	// Log-file open
					
					// write xml validate error to debug log
					if ($bDebugLogFileOpen) {
						$errors = libxml_get_errors();
						foreach ($errors as $error) {
							fwrite ($fDebugLog, display_xml_error($error));
						}

						libxml_clear_errors();
					}
				}	// END if (0 < $nXmlValidateError )
			## END validate errors? ##
			
			## Close section well-formed & validate ##
				if ($bDebugLogFileOpen) {
					fclose($fDebugLog);
					$bDebugLogFileOpen = false;
				}
			## Close section well-formed & validate ##
			
		}	// END IF (true)
		
		
		
		
		### XML Reader section ###
		
		// var_dump(libxml_use_internal_errors(true));
		
		
		if (false) {
		
			$xmlReaderIn = new XMLReader();
			$xmlReaderIn2 = new XMLReader();
			
			if (!$xmlReaderIn->open($filenameXmlReaderIn, 'UTF-8')) {
				// Error: opening XML Reader in
				throw new RuntimeException('Unable to open file.');
				
				// Error: opening XML Reader in
				$strDescription = PHP_EOL . '## error: failed open XML Reader in ##' . PHP_EOL;
				$strLine = $filenameXmlReaderIn . PHP_EOL;
				logError(0, $strDescription, $strLine, 2);
				
				$strDescription = '> PHP EXIT <' . PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
				
				flushExit();
				exit;
			}
			$xmlReaderIn2 = $xmlReaderIn;
			
			
			// Log XMl Reader in opened
			$strDescription = '- XML Reader in opened "' . $filenameXmlReaderIn . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
			
			#1 Iterate through XML Reader in with no exception handler
			
			$nIterateCount = 0;
			While ($xmlReaderIn2->read()) {
				$nIterateCount++;
				foreach (libxml_get_errors() as $error) {
					print $error . PHP_EOL;
				}
			}
			
			$nXmlReaderCount2 = $nIterateCount;
			
			$strDescription = '-- XML Reader in count read = "' . $nXmlReaderCount2 . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
			
			#2 check if well-formed by simply parsing through witout errors detected
			
			// Iterate
			$nIterateCount = 0;
			$nXmlReaderInErrorWellFormed = 0;
			try {
				While ($xmlReaderIn->read()) {
					// Count XML Read in
					print $nIterateCount++ . ', ';
					
					if (isset($php_errormsg) ) {
						$nXmlReaderInErrorWellFormed++;
						fault (21, $php_errormsg);
					}
				};				
			} catch (Exception $e) {
				$nXmlReaderInErrorWellFormed++;
				
				// Error: well-formed
				$strDescription = PHP_EOL . '## error: well-formed exception read no ' . $nIterateCount . ' ##' . PHP_EOL;
				$strLine = $e . PHP_EOL;
				logError(0, $strDescription, $strLine, 2);
			}
			print PHP_EOL;
				
			
			$nXmlReaderCount = $nIterateCount;
			
			$strDescription = '-- XML Reader in count read = "' . $nXmlReaderCount . '"' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
			
			if (0 == $nXmlReaderInErrorWellFormed) {
				// Log no XML Reader in well-formed errors
				$strDescription = '-- XML Reader in is well-formed' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, 2);
			} else {
				// Log XML Reader in well-formed errors	
				$strDescription = '-- XML Reader in is not well-formed, errors = "' . $nXmlReaderInErrorWellFormed . '"' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, 2);
			}		
		
		}	// DEGUG end if (false)
		
		### END XML Reader section ###
				
		if (0 == $nXmlTransformError) {
			// Log no errors
			$strDescription = '- no transform errors' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		} else {
			// Error: transform errors detected
			$strDescription = PHP_EOL . '## transform errors detected: ' . $nXmlTransformError . ' ##' .  PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		}
	# END 51.3 - XML Transform implementation #
	
	// END inc-section
	if ($bLogInc) {
		$strDescription = 'END php include <<< ' . $strIncPhpScript . ' <<<' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
?>
