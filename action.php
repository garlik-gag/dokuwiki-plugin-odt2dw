<?php
/**
 * Action Plugin
 *
 * @license     GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author      Greg BELLAMY <garlik.crx@gmail.com> [Gag]
 * @version     0.08beta
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

class action_plugin_odt2dw extends DokuWiki_Action_Plugin {

  /**
  * Registers a callback function for a given event
  */
  function register(Doku_Event_Handler $controller) {
    // OdtFile Parser hook
    $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, '_parser', array());
    // Display form hook before the wiki page (on top); Maybe create a param to display the form after the page
    $controller->register_hook('TPL_ACT_RENDER', 'BEFORE', $this, '_render', array());
    $controller->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'addbutton', array());
  }

  /**
   * Add 'import odt'-button to pagetools
   *
   * @param Doku_Event $event
   * @param mixed      $param not defined
   */
  public function addbutton(&$event, $param) {
    global $ID, $REV, $conf;

    if($this->getConf('showimportbutton') && $event->data['view'] == 'main') {
      $params = array('do' => 'odt2dw');
      if($REV) $params['rev'] = $REV;

      switch($conf['template']) {
      case 'dokuwiki':
      case 'arago':
	$event->data['items']['import_odt'] =
	  '<li>'
	  .'<a href='.wl($ID, $params).'  class="action import_odt" rel="nofollow" title="'.$this->getLang('import_odt_button').'">'
	  .'<span>'.$this->getLang('import_odt_button').'</span>'
	  .'</a>'
	  .'</li>';
	break;
      }
    }
  }

  function _render(&$event, $param) {
    ### _render : displays the upload form in the pages according to authorized action
    # INPUT : it's a dokuwiki event function
    # OUTPUT : void
    # DISPLAY : upload form
    global $ID, $lang;
    // Check if the current action is in the action allow table
    if ( strpos( $this->getConf('formDisplayRule'), $event->data) === false ) return;
    // Check if the page exists
    if ( page_exists( $ID ) && $event->data != "odt2dw" ) return;
    if ( page_exists( $ID ) ) echo p_render('xhtml',p_get_instructions( $this->getLang( 'formPageExistMessage' ) ), $info );
    // Check auth user can edit this page
    if ( auth_quickaclcheck( $ID ) < AUTH_EDIT ) return;
    // If all check is ok, display the form
    $message = $this->getConf('formIntroMessage');
    if ( $message == 'default' ) $message = $this->getLang('formIntroMessage');
    if ($message) echo p_render('xhtml',p_get_instructions($message),$info);
    // FIXME create the form with dokuwiki method ?
    echo '<form method="post" action="" enctype="multipart/form-data">
<fieldset>
<legend>'.$this->getLang('formLegend').'</legend>
<input type="hidden" name="MAX_FILE_SIZE" value="'.$this->getConf('formMaxFileSize').'"/>
<input type="hidden" name="do" value="odt2dw"/>
<input type="hidden" name="id" value="'.$ID.'"/>
<input type="file" name="odtFile"/>
<input type="submit" value="'.$lang['btn_upload'].'"/>
</fieldset>
</form>';
    if ( $event->data == 'odt2dw' ) $event->preventDefault();
  }

  function _parser(&$event, $param) {
    ### _parser : check if an odtFile migth be upload than call the odt2dw converter
    # INPUT : it's a dokuwiki event function
    # OUTPUT : void

    // Check action is odt2dw
    if ( $event->data != 'odt2dw' ) return;

    ###Preparation of the message renderer
    //Set the debug lvl
    $this->debug = $this->getConf( 'debugLvl' );
    //If used, open the logFile
    if ( $this->debug >= 2 ) {
      $this->logFile = $this->getConf( 'logFile' );
      if ( isset( $this->logFile ) ) if ( file_exists( dirname( $this->logFile ) ) || mkdir( dirname( $this->logFile ) ) ) {
        if ( ! ( $this->logFileHandle = @fopen( $this->logFile, 'a' ) ) ) unset( $this->logFileHandle, $this->logFile );
      } else unset( $this->logFile );
      if ( ! isset( $this->logFileHandle ) ) $this->_msg( 'er_logFile' );
    }
    ###

    // Check upload file defined
    $retour = false;
    if ( $_FILES['odtFile'] ) {
      // If parse work, change action to defined one in conf/local.php file
      $retour = $this->_odt2dw();
      # Delete temp file
      $this->_purge_env();
    }
    //if the file is correctly parsed, change the action to the action defined in the conf
    //otherwise the action stay odt2dw -> the display form hook will be call by render trigger
    if ( $retour === true ) {
      $event->data = $this->getConf('parserPostDisplay');
    } else {
      $event->preventDefault();
    }

    ### Clear the message renderer
    // Close the log file if used
    if ( isset( $this->logFileHandle ) ) @fclose( $this->logFileHandle );
    ###
  }

  function _odt2dw() {
    ### _odt2dw : Translate an odt File into dokuwiki syntax
    # OUTPUT :
    #   * true -> process successfully
    #   * false -> something wrong; using _msg to display what's wrong

    global $ID, $conf;

    //Table use to convert urn to url -> without this, xslProc won t parse correctly
    //Table corrigeant les attributs de la racine du fichier content.xml : urn -> url
    $this->conversion = array(
      "xmlns:office" => "http://openoffice.org/2000/office",
      "xmlns:style" => "http://openoffice.org/2000/style",
      "xmlns:text" => "http://openoffice.org/2000/text",
      "xmlns:table" => "http://openoffice.org/2000/table",
      "xmlns:draw" => "http://openoffice.org/2000/drawing",
      "xmlns:fo" => "http://www.w3.org/1999/XSL/Format",
      "xmlns:xlink" => "http://www.w3.org/1999/xlink",
      "xmlns:dc" => "http://purl.org/dc/elements/1.1/",
      "xmlns:meta" => "http://openoffice.org/2000/meta",
      "xmlns:number" => "http://openoffice.org/2000/datastyle",
      "xmlns:svg" => "http://www.w3.org/2000/svg",
      "xmlns:chart" => "http://openoffice.org/2000/chart",
      "xmlns:dr3d" => "http://openoffice.org/2000/dr3d",
      "xmlns:math" => "http://www.w3.org/1998/Math/MathML",
      "xmlns:form" => "http://openoffice.org/2000/form",
      "xmlns:script" => "http://openoffice.org/2000/script",
      "xmlns:config" => "http://openoffice.org/2001/config",
      "xmlns:ooo" => "http://openoffice.org/2004/office",
      "xmlns:ooow" => "http://openoffice.org/2004/writer",
      "xmlns:oooc" => "http://openoffice.org/2004/calc",
      "xmlns:dom" => "http://www.w3.org/2001/xml-events",
      "xmlns:xforms" => "http://www.w3.org/2002/xforms",
      "xmlns:xsd" => "http://www.w3.org/2001/XMLSchema",
      "xmlns:xsi" => "http://www.w3.org/2001/XMLSchema-instance",
      "xmlns:rpt" => "http://openoffice.org/2005/report",
      "xmlns:of" => "urn:oasis:names:tc:opendocument:xmlns:of:1.2",
      "xmlns:xhtml" => "http://www.w3.org/1999/xhtml",
      "xmlns:grddl" => "http://www.w3.org/2003/g/data-view#",
      "xmlns:tableooo" => "http://openoffice.org/2009/table",
      "xmlns:css3t" => "http://www.w3.org/TR/css3-text/"
    );
    // urn wont be/need to convert -- keep for further odtFile version
    // "xmlns:field" => "urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0",
    //"xmlns:formx" => "urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0",
    // CONSTANT : Content file extract from the odt file
    $this->xmlFile = "content.xml";

    ### Check parameter ###

    // Page receive content
    if ( ! $this->pageName = $ID ) return $this->_msg('er_id');
    $this->nsName = getNS($this->pageName);
    // Check right to change the page
    if ( page_exists($ID) ) {
      if ( auth_quickaclcheck($ID) < AUTH_EDIT ) return $this->_msg('er_acl_edit');
    } else {
      if ( auth_quickaclcheck($ID) < AUTH_CREATE ) return $this->_msg('er_acl_create');
    }

    // Check the Odt file uploaded
    if ( ! $this->_checkUploadFile() ) return $this->_msg('er_checkUploadResult');

    // Check the xslFile
    if ( ! $this->getConf( 'parserXslFile' ) )  return $this->_msg('er_xslFile_notset');
    $this->xslFile = DOKU_PLUGIN.'odt2dw/'.$this->getConf('parserXslFile');
    if ( ! file_exists($this->xslFile) ) return $this->_msg('er_xslFile_exists');
    if ( ! is_file($this->xslFile) ) return $this->_msg('er_xslFile_isfile');

    // Class Control
    if ( ! class_exists( XSLTProcessor ) ) return $this->_msg('er_class_xsltProcessor');
    if ( ! class_exists( ZipArchive ) ) return $this->_msg('er_class_zipArchive');
    if ( ! class_exists( DOMDocument ) ) return $this->_msg('er_class_domDocument');
    // Create instance of needed class
    $this->XSLT = new XSLTProcessor;
    $this->ZIP  = new ZipArchive;
    $this->XSL  = new DOMDocument;
    $this->XML  = new DOMDocument;

    // Load the xslFile
    if ( ! ($this->XSL->load( $this->xslFile ) ) ) return $this->_msg('er_loadXsl');
    // Build the xsl processor
    if ( ! $this->_set_xsltProcessor() ) return $this->_msg('er_xsltProc');
    // Extract content file from odtFile
    if ( ! $this->_unzip( $this->xmlFile ) ) return $this->_msg('er_odtFile_unzip');
    // Load the xmlFile
    if ( ! $this->XML->load($this->uploadDir.'/'.$this->xmlFile) ) return $this->_msg('er_loadXml');
    if ( ! $this->racine = $this->XML->getElementsByTagName('document-content')->item(0) ) return $this->_msg('er_invalidRoot');
    // Correction for urn bug
    foreach ( $this->conversion as $attr => $value ) if ( $this->racine->hasAttribute($attr) ) $this->racine->setAttributeNS( "http://www.w3.org/2000/xmlns/", $attr, $value );
    // Transformation du fichier XML
    $this->result = '====== '.basename($this->odtFileName,'.odt').' ======
';
    if ( $this->getConf('parserLinkToOriginalFile') && auth_quickaclcheck($ID) >= AUTH_UPLOAD ) $this->result .= '<sub>{{'.$this->odtFileName.'|'.$this->getLang('parserOriginalFile').'}}</sub>

';

    ### Parameters have been checked successfully ###


    // Set specific time out to parse the odtfile into dw syntax
    set_time_limit( $this->getConf('parserCoreTimeOut') );
    // Parse the content - This is the CORE
    if ( ! $tmp = html_entity_decode($this->XSLT->transformToDoc( $this->XML )->saveHTML(), ENT_COMPAT, 'UTF-8') ) return $this->_msg('er_transform');
    $this->result .= $tmp;
    // Set the time out to default
    set_time_limit(30);
    // Extract and store image files from odtFile to Dokuwiki mediaManager
    $this->_parse_image();

    // Store the result
    if ( ! $this->_apply_result() ) return $this->_msg('er_apply');

    return true;
  }

  function _msg( $message, $type=null, $force=false ) {
    ### _msg : display message using the debugLvl value
    # $message : mixed :
    #   * string : key for $this->getLang() function
    #   * array :
    #       $message[0] : string : key for $this->getLang() function
    #       $message[1] : string : additional information
    # $type : integer : (check the dokuwiki msg function)
    #   * -1 : error message
    #   *  0 : normal message
    #   *  1 : info message
    # if type == null, the first 3 char of the key define the message type
    #   * er_ : -1
    #   * ok_ :  1
    #   * otherwise : 0
    # $force : boolean : force displaying the message without checking debugLvl
    # OUTPUT :
    #   * true -> display a normal message
    #   * false -> display an error message
    # DISPLAY : call dokuwiki msg function
    if ( is_array( $message ) ) {
      $output = $message[0];
    } else {
      $output = $message;
    }
    // If output is empty, crash with error display;
    if ( ! $output ) die( $this->getLang( 'er_msg_nomessage' ) );
    if ( is_null( $type ) ) {
      $val = substr( $output, 0, strpos( $output, '_' )+1 );
      switch ($val) {
        case 'er_' :
          $err = -1;
          break;
        case 'ok_' :
          $err = 1;
          break;
        default :
          $err = 0;
      }
    } else {
      if ( $type < -1 || $type > 1 ) return false;
      $err = $type;
    }
    // Dev debugging mode; manually set to 4; this dirtily display some informations
    if ( $this->debug > 3 ) echo '<p>message : '.$message.' |output : '.$output.' |val : '.$val.' |err : '.$err.'</p>';

    // Debug = 0 => No message
    if ( !$force && $this->debug == 0 ) return ( $err == -1 ? false : true );

    // Debug < 3 => Only error message; If it s not an error message, message return true;
    if ( !$force && $err != -1 && $this->debug < 3 ) return true;
    // Otherwise display the message
    $content = $output.' : '.$this->getLang( $output ).( is_array( $message ) ? ' : '.$message[1] : '' );
    msg( 'odt2dw : '.$content, $err );
    if ( isset( $this->logFileHandle ) ) fwrite( $this->logFileHandle, date(DATE_ATOM).':'.$_SERVER['REMOTE_USER'].':'.$content.'
' );
    // If error message, return false
    if ( $err == -1 ) return false;
    // Otherwise return true;
    return true;
  }



  function _checkUploadFile() {
    ### _checkUploadFile : group all process about the uploadFile, like uploadStatus, file format, move it in a working directory, etc. ###
    # OUTPUT :
    #   * true -> process successfully
    #   * false -> something wrong; using _msg to display what's wrong
    // Check a file will be upload
    if ( ! $_FILES['odtFile'] ) return $this->_msg('er_odtFile_miss');
    // Check the file status
    if ( $_FILES['odtFile']['error'] > 0 ) return $this->_msg( array( 'er_odtFile_upload', $_FILES['odtFile']['error'] ) );
    // Check the file has an authorized mimetype
    if ( $this->getConf( 'parserMimeTypeAuthorized' ) != "" && strpos( $this->getConf( 'parserMimeTypeAuthorized' ), $_FILES['odtFile']['type'] ) === false ) return $this->_msg( array( 'er_odtFile_format', $_FILES['odtFile']['type'] ) );
    // Create an unique temp work dir name
    while ( file_exists( $this->uploadDir = $this->getConf( 'parserUploadDir' ).rand( 10000, 100000 ) ) ) {};
    // Create the directory
    if ( ! mkdir( $this->uploadDir, 0777, true ) ) return $this->_msg( 'er_odtFile_tmpDir' );
    // Move the upload file into the work directory
    $this->odtFileName = $_FILES['odtFile']['name'];
    $this->odtFile = $this->uploadDir.'/'.$this->odtFileName;
    if ( ! move_uploaded_file( $_FILES['odtFile']['tmp_name'], $this->odtFile ) ) return $this->_msg('er_odtFile_getFromDownload');
    // All upload file checking are OK
    return true;
  }

  function _purge_env() {
    ### _purge_env : clean the system from temporary file ###
    # OUTPUT :
    #   void
    # Display some error message if something wrong in the delete process (might delete the file manually)

    // Perhaps this would not be needed if use temp dir.
    // No timeOut : the cleanning process wont be interrupted.
    set_time_limit(0);
    // use @ to catch the system error message
    // If exists, delete the download file
    if ( file_exists( $this->odtFile ) ) if ( ! @unlink( $this->odtFile ) ) $this->_msg( array( 'er_pg_file', $this->odtFile ) );
    // Delete each file extracted for the uploaded file
    if ( $this->file_extract ) foreach ($this->file_extract as $file) if ( file_exists( $file ) ) if ( ! @unlink( $file ) ) $this->_msg( array( 'er_pg_file', $file ) );
    // Delete each image would be rename and not move to the wiki
    if ( $this->file_import ) foreach ( $this->file_import as $file ) if ( file_exists( $this->uploadDir.'/'.$this->pictpath.'/'.$file ) ) if ( ! @unlink( $this->uploadDir.'/'.$this->pictpath.'/'.$file ) ) $this->_msg( array( 'er_pg_file', $this->uploadDir.'/'.$this->pictpath.'/'.$file ) );
    // Delete the Pictures directory
    if ( file_exists( $this->uploadDir.'/'.$this->pictpath) ) if ( ! @rmdir( $this->uploadDir.'/'.$this->pictpath ) ) $this->_msg( array( 'er_pg_dir', $this->uploadDir.'/'.$this->pictpath ) );
    // Than delete the temporary directory
    if ( file_exists( $this->uploadDir ) ) if ( ! @rmdir( $this->uploadDir ) ) $this->_msg( array( 'er_pg_dir', $this->uploadDir ) );
    // Set back default timeOut
    set_time_limit(30);
  }

  function _set_xsltProcessor(){
    ### _set_xsltProcessor : set all xslt param regarding the dokuwiki plugin installed ###
    # OUTPUT :
    #   * true -> process successfully
    #   * false -> something wrong; using _msg to display what's wrong
    # _msg info report ( debugLvl >= 2 ) display message about active plugin

    // Gag : I think it s a Nasty way to check plugin - must be rewrite but i don t know how
    $tmp_plugin_lst = plugin_list();
    if ( ! $this->XSLT->importStylesheet( $this->XSL ) ) return $this->_msg('er_xslt_invalid');
    foreach ( array('numberedheadings') as $param ) if ( array_search( $param, $tmp_plugin_lst ) !== false ) {
      if ( ! $this->XSLT->setParameter( '', $param, '1' ) ) return $this->_msg( array( 'inf_xslt_param', $param ), -1 );
      // _msg info report
      $this->_msg( array( 'ok_infoPlugin', $param ), 1 );
    }
    //
    foreach ( array('subtable_message') as $lang_elt ) if ( ! $this->XSLT->setParameter( '', $lang_elt, $this->getLang('xsl_'.$lang_elt ) ) ) $this->_msg( array( 'inf_xslt_lang', $param ), 0 );
    return true;
  }

  function _apply_result() {
    ### _apply_result : store the content in dokuwiki page and the attache file (img) in dokuwiki media
    # OUTPUT :
    #   * true -> process successfully
    #   * false -> something wrong; using _msg to display what's wrong
    global $INFO;
    // Save the content in data/page
    saveWikiText( $this->pageName, $this->result, $this->getLang( 'parserSummary' ).$this->odtFileName );
    if ( ! page_exists($this->pageName) ) return $this->_msg('er_apply_content');
    // Check if the user could upload file (ACL : permission lvl 8)
    if ( auth_quickaclcheck($ID) >= AUTH_UPLOAD ) {
      // Import the image file in the mediaManager (data/media)
      $destDir = mediaFN( $this->nsName );
      if ( ! ( file_exists( $destDir ) || mkdir( $destDir, 0777, true ) ) ) return $this->_msg( array( 'er_apply_dirCreate' ) );
      if ( $this->file_import ) foreach ( $this->file_import as $pict ) {
        $destFile = mediaFN( $this->nsName.':'.$pict );
        list( $ext, $mime ) = mimetype( $this->uploadDir.'/'.$this->pictpath.'/'.$pict );
        if ( media_upload_finish($this->uploadDir.'/'.$this->pictpath.'/'.$pict, $destFile, $this->nsName, $mime, @file_exists($destFile), 'rename' ) != $this->nsName ) return $this->_msg( array( 'er_apply_img', $this->uploadDir.'/'.$this->pictpath.'/'.$pict ) );
      }
      // Keep the original file (import the upload file in the mediaManager)
      $destFile = mediaFN( $this->nsName.':'.$this->odtFileName );
      list( $ext, $mime ) = mimetype( $this->uploadDir.'/'.$this->odtFileName );
      if ( media_upload_finish($this->uploadDir.'/'.$this->odtFileName, $destFile, $this->nsName, $mime, @file_exists($destFile), 'rename' ) != $this->nsName ) return $this->_msg( array( 'er_apply_odtFile' ) );
    } else {
      // If not allowed to upload, display a message.
      $this->_msg( 'inf_acl_upload', 0, true );
    }
    # Refresh info about the current page (see doku.php where $INFO is initiate) - Needed for edit or preview "parserPostDisplay" option
    $INFO = pageinfo();
    return true;
  }

  function _parse_image() {
    ### _parse_image : search dokuwiki img markup in $this->result than extract the img file and rename it to easier name ###
    # OUTPUT :
    #   void
    # using _msg to display each img file wont be process successfully

    global $ID;
    $imgs = array();
    if ( preg_match_all( '|{{((?:[^/}]+/)*[^/}]+)/([0-9a-zA-Z]+)(\.[a-z]+)(\?[0-9]+(?:x[0-9]+)?)?}}|', $this->result, $imgs, PREG_SET_ORDER ) ) {
      if ( auth_quickaclcheck( $ID ) < AUTH_UPLOAD ) return $this->_msg( 'er_acl_upload' );
      $this->err['ok'] = array();
      foreach ( $imgs as $key => $value ) {
        set_time_limit(20);
        $this->pictpath = $value[1];
        $pict = $value[2].$value[3];
        $ext  = $value[3];
        $other = $value[4];
        if ( $this->_unzip($this->pictpath.'/'.$pict) ) {
          $newname = noNS($this->pageName).'_Image_'.$key.$ext;
          if ( rename( $this->uploadDir.'/'.$this->pictpath.'/'.$pict, $this->uploadDir.'/'.$this->pictpath.'/'.$newname ) ) {
            $this->result = str_replace( '{{'.$this->pictpath.'/'.$pict.$other.'}}' , '{{'.$newname.$other.'}}' , $this->result );
            $this->file_import[] = $newname;
            if ( $this->debug ) $this->err['ok'][] = $pict.' : '.$newname;
          } else $this->err[$pict] = 'rename';
        } else $this->err[$pict] = 'unzip';
      }
    }
    if ( $this->err ) foreach ( $this->err as $key => $value ) {
      switch ( $key ) {
        case 'ok':
          foreach ( $value as $msg ) $this->_msg( array( 'ok_img', $msg ) );
          break;
        default :
          // $value E ( rename, unzip) => er_img_rename, er_img_unzip
          $this->_msg( array( 'er_img_'.$value, $key ) );
      }
    }
  }

  function _unzip( $entrie ) {
    ### _unzip : extract $entrie file from $this->odtFile to $this->uploadDir using $this->ZIP object instance of ZipArchive Class ###
    # $entrie : string : fullFileName (with the internal path in the archive)
    # OUTPUT :
    #   * true -> extraction ok
    #   * false -> something wrong; using _msg to display what's wrong

    if ( ! $this->ZIP ) return $this->_msg('er_unzip_object');
    if ( ! file_exists( $this->odtFile ) ) return $this->_msg('er_unzip_nofile');
    if ( ! ( $this->ZIP->open( $this->odtFile ) === true ) ) return $this->_msg( 'er_unzip_open' );
    $res = $this->ZIP->extractTo( $this->uploadDir, $entrie );
    $this->ZIP->close();
    if ( ! $res ) return $this->_msg( array( 'er_unzip_error', $entrie ) );
    $this->file_extract[] = $this->uploadDir.'/'.$entrie;
    return $this->_msg( array( 'ok_unzip', $entrie ) );
  }

}
