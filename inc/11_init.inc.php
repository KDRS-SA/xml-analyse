<?php

	### 11_init.inc.php ###
	
	# Sections in this inc-file: #
	# 11.1 - PHP command line parameters (help) #
	# 11.2 - version, code & framework #
	
	## Global variables created in this include-section: ##
	
	$bInitDone = false;		// = true after this inc-file is ready with intitial steps
	
	// Final setup-parameters after analysing PHP parameters, INI & XML setup-files
	// $arrRunParameter[<parameter key>] = <parameter value>
	$arrRunParameter = array();
	
	// $argv is a "system" array with all PHP console parameters
	// "php <parameter 0> <parameter 1> <parameter 2 etc.>"
	// $argv[0] is the running php-file itself, ex. "main.php"
	
	// $arrLog [0]=input linenr, [1]=log description, [2]=complete line of data
	// [0] = 0 for logging not related to an input line
	$arrLog = array ();
	$nArrLog = 0;			// counter for log elements in array
	$nLog = 0;				// counter total log-lines
	$nFlushLogFile = 500;	// limit until log-array flushed to file-writing
	$bLogFile = true;		// =true, create logfile
	$bLogFileOpen = false;
	
	// $arrError [0]=input linenr, [1]=error description, [2]=complete line of data
	// [0] = 0 for errors not related to an input line
	$arrError = array ();
	$nArrError = 0;			// counter for error elements in array
	$nError = 0;				// counter total error-lines
	$nFlushErrorFile = 50;				// limit until error-array flushed to file-writing
	$bErrorFileOpen = false;
	
	## END global variables ##
	
	// General parameters
	// ToDO: debug parameter vs debug setup
	// Check for debugmode levels etc.
	
	// Initial default values - may be replaced by RunParameters (PHP-parameters, XML-setup, INI-setup)
	$strDefaultCSV = ';';
	$strDefaultCSVEntity = '_#_';
	
	$bLogInc = true;				// =true, log debug info from inc-sections; =false, disabled
	$bLogPhpParameter = true;		// =true, show parameters; =false, disabled
	$nLogModePhpParameter = 0;		// =0 - file only; =1 console only; =2 both console and file	

	$bLogSetupIni = true;			// =true, log setup.ini; =false, disabled
	$nLogModeSetupIni = 0;			// =0 - file only; =1 console only; =2 both console and file	

	$bLogSetupXml = true;			// =true, show parameters; =false, disabled
	$nLogModeSetupXml = 0;			// =0 - file only; =1 console only; =2 both console and file	
	
	$bDebugLogErrorFiles = false;	// =true, log dummy-line in log-file and error-fil
	$bDebugDeveloper1 = true;		// =true, extended debugging; =false, disabled
	
	// Setup files
	$filenameXmlVersion = 'xml\version.xml';
	$filenameHelp = 'php-help.txt';
	$filenameIniSetup = 'setup.ini';
	$filenameXmlSetup = 'xml\setup.xml';
	
	// PHP script
	$strIncPhpScript = pathinfo(__file__)['basename'];
	
	// Timestamp
	$thisTimezone = 'Europe/Oslo';
	date_default_timezone_set($thisTimezone);
	$timeStart = time();
	$strStartDateTime = date('Y-m-d\TH:i:sP', $timeStart);
	
	##### 11.1 - PHP command line parameters (help) #####
	
	if ($argv[0] !== $strPhpScript) {
		print PHP_EOL;
		print '## PHP error: [' . $argv[0] . '] !== [' . $strPhpScript . '] ##' . PHP_EOL;
		exit;
	}
	
	// Hardcoded parameters: [-h], [-help], [h] & [help]; Display help info and exit
	// The helpfile must exist as provided in variable $filenameHelp above
	if ( count($argv) > 1 ) {
		if ('-h' == $argv[1] OR '-help' == $argv[1]  OR 'h' == $argv[1] OR 'help' == $argv[1]) {
			// help-file
			if (!$fHelp = fopen($filenameHelp, 'r')) {
				// Error: failed to open file
				print PHP_EOL;
				print '## error opening help filename [' . $filenameHelp . '] ##' .  PHP_EOL;
				print '> PHP EXIT <' . PHP_EOL;
				exit;
			}
			
			print PHP_EOL;
			print '### help information # ' . $strPhpScript . ' ###' . PHP_EOL;
			print PHP_EOL;
			
			while ( !feof($fHelp) ) {
				$strLine = fgets($fHelp);
				print $strLine;
			}
			print PHP_EOL;
			fclose($fHelp);
			exit;
		}	// if first parameter = help
	}	// if count($argv) > 1
	
	##### END 11.1 - PHP command line parameters (arg value & help) #####
	
	// PHP start
	$strDescription = PHP_EOL . 'PHP start [' . $strStartDateTime . ']' . PHP_EOL;
	$strLine = 'PHP filnavn "' . $strPhpScript . '"' . PHP_EOL;
	logLogInit(0, $strDescription, $strLine, 2);
	
	if ($bDebugLogErrorFiles) {
		// DEBUG LOG
		$strDescription = PHP_EOL . 'PHP DEBUG LOG (testing log-function)' . PHP_EOL;
		$strLine = 'SECOND line with semicolon ";" debugging' . PHP_EOL;
		logLogInit(0, $strDescription, $strLine, 2);
	}
	
	// START inc-section
	if ($bLogInc) {
		$strDescription =PHP_EOL . 'START php include >>> ' . $strIncPhpScript . ' >>>' . PHP_EOL;
		$strLine = '';
		logLogInit(0, $strDescription, $strLine, 2);
	}
	
	# 11.2 - version, code & framework #
	
	if (!$xmlVersion = simplexml_load_file($filenameXmlVersion)) {
		// Error: failed to open file
		print PHP_EOL;
		print '## error opening version filename [' . $filenameXmlVersion . '] ##' .  PHP_EOL;
		print '> PHP EXIT <' . PHP_EOL;
		exit;
	} else {
		// Log open version filename
		$strDescription = '- open version filename "' . $filenameXmlVersion . '"' . PHP_EOL;
		$strLine = '';
		logLogInit(0, $strDescription, $strLine, 2);
	}
	
	## 11.2.1 version -> code: name, version, date ##
	// version-code elements are "hardcoded" and must containt elements in code below
	
	// name
	if ( isset($xmlVersion->code->name) ) {
		$strDescription = '-- code name "' . $xmlVersion->code->name . '"' . PHP_EOL;
		$strLine = '';
		logLogInit(0, $strDescription, $strLine, 2);
	} else {
		print PHP_EOL;
		print '## error code name ##' . PHP_EOL;
		print '> PHP EXIT <' . PHP_EOL;
		exit;
	}
	
	// version
	if ( isset($xmlVersion->code->version) ) {
		$strDescription = '-- code version "' . $xmlVersion->code->version . '"' . PHP_EOL;
		$strLine = '';
		logLogInit(0, $strDescription, $strLine, 2);
	} else {
		print PHP_EOL;
		print '## error code version ##' . PHP_EOL;
		print '> PHP EXIT <' . PHP_EOL;
		exit;
	}
	
	// date
	if ( isset($xmlVersion->code->date) ) {
		$strDescription = '-- code date "' . $xmlVersion->code->date . '"' . PHP_EOL;
		$strLine = '';
		logLogInit(0, $strDescription, $strLine, 2);
	} else {
		print PHP_EOL;
		print '## error code date ##' . PHP_EOL;
		print '> PHP EXIT <' . PHP_EOL;
		exit;
	}
	
	## END 11.2.1 version -> code: name, version, date ##
	
	## 11.2.2 version -> framework: name, version, date ##
	// version-framework elements are "hardcoded" and must containt elements in code below
	
	// name
	if ( isset($xmlVersion->framework->name) ) {
		$strDescription = '-- framework name "' . $xmlVersion->framework->name . '"' . PHP_EOL;
		$strLine = '';
		logLogInit(0, $strDescription, $strLine, 2);
	} else {
		$strDescription = '-- framework version not available' . PHP_EOL;
		$strLine = '';
		logLogInit(0, $strDescription, $strLine, 2);
	}	// framework name
	
	if ( isset($xmlVersion->framework->name) ) {
		// version
		if ( isset($xmlVersion->framework->version) ) {
			$strDescription = '-- framework version "' . $xmlVersion->framework->version . '"' . PHP_EOL;
			$strLine = '';
			logLogInit(0, $strDescription, $strLine, 2);
		} else {
			print PHP_EOL;
			print '## error framework version ##' . PHP_EOL;
			print '> PHP EXIT <' . PHP_EOL;
			exit;
		}
		
		// date
		if ( isset($xmlVersion->framework->date) ) {
			$strDescription = '-- framework date "' . $xmlVersion->framework->date . '"' . PHP_EOL;
			$strLine = '';
			logLogInit(0, $strDescription, $strLine, 2);
		} else {
			print PHP_EOL;
			print '## error framework date ##' . PHP_EOL;
			print '> PHP EXIT <' . PHP_EOL;
			exit;
		}
	}	// framework version & date
	
	## END 11.2.2 version -> framework: name, version, date ##
	
	# END 11.2 - version, code & framework #
	
	// END inc-section
	if ($bLogInc) {
		$strDescription = 'END php include <<< ' . $strIncPhpScript . ' <<<' . PHP_EOL;
		$strLine = '';
		logLogInit(0, $strDescription, $strLine, 2);
	}
	
	function logLogInit($nLine, $strDescription, $strLine, $nMode) {
			// Log log (init-section only)
			
			// Declare global variables
			global $arrLog, $nArrLog, $nLog, $strCSV, $strCSVEntity;
			
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
			}
		}	// end function
?>
