<?php
	
	### 31_parse-transform.inc.php ###
	
	# Sections in this inc-file: #
	# 31.0 - Debug transparent set Target = Source | masked as text #
	# 31.1 - Transform definition #
	# 31.2 - Transform parameters #
	# 31.3 - Transform implementation #
	
	# global variables created in this include-section: #
	
	// Transform definition
	$filenameTransformDefinition = 'README.md';
	$nLineTransformDefinition = 0;
	$nCharTransformDefinition = 0;
	
	// Transform array = temporary array keeping track of each new line
	// Hence additional unique row-counter leftmost in array!
	
	// $arrTransform[$nLineTransform][$nColumnTransform] = value
	// $nColumnTransform = [1..14] for standard form IKAMR-K06
	$arrTransform = array();
	$nLineTransform = 0;		// = [0..n]
	
	$nTransformError = 0;
	
	// General counters for logging Stykke and Mappe
	$nStykke = 0;
	$nMappe = 0;
	
	// $arrTarget = output lines of data to be written after migrate source to target processing
	$arrTarget = array ();	
	$nLineTarget = 0;
	# END global variables #
	
	// PHP script
	$strIncPhpScript = pathinfo(__file__)['basename'];
	
	// START inc-section
	if ($bLogInc) {
		$strDescription = PHP_EOL . 'START php include >>> ' . $strIncPhpScript . ' >>>' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	# 31.0 - Debug transparent set Target = Source | masked as text #
	
	// Debug: transparent datalines from source to target
	// $arrTarget = $arrSource;
	
	# END 31.0 - Debug transparent set Target = Source | masked as text #
	
	# 31.1 - Transform definition #
	
		if ('' == $filenameTransformDefinition) {
			// Error: missing transform logic specification
			$strDescription = '## error: missing transform logic specification ##' . PHP_EOL;
			$strLine = '';
			logError(0, $strDescription, $strLine, 2);
			
			// Log til log-file only
			logLog(0, $strDescription, $strLine, 0);
		} else  {			
			if (!$fTmp = fopen($filenameTransformDefinition, 'r')) {
				// Error: failed to open file
				$strDescription = PHP_EOL . '## error opening transform definition filename [';
				$strDescription .= $filenameTransformDefinition . '] ##' .  PHP_EOL;
				$strLine = '';
				logError(0, $strDescription, $strLine, 2);
				
				// Log til log-file only
				logLog(0, $strDescription, $strLine, 0);
			} else {
				// Log open transform specification filename
				$strDescription = '- open transform definition filename "' . $filenameTransformDefinition . '"' . PHP_EOL;
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
						$nLineTransformDefinition++;
						
						// Count all characters in lines
						$nCharTransformDefinition += strlen($strLineFile);
					}
				}
				$strDescription = '- transform definition file has ' . $nLineTransformDefinition . ' lines and ' ;
				$strDescription .= $nCharTransformDefinition . ' characters' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, 2);
				
				fclose($fTmp);
			}
		}
	# END 31.1 - Transform definition #
	
	# 31.2 - Transform parameters #
		
		## Custom values section ##
			// Section run
			$strSectionRun = $arrRunParameter['section_run'];
			
			// Arkivstruktur add mappe count
			$nArkivstrukturAddMappeCount = $arrRunParameter['arkivstruktur_add_mappe_count'];
			$nTemp = (integer) $nArkivstrukturAddMappeCount;
			if (!is_int($nTemp)) {	
				$nTransformError++;
				
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
				$nTransformError++;
				
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
			
			// Arkivstruktur add mappe error count
			$nArkivstrukturAddMappeErrorCount = $arrRunParameter['arkivstruktur_add_mappe_error_count'];
			$nTemp = (integer) $nArkivstrukturAddMappeErrorCount;
			if (!is_int($nTemp)) {	
				$nTransformError++;
				
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
				$nTransformError++;
				
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
		## END Custom values section ##
		
	# END 31.2 - Transform parameters #
	
	# 31.3 - Transform implementation #
		
		// empty row dummyarray
		for ($i=1; $i <= $nOutputColumns; $i++) {
			$arrDummy[$i] = '';
		}
		
		// Transform main loop
		// Stykke/Mappe-elements
		$nThisStykkenr = 0;
		$nThisStykkenrOffset = $nDefaultStykkeStartnr - 1;
		$nThisMappenr = 0;
		$nLine = 0;
		$bFirstStykkenr = true;
		
		foreach ($arrSource as $arrLine) {
			$nLine++;
						
			// Check for a minimum of fields pr. line
			if ($nInputColumnsMin > count($arrLine)) {
				// Log error (file only) and skip to next element in foreach
				$strDescription = count($arrLine) . 'TRANSFORM: data columns < ' . $nInputColumnsMin . ' elements in dataline' . PHP_EOL;
				$strLine = implode($strCSV, $arrLine);
				logError($nLine, $strDescription, $strLine, 0);
				
				$nTransformError++;
				
				// Skip to next line
				continue;
			}
			
			// Log error flag
			$bTmp = false;
			
			//ToDo: automatically check for mandatory, optional and empty elements in columns < $nInputColumnsMax
			
			// HARDCODED check
				if (false) {
					$bTmp = true;
				}

				// Check log error flag
				if ($bTmp) {
					// Log error (file only), but do NOT skip tonext element in foreach
					$strDescription = 'TRANSFORM: HARDCODED error in dataline' . PHP_EOL;
					$strLine = implode($strCSV, $arrLine);
					logError($nLine, $strDescription, $strLine, 0);
					
					$nTransformError++;
				}
			// END HARDCODED
			
			
			// Check for a larger number of fields pr. line
			if ($nInputColumnsMax < count($arrLine)) {
				// Log error (file only and if rows not empty)
				
				// Log error flag
				$bTmp = false;
				for ($i=$nInputColumnsMax;  $i <= (count($arrLine)-1); $i++) {
					if ('' !== $arrLine[$i]) {
						// Content encountered in column [3..n]
						$bTmp = true;
					}
				}			
			
				// Check log error flag
				if ($bTmp) {
					// Log error (file only), but do NOT skip tonext element in foreach
					$strDescription = 'TRANSFORM: ' . count($arrLine) . ' data columns > ' . $nInputColumnsMax . ' elements in dataline' . PHP_EOL;
					$strLine = implode($strCSV, $arrLine);
					logError($nLine, $strDescription, $strLine, 0);
					
					$nTransformError++;
				}
			}
			
			## Analyze field-data this line ##
			
			// set this row to empty dummy array
			$arrTransform[$nLineTransform] = $arrDummy;
			
			# In 1 : Stykkenr #
			if ('' == $arrLine[0]) {
				// Unset new Stykkenr flag
				$bNewStykkenr = false;
			} else {
				// Set new Stykkenr flag
				$bNewStykkenr = true;
				
				// Increment Stykkenr
				$nThisStykkenr++;
				$nThisStykkenrOffset++;
				
				$strThisStykkenr = trim($arrLine[0]);
	
				$nTemp = (integer) $strThisStykkenr;		
				if ( !is_int( $nTemp ) ) {
					// Log error (file only), but do NOT skip tonext element in foreach
					
					// Stykkenr not an integer
					$strDescription = '## error: Stykkenr not an integer: "' . $arrLine[0] . '"' . PHP_EOL;
					$strLine = '';
					logError($nLine, $strDescription, $strLine, 0);
					
					$nTransformError++;
					
					// ToDo: advanced handling and error-check Stykkenr?
					$nThisStykkenr = 0;
					
				} elseif ((integer) $arrLine[0] !== $nThisStykkenr ) {
					// Log error (file only), but do NOT skip tonext element in foreach
					
					// Stykkenr counter unequal to Source array value
					$strDescription = '## error: Stykkenr counter ' . $nThisStykkenr;
					$strDescription .= ' <> ' . $arrLine[0] . PHP_EOL;
					$strLine = '';
					logError($nLine, $strDescription, $strLine, 0);
					
					$nTransformError++;
				}
			}
			
			# In 2 : Fra år #
			$strThisStykkeFraaar = trim($arrLine[1]);
			
			# In 3 : Til år #
			$strThisStykkeTilaar = trim($arrLine[2]);
			
			# In 4 : Frakode #
			$strThisStykkeFrakode = trim($arrLine[3]);
			
			# In 5 : Frakode #
			$strThisStykkeTilkode = trim($arrLine[4]);
			
			# In 6 : Mappenr #
			if ('' == $arrLine[5]) {
				// Log error (file only), but do NOT skip tonext element in foreach
				
				// Mappenr empty
				$strDescription = '## error: Mappenr empty: "' . $arrLine[5] . '"' . PHP_EOL;
				$strLine = '';
				logError($nLine, $strDescription, $strLine, 0);
				
				$nTransformError++;
				
				$strThisMappenr = '0';
			} else {
				// Set this Mappenr
				$strThisMappenr = trim($arrLine[5]);
				$nTemp = (integer) $strThisMappenr;
			
				if ( !is_int( $nTemp ) ) {
					// Log error (file only), but do NOT skip tonext element in foreach
					
					// Mappenr not an integer
					$strDescription = '## error: Mappenr not an integer: "' . $arrLine[5] . '"' . PHP_EOL;
					$strLine = '';
					logError($nLine, $strDescription, $strLine, 0);
					
					$nTransformError++;
					
					// ToDo: advanced handling and error-check Stykkenr?
				}
			}	// END if '' == this Mappenr
			
			# In 7 : Mappenamn #
			$strThisMappenamn = trim($arrLine[6]);
			
			
			## Analyze existing field(s) with multiple fields of information ##
			
			if ($bNewStykkenr) {
				// First element of a new Stykkenr
				
				// Set Mappenr = 1
				$nThisMappenr = 1;
							
				if ($bFirstStykkenr) {
					// Skip first Stykke for post-calculated columns depending on Mappe-info
					$bFirstStykkenr = false;
				} else {
					# Calculate final Stykke-data from previous Stykke & Mappe (if any) #
						// Set Column 2 = Stykkenamn, single frakode or frakode-tilkode
						if ($strPrevStykkeFrakode == $strPrevStykkeTilkode) {
							// Single Mappe frakode element
							# Out 1.2: Set elements for previous Stykke #
							
							// Set Column 2 = Stykkenamn
							$arrTransform[$nPrevLineTransform][2] = $strPrevStykkeFrakode;
						} else {
							// Multiple Mappe frakode elements
							
							# Out 1.2: Set elements for previous Stykke #
							
							// Set Column 2 = Stykkenamn
							$arrTransform[$nPrevLineTransform][2] = $strPrevStykkeFrakode . '-' . $strPrevStykkeTilkode;
						}
						
						// Set Column 6 = Stykke tilkode
						$arrTransform[$nPrevLineTransform][6] = $strPrevStykkeTilkode;
					# END Calculate final Stykke-data from previous Stykke & Mappe (if any) #
				}

				# Set available data for new Stykke #
					# Out 1.1: Set elements for new Stykke only #
					
					// Set Column 1 = Stykkenr
					$arrTransform[$nLineTransform][1] = $nThisStykkenrOffset;
					
					// Set Column 3 = Stykke startdato
					$arrTransform[$nLineTransform][3] = $strTargetStykkeStartdato;
				
					// Set Column 4 = Stykke sluttdato
					$arrTransform[$nLineTransform][4] = $strTargetStykkeSluttdato;
					
					// Set Column 5 = Stykke frakode
					$arrTransform[$nLineTransform][5] = $strThisMappeFrakode;
					
					// Preserve Stykke Frakode from this (first) Mappe Frakode
					$strPrevStykkeFrakode = $strThisMappeFrakode;
					
					// Preserve previous Stykkenr transform line
					$nPrevLineTransform = $nLineTransform;
				# END Set available data for new Stykke #
				
			} else {
				// Second or following Mappenr
				
				// Mappenr increment +1
				$nThisMappenr++;
			}	// END if ($bNewStykkenr)
			
			# Set available data for every Mappe #
				# Out 2: Set elements for Mappe (every row) #
				
				// Set Column 8 = Mappenr
				$arrTransform[$nLineTransform][8] = $nThisMappenr;
				
				// Set Column 9 = Mappenamn
				$arrTransform[$nLineTransform][9] = $strThisMappenamn;
				
				// Set Column 12 = Mappe frakode
				$arrTransform[$nLineTransform][12] = $strThisMappeFrakode;
				
				// Update counters
				$nLineTransform++;
				
				# Preserve Prev-variables #
				
				// Preserve previous Stykkenr		
				$nPrevStykkenr = $nThisStykkenr;
				
				// Preserve Stykke Tilkode from this (latest) Mappe Frakode
				$strPrevStykkeTilkode = $strThisMappeFrakode;
			# END Set available data for every Mappe #
			
		}	// END foreach reading $arrSource
		
		// Transform post-handling loop
		// Final Stykke/Mappe elements
		
		# Calculate final Stykke-data from previous Stykke (if any) #
		if (0 < $nLineTransform) {
			// There must be at least 1 line of data
						
			# Calculate final Stykke-data from previous Stykke & Mappe (if any) #
				// Set Column 2 = Stykkenamn, single frakode or frakode-tilkode
				if ($strPrevStykkeFrakode == $strPrevStykkeTilkode) {
					// Single Mappe frakode element
					# Out 1.2: Set elements for previous Stykke #
					
					// Set Column 2 = Stykkenamn
					$arrTransform[$nPrevLineTransform][2] = $strPrevStykkeFrakode;
				} else {
					// Multiple Mappe frakode elements
					
					# Out 1.2: Set elements for previous Stykke #
					
					// Set Column 2 = Stykkenamn
					$arrTransform[$nPrevLineTransform][2] = $strPrevStykkeFrakode . '-' . $strPrevStykkeTilkode;
				}
				
				// Set Column 6 = Stykke tilkode
				$arrTransform[$nPrevLineTransform][6] = $strPrevStykkeTilkode;
			# END Calculate final Stykke-data from previous Stykke & Mappe (if any) #
		}
		
		// Log writing transform results
		$strDescription = '- done transforming ' . $nLineTransform . ' lines' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
		
		// Log writing target results
		$strDescription = '- done target ' . $nLineTransform . ' lines' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
		
		# Populate target array
		foreach ($arrTransform as $arrLine) {
			array_push($arrTarget, $arrLine);
		}
		
		// Log transform to target results
		$strDescription = '- ' . count($arrTransform) . ' transform elements copied to ' . count($arrTarget) . ' target' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
		
		if (0 == $nTransformError) {
			// Log no errors
			$strDescription = '- no transform errors' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		} else {
			// Error: transform errors detected
			$strDescription = PHP_EOL . '## transform errors detected: ' . $nTransformError . ' ##' .  PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		}
	# END 31.3 - Transform implementation #
	
	// END inc-section
	if ($bLogInc) {
		$strDescription = 'END php include <<< ' . $strIncPhpScript . ' <<<' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
?>
