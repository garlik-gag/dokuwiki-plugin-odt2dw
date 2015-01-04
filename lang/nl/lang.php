<?php
/**
 * Dutch language file
 *
 * @author Mark Prins <mprins@users.sf.net>
 * @author Greg BELLAMY <garlik.crx@gmail.com> [Gag]
 */

$lang['formLegend']  = "Importeer een odt bestand";
$lang['formIntroMessage'] = "Je kunt deze pagina aanmaken door **een odt bestand van je computer te importeren**.\\\
Hoe meer het bestand de de regels volgt hoe beter het resultaat.";
$lang['formPageExistMessage'] = "**WAARSCHUWING!!! Deze pagina bestaat al. Als je een nieuwe upload wordt de huidige gearchiveerd.**

";

$lang['parserSummary'] = "Bestand gemaakt met odt2dw plug-in";
$lang['parserOriginalFile'] = "Oorspronkelijke bestand";

$lang['xsl_subtable_message'] = "FIXME : Er is een complexe tabel gevonden. De opmaak moet waarschijnlijk met de hand worden aangepast";

$lang['ok_infoplug-in']  = "Geïnstalleerde plug-in";
$lang['ok_img']         = "Afbeelding correct verwerkt";
$lang['ok_unzip']       = "Verwerking loopt";

$lang['inf_acl_upload'] = "Je hebt onvoldoende toegangsrechten om het originele bestand op te slaan in de wiki, het bestand wordt derhalve niet opgeslagen.";
$lang['inf_xslt_lang'] = "Geen translatie beschikbaar in stylesheet. De verstek waarde is toegepast.";
$lang['inf_xslt_param'] = "Het was onmogelijk de parameter in te stellen op de xsltProcessor. De verstek waarde is toegepast";

$lang['er_acl_create'] = "Toegangs nivo te laag om hier een pagina aan te maken";
$lang['er_acl_edit'] = "Toegangs nivo te laag om hier een pagina aan te passen";
$lang['er_acl_upload'] = "Toegangs nivo te laag om hier media op te slaan";
$lang['er_apply'] = "Fout tijdens opslaan ontleedde gegevens";
$lang['er_apply_content'] = "De inhoud kon niet worden opgeslagen";
$lang['er_apply_img'] = "De media kon niet worden opgeslagen";
$lang['er_apply_odtFile'] = "Het originele odt bestand kon niet worden opgeslagen";
$lang['er_checkUploadResult'] = "Er is iets mis met het import bestand";
$lang['er_class_domDocument'] = "Fatale fout : het DOMDocument object kon niet worden geïnitialiseerd";
$lang['er_class_xsltProcessor'] = "Fatale fout : het XsltProcessor object kon niet worden geïnitialiseerd";
$lang['er_class_zipArchive'] = "Fatale fout : het ZipArchive object kon niet worden geïnitialiseerd";
$lang['er_id'] = "verkeerd gebruik van deze plug-in. _odt2dw moet worden aangeroepen met namepage (\$ID)";
$lang['er_img_rename'] = "Het afbeeldingbestand kon niet worden hernoemd";
$lang['er_img_unzip'] = "Het afbeeldingbestand kon niet worden uitgepakt";
$lang['er_invalidRoot'] = "RootNode van het geuploade document is fout. Het bestand is mogelijk corrupt";
$lang['er_loadXml'] = "Het was niet mogelijk de inhoud van het document te laden. Het bestand is mogelijk corrupt";
$lang['er_loadXsl'] = "Het XSL bestand heeft een fout (xml syntax). Controleer en corrigeer het bestand.";
$lang['er_logFile'] = "Er kon niet in de logFile worden geschreven";
$lang['er_msg_nomessage'] = "Verkeerd gebruik van deze plug-in. _msg moet worden aangeroepen met een bericht";
$lang['er_odtFile_format'] = "Verkeerd bestand geupload. Het bestand is geen odt bestand";
$lang['er_odtFile_getFromDownload'] = "Het upload betstand kon niet gevonden worden.";
$lang['er_odtFile_miss'] = "Verkeerd gebruik van deze plug-in. _odt2dw moet worden aangeroepen met een importeer bestand formulier";
$lang['er_odtFile_tmpDir'] = "De tijdelijke directory kon niet worden gemaakt.";
$lang['er_odtFile_unzip'] = "Het bestand kon niet worden uitgepakt";
$lang['er_odtFile_upload'] = "Er ging iets mis de bestandsupload";
$lang['er_pg_dir'] = "De tijdelijke directory kon niet worden verwijderd";
$lang['er_pg_file'] = "Het tijdelijke bestand kon niet worden verwijderd";
$lang['er_transform'] = "Er ging iets fout met de ontleder tijdens transformatie van het odtFile naar dokuwiki opmaak";
$lang['er_unzip_error'] = "Het bestand kon niet worden uitgepakt";
$lang['er_unzip_nofile'] = "Geen zip archief gevonden";
$lang['er_unzip_object'] = "De zip archief manager is niet geladen";
$lang['er_unzip_open'] = "Het zip archief kon niet worden geopend";
$lang['er_xslFile_exist'] = "Het bestand dat als **parserXslFile** is ingesteld bestaat niet of kon niet worden gelezen";
$lang['er_xslFile_isfile'] = "Het bestand dat als **parserXslFile** is ingesteld is geen bestand";
$lang['er_xslFile_notset'] = "Verkeerde waarde voor **parserXslFile** instelling ";
$lang['er_xsltProc'] = "Fatale fout : er ging iets fout met instellen van de xsltProcessor";
$lang['er_xslt_invalid'] = "Het XSL bestand heeft een fout (xsl syntax). Controleer en corrigeer het bestand.";

//Setup VIM: ex: et ts=2 enc=utf-8 :
