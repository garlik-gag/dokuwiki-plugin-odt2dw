<?php
/**
 * English language setting file
 *
 * @author Greg BELLAMY <garlik.crx@gmail.com> [Gag]
 */

$lang['debugLvl']                 = "Debug mode -- more verbose : 0-void, 1-Display error, 2-Log&Display error, 3-Log&Display all message";
$lang['logFile']                  = "Path to the log file";
$lang['formDisplayRule']          = "Action list where the import file form will be displayed";
$lang['formIntroMessage']         = "<div>Message show before the upload form</div><div>  - dokuwiki syntax allowed</div><div>  - Default value : default -> get the message from the default language file</div>";
$lang['formMaxFileSize']          = "<div>Max size allowed to upload file</div><div>The value must be lower than the Apache max size value</div>";
$lang['parserPostDisplay']        = "Displayed mask after an import";
$lang['parserXslFile']            = "Name of the xslFile use to transform";
$lang['parserLinkToOriginalFile'] = "Create a link to original file into the dokuwiki page";
$lang['parserCoreTimeOut']        = "Special TimeOut used when parsing the odt content by xslt (default : 300).";
$lang['parserUploadDir']          = "Path where the file will be uploaded";
$lang['parserMimeTypeAuthorized'] = "<div>Mimetypes authorized to upload odt files</div><div>Default : <tt>application/vnd.oasis.opendocument.text application/octetstream</tt></div><div><b>application/vnd.oasis.opendocument.text</b> default common mimetype</div><div><b>application/octetstream</b> mimetype used by Chrome on Windows XP</div><div>Add missing mimetypes if necessary</div><div>Leave empty to disabled the control.</div>";
