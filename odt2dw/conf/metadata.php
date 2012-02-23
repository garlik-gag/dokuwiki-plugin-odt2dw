<?php
/**
 * Options for the odt2dw plugin
 *
 * @author Greg BELLAMY <garlik.crx@gmail.com> [Gag]
 */


$meta['debugLvl']                 = array('multichoice', '_choices' => array(0,1,2,3));
$meta['logFile']                  = array('string');
$meta['formDisplayRule']          = array( 'multicheckbox', '_choices' => array( 'odt2dw', 'edit', 'show' ) );
$meta['formIntroMessage']         = array('');
$meta['formMaxFileSize']          = array('numericopt');
$meta['parserPostDisplay']        = array('multichoice', '_choices' => array( 'odt2dw', 'edit', 'show', 'preview' ) );
$meta['parserXslFile']            = array('string');
$meta['parserLinkToOriginalFile'] = array('onoff');
$meta['parserCoreTimeOut']        = array('numericopt');
$meta['parserUploadDir']          = array('string');
