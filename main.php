<?php
	
	// NO
	// Kode for analyse av XML-uttrekk (katalog med xml-filer).
	// Analyserer og logger innhold uten noen forhåndsinformasjon.
	// Bruker metode SAX XMLReader.
	
	// EN
	// Code analysis of xml-files (elements and attributes) in directory.
	// Using the SAX XMLReader extension (an XML Pull parser).
	// Going forward on the document stream and stopping at each node on the way.
	
	// Main PHP-script
	$strPhpScript = pathinfo(__file__)['basename'];	// Main PHP script
	
	// Inc sections
	require_once 'inc/testProperties/XMLWellFormedTestProperty.php';
	require_once 'inc/testProperties/XMLValidationTestProperty.php';
	require_once 'inc/xml/XMLTestWellFormed.php';
	require_once 'inc/xml/XMLTestValidation.php';
	
	include_once 'inc\11_init.inc.php';
	include_once 'inc\12_functions.inc.php';
	include_once 'inc\13_setup-read.inc.php';
	include_once 'inc\14_setup-select.inc.php';
	include_once 'inc\51_xml-transform.inc.php';
	include_once 'inc\91_end.inc.php';
	
?>
