# xml-large-files-php
Generate, handle and validate large xml-files in PHP

Sections below:<br>
- PHP framework<br>
- PHP console howto<br>
- Transform definition<br>
- Transform implementation<br>
- Tools (PHP-editor, Running PHP from console window)<br>

## PHP framework ##

Using a PHP procedural framework with standard generic functionality for code name/version/date, PHP-parameters, INI-setup, XML-setup, log-file, error-file, source-file, transform-implementation section and target-file.<br>

Open Source PHP framework "KDRS-SA/php-procedural-framework":<br>
https://github.com/KDRS-SA/php-procedural-framework<br>

ToDo:<br>
- Read debug-parameters from parameters (not set variables in PHP-script manually).
- Coherent overall parameter-definitions from PHP-parameters, INI-setup, XML-setup.
- Implement resulting run-parameters into the PHP-code (read into variables for execution).<br>

## Transform definition ##

Sections
1. Check xml well-formed
2. Check xml validate

Not implemented yet
3. Add x-repetitions of "mappe" to input file in setup.ini
3. Add y-errors to input file in setup.ini
1. Check xml well-formed on added xml file
2. Check xml validate on added xml file

Only single file implemented in this first version.
Using stream-based XML Reader so large xml-files are supported.

ToDo:
- Implement section 3,4,5,6
- Implement directory (and subdirectories) xml-parsing (to check for well-formed and validation)

- Implement error-traps on all levels
- Implement isset on all array-elements to be read as parameters from PHP-parameter, INI-setup & XML-setup.
- Implement parameter for paramaeter logging console and/or file (extent from log/nolog as is).
- Complete setup.xml to array XML-setup.
- Implement RunParameter selection from PHP-parameter, setup.ini and setup.xml (uses setup.ini as is).

## Tools ###
PHP-editor<br>
Running PHP from console window <br>

### PHP editor ###
geany is a PHP-editor (IDE - Integrated Developer Environment):<br>
https://download.geany.org/geany-1.23_setup.exe

PHP documentation (syntax, functions and parameters):<br>
http://php.net

### Running PHP from console window ###

PHP-client has to be installed our your local computer to be to run PHP from console window.<br>
Setup instructions in Norwegian (to be translated into English).<br>
https://github.com/KDRS-KURS/PHP-eArkivar/blob/master/doc/Programvare%20for%20kurset.pdf<br>

Test if a PHP-cliemt is running correctly on your computer:<br>
- Push and hold the Windows-button and push button "R" simultaniously.<br>
- Enter "cmd" i dialog-window and click "OK".<br>
- The console window is now opened.<br>
- Enter "php -v" to test the PHP-client running and display version installed (result for ex: [PHP 5.6.5 (cli)...])<br>
- Navigate in conolse window to directory with the PHP-script to be executed:.<br>
- Microsoft Windows example:
-- Select harddisk C: [cd c:]<br>
-- Move to top-level root-directory: [cd \]<br>
-- Move to desired subdirectory (relative to your location at the moment), ex: "php-test": [cd php-test]<br>
-- List directory content: [dir]<br>

- Run a local PHP-file inside your selected directory, ex. "test.php": [php test.php]<br>
- That is, "php" folowed by blank space and the name of the PHP-feil to be runned including ".php" at end.<br>

- Tip: All commands in windows console has <tab-button> as automatically select<br>
  the remaining text based on existing files and folders in selected directory.<br>
-- Repeat pushing <tab-button> to scroll through the available options for your so far entered text.<br>
-- Enter "php" and empty space first, and then repeat pushing <tab-button> to loop through the files.<br>
-- You don't have to enter precicely long filenames etc.<br>
