<?php
/**
 * Default settings for the odt2dw plugin
 *
 * @author Greg BELLAMY <garlik.crx@gmail.com> [Gag]
 */

$conf['debugLvl']                 = 1;                  // debug mode level -- more verbose ( 0: no display; 1: display error msg; 3: display&log error msg all msg; 3: display&log all )
$conf['logFile']                  = '';                 // log File where $this->_msg write with debugLvl >= 2
$conf['formDisplayRule']          = 'odt2dw,edit,show'; // which action will display the odt2dw upload form in newpage
$conf['formIntroMessage']         = 'default';          // personnalized message - if "default", display the language default message
$conf['formMaxFileSize']          = 2097152;            // maxsize for upload odtFile
$conf['parserPostDisplay']        = 'show';             // which action perform after parsing the odt file
$conf['parserXslFile']            = 'odt2dw.xsl';       // name of the xslpage use to transform the odtFile
$conf['parserLinkToOriginalFile'] = 1;                  // display a link to the original odtFile 0=no link; 1=link
$conf['parserCoreTimeOut']        = 300;                // the parserCoreTimeOut give specific timeout to parse the xmlFile with the xslFile. Bigger is the odtFile, longer will the parser take.
$conf['parserUploadDir']          = '/tmp/odt2dw/';     // systeme path where the file will be move after upload but before parse
