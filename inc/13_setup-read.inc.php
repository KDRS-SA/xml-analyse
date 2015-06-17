<?php

	### 13_setup-read.inc.php ###
	
	# Sections in this inc-file: #
	# 13.1 - PHP command line parameters (arg value read loop) #
	# 13.2 - INI setup-file #
	# 13.3 - XML setup-file #
	
	## Global variables created in this include-section: ##
	
	// Read from PHP console parameters
	// $arrPhpParameter[<parameter key>] = <parameter value>
	$arrPhpParameter = array();
	
	// Read from INI setup-file
	$nIniSection = 0;
	$nIniSectionKey = 0;
	// $arrIniSection[<[section]>] = <0..n>  | Section order from top to bottom
	$arrIniSection = array();
	// $arrIniSectionKey[<[section]>][<key>] = <0..n>  | Key order from top to bottom
	$arrIniSectionKey = array();
	// $arrIniParameter[<key>] = <value>
	$arrIniParameter = array();
	
	// Read from XML setup-file
	$nXmlSection = 0;
	$nXmlSectionKey = 0;
	// $arrXmlSection[<[section]>] = <0..n>  | Section order from top to bottom
	$arrXmlSection = array();
	// $arrXmlSectionKey[<[section]>][<key>] = <0..n>  | Key order from top to bottom
	$arrXmlSectionKey = array();
	// $arrIniParameter[<key>] = <value>
	$arrXmlParameter = array();
	$nbXmlSetupError = 0;		// =0 no errors, =1 error detected do not exit, =2 error detected and exit
	## END global variables ##
	
	// PHP script
	$strIncPhpScript = pathinfo(__file__)['basename'];
	
	// START inc-section
	if ($bLogInc) {
		$strDescription = PHP_EOL . 'START php include >>> ' . $strIncPhpScript . ' >>>' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	##### 13.1 - PHP command line parameters (arg value read loop) #####
	
	// Read from PHP console parameters
	// $arrPhpParameter[<parameter key>] = <parameter value>
	
	// Strips trailing "-", key-value separator is "?="
	// exits if duplicate parameter keys
	
	// Log PHP parameter section
	$nTemp = count($argv) - 1;
	$strDescription = '- php parameter section' . PHP_EOL;
	$strLine = '';
	logLog(0, $strDescription, $strLine, 2);
	
	// Log PHP parameter count
	$nTemp = count($argv) - 1;
	$strDescription = '-- php parameter count = "' . $nTemp . '"' . PHP_EOL;
	$strLine = '';
	logLog(0, $strDescription, $strLine, 2);
	
	$arrTemp = array();
	$i=1;
	while ($i < count($argv)) {
		// Get PHP parameter key and value
		$arrTemp = explode('=', $argv[$i]);
		
		// Strip '-' from start of parameter if exists
		if ('-' == substr($arrTemp[0], 0, 1)) {
			$arrTemp[0] = substr($arrTemp[0], 1, strlen($arrTemp[0])-1);
		}
		$strKey = $arrTemp[0];
		
		// Exit if duplicate parameter
		if ( isset($arrPhpParameter[$arrTemp[0]]) ) {
			print PHP_EOL;
			print '## parameter error, duplicate : [' . $arrTemp[0] . '] ##' . PHP_EOL;
			print 'Run with help parameter: "php ' . $strPhpScript . ' help"' . PHP_EOL;
			print '> PHP EXIT <' . PHP_EOL;
			exit;
		}
		
		if (1 == count($arrTemp)) {
			if ($bLogPhpParameter) {
				// Parameter key without value
				$arrPhpParameter[$arrTemp[0]] = '';
				$strDescription = '-- parameter without value "' . $arrTemp[0] . '"' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, $nLogModePhpParameter);
			}
		} elseif (2 !== count($arrTemp)) {
			// Error: More than one "=" in parameter element
			print PHP_EOL;
			print '- Parameter error, must include one single "=" : [' . $argv[$i] . ']' . PHP_EOL;
			print 'Run with help parameter: "php ' . $strPhpScript . ' help"' . PHP_EOL;
			print '> PHP EXIT <' . PHP_EOL;
			exit;
		} else {
			$arrPhpParameter[$arrTemp[0]] = $arrTemp[1];
			
			// Log PHP parameter key & value
			if ($bLogPhpParameter) {
				if ('' == $arrTemp[1]) {
					// Parameter key with empty value
					$strDescription = '-- parameter with empty value "' . $arrTemp[0] . '="' . PHP_EOL;
					$strLine = '';
					logLog(0, $strDescription, $strLine, $nLogModePhpParameter);
				} else {
					// Parameter key with matching value
					$strDescription = '-- parameter & value: "' . $arrTemp[0] . '=' . $arrTemp[1] . '"' . PHP_EOL;
					$strLine = '';
					logLog(0, $strDescription, $strLine, $nLogModePhpParameter);
				}
			}
		}
		$i++;
	}
	$arrTemp = array();
	unset($i);
	##### END 13.1 - PHP command line parameters (arg value read loop) #####
	
	##### 13.2 - INI setup-file #####
	
	if (!$fSetup = fopen($filenameIniSetup, 'r')) {
		// Error: failed to open file
		$strDescription = PHP_EOL . '## error opening INI-setup filename [' . $filenameIniSetup . '] ##' .  PHP_EOL;
		$strLine = '';
		logError(0, $strDescription, $strLine, 2);
		
		$strDescription = '> PHP EXIT <' . PHP_EOL;
		$strLine = '';
		logError(0, $strDescription, $strLine, 2);
		
		flushExit();
		exit;
	} else {
		// Log open INI-setup filename
		$strDescription = '- open INI-setup filename "' . $filenameIniSetup . '"' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	$arrTemp = array();
	$strSection = '';
	while ( !feof($fSetup) ) {
		$strFileLine = trim(fgets($fSetup));
		if (false === $strFileLine) {
			$strDescription = '-- done reading INI setup-file (readline === false)' . PHP_EOL;
			$strLine = '';
			logLog(0, $strDescription, $strLine, 2);
		}
		
		// Skip all lines starting with ";"
		$strTemp = substr($strFileLine, 0, 1);
		if ( !('' == $strFileLine OR ';' == $strTemp) ) {
			if ('[' == substr($strFileLine, 0, 1)) {
				// INI-file [section]
				$strSection = $strFileLine;
				
				// Strip preceding "["
				$strTemp = substr($strFileLine, 1, strlen($strFileLine)-1);
				
				// Strip trailing "]"
				if (']' == substr($strTemp, strlen($strTemp)-1, 1)) {
					$strTemp = substr($strTemp, 0, strlen($strTemp)-1);
					
					// Check for error; additional "[" or "]"
					if (strpos($strTemp, '[') ) {
						// Error: > 1 "[" in INI-file [section]
						$strDescription = PHP_EOL . '## error: > 1 "[" in INI-file [section] "' . $strFileLine . '" ##' .  PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						$strDescription = '> PHP EXIT <' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						flushExit();
						exit;
					} elseif (strpos($strTemp, ']') ) {
						// Error: > 1 "]" in INI-file [section]
						$strDescription = PHP_EOL . '## error: > 1 "]" in INI-file [section] "' . $strFileLine . '" ##' .  PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						$strDescription = '> PHP EXIT <' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						flushExit();
						exit;
					}
				} else {
					// Error: no trailing "]" in INI-file [section]
					$strDescription = PHP_EOL . '## error: no trailing "]" in INI-file [section] "' . $strFileLine . '" ##' .  PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, 2);
					
					$strDescription = '> PHP EXIT <' . PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, 2);
					
					flushExit();
					exit;
				}
				
				// At this point: $strFileLine = "[section]", $strTemp = "section"
				$strSection = $strTemp;
				$strSection2 = '[' . $strSection . ']';
				
				if ( isset($arrIniSection[$strSection]) ) {
					// Error: duplicate INI-file [section]
					$strDescription = PHP_EOL . '## error: duplicate INI-file [section]: "' . $strFileLine . '" ##' .  PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, 2);
					
					$strDescription = '> PHP EXIT <' . PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, 2);
					
					flushExit();
					exit;
				} else {
					$arrIniSection[$strSection] = $nIniSection;
					$nIniSection++;
					$nIniSectionKey = 0;
				}
			} else {
				// Check for INI-file <key> = <value> line
				$arrTemp = explode('=', $strFileLine);
				
				if (1 == count($arrTemp)) {
					// Error: "=" missing in INI-file <key> = <value> line
					$strDescription = PHP_EOL . '## error: "=" missing in INI-file <key> = <value> line:' . PHP_EOL;
					$strLine = '"' . $strFileLine . '" ##' .  PHP_EOL;
					logError(0, $strDescription, $strLine, 2);
					
					$strDescription = '> PHP EXIT <' . PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, 2);
					
					flushExit();
					exit;
				} else {
					// <key> = <value> line with single "="
					$strKey = trim($arrTemp[0]);
					$strValue = trim($arrTemp[1]);
					
					if ('' == $strKey) {
						// Error: INI-file key cannot be empty
						$strDescription = PHP_EOL . '## error: INI-file key cannot be empty:' . PHP_EOL;
						$strLine = '"' . $strFileLine . '" ##' .  PHP_EOL;
						logError(0, $strDescription, $strLine, 2);
						
						$strDescription = '> PHP EXIT <' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						flushExit();
						exit;
					}
					
					// strip trailing comments
					$arrTemp = explode(';', $strValue);
					$strValue = trim($arrTemp[0]);
					
					if ( isset($arrIniSectionKey[$strSection][$strKey]) ) {
						// Error: duplicate INI-file [section][key]
						$strDescription = PHP_EOL . '## error: duplicate INI-file [section][key]: "' . PHP_EOL;
						$strLine = '"' . $strFileLine . '" ##' .  PHP_EOL;
						logError(0, $strDescription, $strLine, 2);
						
						$strDescription = '> PHP EXIT <' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						flushExit();
						exit;
					} else {
						$arrIniSectionKey[$strSection][$strKey] = $nIniSectionKey;
						$nIniSectionKey++;
					}
					
					if ( isset($arrIniParameter[$strKey]) ) {
						// Error: duplicate INI-file parameter [key]
						$strDescription = PHP_EOL . '## error: duplicate INI-file parameter [key] in [section]: "' . $strSection2 . ']' . PHP_EOL;
						$strLine = '"' . $strFileLine . '" ##' .  PHP_EOL;
						logError(0, $strDescription, $strLine, 2);
						
						$strDescription = '> PHP EXIT <' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						flushExit();
						exit;
					} else {
						$arrIniParameter[$strKey] = $strValue;
					}
				}	// END <key> = <value> line with single "="
			}	// END if INI-file [section] or <key> = <value>
		}	// if '' (empty) OR ';' (text info) INI-file line
	}	// while read INI setup-file
	
	// Log INI-setup section, section-key, key & value
	$strDescription = '-- INI-file [section] count = ' . $nIniSection . PHP_EOL;
	$strLine = '';
	logLog(0, $strDescription, $strLine, 2);
	
	if ($bLogSetupIni) {
		// $arrIniSection[<[section]>] = <0..n>  | Section order from top to bottom
		// $arrIniSectionKey[<[section]>][<key>] = <0..n>  | Key order from top to bottom
		// $arrIniParameter[<key>] = <value>
		// $nIniSection
		// $nIniSectionKey
		
		if (0 < count($arrIniSection)) {
			$strTemp = '';
			
			// Log INI-setup section
			foreach ($arrIniSection as $strKey => $strValue) {
				// Log INI-setup section element
				$strDescription = '--- INI-setup section element "' . $strKey . '[' . $strValue . ']' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, $nLogModePhpParameter);
			}
			
			// Log INI-setup section & key
			foreach ($arrIniSectionKey as $strTemp => $arrTemp) {
				foreach ($arrTemp as $strKey => $strValue) {
					// Log INI-setup section & key & position
					$strDescription = '--- INI-setup section & key "' . $strTemp . '-';
					 $strDescription .= $strKey .' [' . $strValue . ']' . PHP_EOL;
					$strLine = '';
					logLog(0, $strDescription, $strLine, $nLogModePhpParameter);
				}
			}
				
			// Log INI-setup key & values
			foreach ($arrIniParameter as $strKey => $strValue) {
				// Log INI-setup section element
				$strDescription = '--- INI-setup key & value "' . $strKey . ' = ' . $strValue . '"' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, $nLogModePhpParameter);
			}
		}	// INI-setup has elements
	}
	
	unset($strSection);
	unset($strSection2);
	unset($strTemp);
	unset($strFileLine);
	$arrTemp = array();
	fclose($fSetup);
	##### END 13.2 - INI setup-file #####
	
	##### 13.3 - XML setup-file #####
		
	// ToDo: Cleanup!
	// if (!$xmlSetup = simplexml_load_file($filenameXmlSetup)) {
	if (!$xmlSetupIterator = new SimpleXMLIterator( $filenameXmlSetup, null, true)) {
		// Error: failed to open file
		$strDescription = PHP_EOL . '## error opening XML-setup filename [' . $filenameXmlSetup . '] ##' .  PHP_EOL;
		$strLine = '';
		logError(0, $strDescription, $strLine, 2);
		
		$strDescription = '> PHP EXIT <' . PHP_EOL;
		$strLine = '';
		logError(0, $strDescription, $strLine, 2);
		
		flushExit();
		exit;
	} else {
		// Log open XML-setup filename
		$strDescription = '- open XML-setup filename "' . $filenameXmlSetup . '"' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
	// parse through XML-setup and store values in 
	
	for ($xmlSetupIterator->rewind(); $xmlSetupIterator->valid(); $xmlSetupIterator->next()) {
		// $arrXmlSection[<[section]>] = <0..n>  | Section order from top to bottom
		// $arrXmlSectionKey[<[section]>][<key>] = <0..n>  | Key order from top to bottom
		// $arrXmlParameter[<key>] = <value>
		// $nXmlSection
		// $nXmlSectionKey
        switch($xmlSetupIterator->key()) {
			case 'special_char':
				// Log special characters
				$strDescription = '-- special characters "' . $xmlSetupIterator->current() . '"' . PHP_EOL;
				$strLine = '';
				logLog(0, $strDescription, $strLine, $nLogModeSetupXml);
				break;
			case 'parameter':
				// Log parameters
				// $strDescription = '-- parameter "' . $xmlSetupIterator->current() . '"' . PHP_EOL;
				// $strLine = '';
				// logLog(0, $strDescription, $strLine, 2);
				break;
			default:
				if (!$xmlSetupIterator->hasChildren()) {
					// XML-setup error, no exit
					if (2 > $nbXmlSetupError) {
						$nbXmlSetupError = 1;
					}
					
					// Empty XML-setup section
					$strDescription = '## Empty XML-setup section "' . $xmlSetupIterator->key() . '" ##' .  PHP_EOL;
					$strLine = '';
					logError(0, $strDescription, $strLine, $nLogModeSetupXml);
					
					if ('' !== trim($xmlSetupIterator->current()) ) {
						// Empty XML-setup section, element value
						$strDescription = '## Empty XML-setup section, element value "' . trim($xmlSetupIterator->current()) . '" ##' .  PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, $nLogModeSetupXml);
					}
					
				} else {
					// XML-setup section
					$strSection = $xmlSetupIterator->key();
					$strSection2 = '[' . $strSection . ']';
					
					if ( isset($arrXMLSection[$strSection]) ) {
						// Error: duplicate XML-setup [section]
						$strDescription = PHP_EOL . '## error: duplicate XML-setup [section]: "' . $strSection . '" ##' .  PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						$strDescription = '> PHP EXIT <' . PHP_EOL;
						$strLine = '';
						logError(0, $strDescription, $strLine, 2);
						
						flushExit();
						exit;
					} else {
						$arrXmlSection[$strSection] = $nXmlSection;
						$nXmlSection++;
						$nXmlSectionKey = 0;
					}
					
					if ($bLogSetupIni) {
						// Log XML-setup section
						$strDescription = '-- XML-setup section "' . $xmlSetupIterator->key() . '"' . PHP_EOL;
						$strLine = '';
						logLog(0, $strDescription, $strLine, $nLogModeSetupXml);
					}
					
					foreach ($xmlSetupIterator->getChildren() as $strKey => $strValue) {
						// XML-setup element
						$strKey2 = '[' . $strKey . ']';
						$strValue = trim($strValue);
						
						if ( isset($arrXmlSectionKey[$strSection][$strKey]) ) {
							// Error: duplicate XML-setup [section][key]
							$strDescription = PHP_EOL . '## error: duplicate XML-setup [section][key]: "' . PHP_EOL;
							$strLine = $strSection2 . $strKey2 . ' ##' .  PHP_EOL;
							logError(0, $strDescription, $strLine, 2);
							
							$strDescription = '> PHP EXIT <' . PHP_EOL;
							$strLine = '';
							logError(0, $strDescription, $strLine, 2);
							
							flushExit();
							exit;
						} else {
							$arrXmlSectionKey[$strSection][$strKey] = $nXmlSectionKey;
							$nXmlSectionKey++;
						}
						
						if ( isset($arrXmlParameter[$strKey]) ) {
							// Error: duplicate XML-setup parameter [key]
							$strDescription = PHP_EOL . '## error: duplicate XML-setup parameter [key] in [section]: "' . $strSection2 . PHP_EOL;
							$strLine = '"' . $strFileLine . '" ##' .  PHP_EOL;
							logError(0, $strDescription, $strLine, 2);
							
							$strDescription = '> PHP EXIT <' . PHP_EOL;
							$strLine = '';
							logError(0, $strDescription, $strLine, 2);
							
							flushExit();
							exit;
						} else {
							$arrXmlParameter[$strKey] = $strValue;
						}
						
						if ($bLogSetupXml) {
							// Log XML-setup section & key / key & value
							$strDescription = '--- XML-setup section & key [' . $strSection . '][' . $strKey . ']';
							$strDescription .= ', key & value "' . $strKey . ' = ' . $strValue . '"' . PHP_EOL;
							$strLine = '';
							logLog(0, $strDescription, $strLine, $nLogModeSetupXml);
						}
					}
				}
        }	// end switch xmlIterator level 1
    }
	
	if ($bLogSetupXml) {
		print 'xml setup' . PHP_EOL;
		// ToDo: Write compact function with print_r functionality with only minimum output lines
		// function "print_array($arrInput)" as multi-line string
		if (0 !== $nLogModeSetupXml) {
			print_r ($xmlSetupIterator);
		}
	}
	
	if (0 < $nbXmlSetupError) {
		// Error: xml setup-file errors detected
		$strDescription = PHP_EOL . '## XML setup-file errors detected ##' .  PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
		
		if (2 == $nbXmlSetupError) {		
			$strDescription = '> PHP EXIT <' . PHP_EOL;
			$strLine = '';
			logError(0, $strDescription, $strLine, 2);
			
			// Log til log-file only
			logLog(0, $strDescription, $strLine, 0);
			
			flushExit();
			exit;
		}
	}
	
	// ToDo: need for closing or unset simpleXML_load_file?
	
	##### END 13.3 - XML setup-file #####
	
	// END inc-section
	if ($bLogInc) {
		$strDescription = 'END php include <<< ' . $strIncPhpScript . ' <<<' . PHP_EOL;
		$strLine = '';
		logLog(0, $strDescription, $strLine, 2);
	}
	
?>
