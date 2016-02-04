<?php
/**
* German language file
*
* @author Peter Ziegler <>
* @author Greg BELLAMY <garlik.crx@gmail.com> [Gag]
*/

$lang['formLegend'] = "Import einer odt-Datei";
$lang['formIntroMessage'] = "Diese Seite kann angelegt werden **importieren einer odt-Datei** von diesem Computer.\\\
Das Resultat wird umso besser, je besser die Textverarbeitungsregeln eingehalten werden.";
$lang['formPageExistMessage'] = "**WARNUNG!!! Diese Seite exisitert bereits. Wenn Sie eine odt-Datei hochladen, wird die aktuelle Seite archiviert.**

";
$lang['import_odt_button']     = 'ODT importieren';

$lang['parserSummary'] = "Datei wurde durch odt2dw-Plugin erzeugt";
$lang['parserOriginalFile'] = "Originaldatei";

$lang['xsl_subtable_message'] = "FIXME: Eine komplexe Tabelle wurde erkannt. Evtl. sind manuelle Korrekturen nötig.";

$lang['ok_infoPlugin'] = "Installiertes Plugin";
$lang['ok_img'] = "Bild korrekt bearbeitet";
$lang['ok_unzip'] = "Entpacken korrekt bearbeitet";

$lang['inf_acl_upload'] = "Unzureichende Rechte, um die Originaldatei im Wiki zu speichern. Die Datei wurde am Server nicht gespeichert.";
$lang['inf_xslt_lang'] = "Keine Übersetzung vorhanden für xsl-Stylesheet.  Standardwerte werden angewendet";
$lang['inf_xslt_param'] = "Kann Parameter für xsltProcessor nicht setzen. Standardwerte werden angewendet";

$lang['er_acl_create'] = "Unzureichende Rechte, um eine Seite zu erstellen";
$lang['er_acl_edit'] = "Unzureichende Rechte, um eine Seite zu editieren";
$lang['er_acl_upload'] = "Unzureichende Rechte, um einen Anhang zu importieren";
$lang['er_apply'] = "Fehler beim schreiben der gelesenen Daten";
$lang['er_apply_content'] = "Der Inhalt konnte nicht gespeichert werden";
$lang['er_apply_img'] = "Der Anhang konnte nicht gespeichert werden";
$lang['er_apply_odtFile'] = "Die odt-Datei konnte nicht gespeichert werden";
$lang['er_checkUploadResult'] = "Allgemeiner Fehler bei der hochgeladenen Datei";
$lang['er_class_domDocument'] = "Kritischer Fehler: DOMDocument-Objekt konnte nicht initialisiert werden";
$lang['er_class_xsltProcessor'] = "Kritischer Fehler: XsltProcessor-Objekt konnte nicht initialisiert werden";
$lang['er_class_zipArchive'] = "Kritischer Fehler: ZipArchive-Objekt konnte nicht initialisiert werden";
$lang['er_id'] = "Falsche Verwendung des Plugins. _odt2dw muss in einem definierten Namespace aufgerufen werden (\$ID)";
$lang['er_img_rename'] = "Konnte Bilddatei nicht umbenennen";
$lang['er_img_unzip'] = "Konnte Bilddatei nicht extrahieren";
$lang['er_invalidRoot'] = "RootNode des hochgeladenen Dokuments ist fehlerhaft. Die Date ist evtl. korrupt";
$lang['er_loadXml'] = "Konnte den Inhalt des Dokuments nicht laden. Die Date ist evtl. korrupt";
$lang['er_loadXsl'] = "Die Xsl-Datei ist falsch (xml Syntax). Bitte überprüfen und korrigieren";
$lang['er_logFile'] = "Konnte nicht in die Log-Datei schreiben";
$lang['er_msg_nomessage'] = "Falsche Verwendung des Plugins. _msg muss mit einer Nachricht aufgerufen werden";
$lang['er_odtFile_format'] = "Falscher Dateityp. Die Datei ist keine odt-Datei";
$lang['er_odtFile_getFromDownload'] = "Kann die hochzuladende Datei nicht erreichen";
$lang['er_odtFile_miss'] = "Falsche Verwendung des Plugins. _odt2dw muss von einem 'Datei-hochladen-Formular' aufgerufen werden";
$lang['er_odtFile_tmpDir'] = "Kann temporäres Arbeitsverzeichnis nicht erzeugen.";
$lang['er_odtFile_unzip'] = "Kann Datei nicht extrahieren";
$lang['er_odtFile_upload'] = "Allgemeiner Fehler beim Datei-Upload";
$lang['er_pg_dir'] = "Kann das temporäre Arbeitsverzeichnis nicht entfernen";
$lang['er_pg_file'] = "Kann die temporäre Arbeitsdatei nicht entfernen";
$lang['er_transform'] = "Allgemeiner Fehler beim umwandeln der odt-Datei in das Wiki";
$lang['er_unzip_error'] = "Kann Datei nicht extrahieren";
$lang['er_unzip_nofile'] = "Keine Archivdatei gefunden";
$lang['er_unzip_object'] = "Kann den Archivemanager nicht laden";
$lang['er_unzip_open'] = "Kann die Archivdatei nicht öffnen";
$lang['er_xslFile_exist'] = "Die Dateimenge **parserXslFile** exisitert nicht oder kann nicht gelesen werden";
$lang['er_xslFile_isfile'] = "Die Dateimenge **parserXslFile** ist keine Datei";
$lang['er_xslFile_notset'] = "Falscher Wert für **parserXslFile** Parameter ";
$lang['er_xsltProc'] = "Kritischer Fehler : Der xsltProcessorkonnte nicht angewendet werden";
$lang['er_xslt_invalid'] = "Die Xsl-Datei ist fehlerhaft (xsl Syntax). Bitte überprüfen und korrigieren";

//Setup VIM: ex: et ts=2 enc=utf-8 :
