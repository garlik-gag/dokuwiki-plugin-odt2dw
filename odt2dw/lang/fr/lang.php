<?php
/**
 * French language file
 *
 * @author Greg BELLAMY <garlik.crx@gmail.com> [Gag]
 */

$lang['formLegend']  = "Import d'un fichier odt";
$lang['formIntroMessage'] = "Vous pouvez créer cette page en y **important un fichier openOffice Writer** présent sur votre ordinateur.\\\
Un document confectionné en respectant au plus près les bonnes pratiques du traitement de texte garantira une bonne restitution dans dokuwiki.";
$lang['formPageExistMessage'] = "**ATTENTION!!! Cette page existe déjà. Si vous validez le formulaire de téléversement, la page actuelle sera archivée et la page chargée prendra sa place.**

";

$lang['parserSummary'] = "Fichier généré par le plugin odt2dw à partir du fichier ";
$lang['parserOriginalFile'] = "Version originale";

$lang['xsl_subtable_message'] = "FIXME : Tableau complexe détecté. Le formatage doit vraisemblablement être corrigé.";

$lang['ok_infoPlugin']  = "Plugin installé";
$lang['ok_img']         = "Image correctement traitée";
$lang['ok_unzip']       = "Décompression effectuée avec succés";

$lang['inf_xslt_lang'] = "Traduction inexistante pour le message utilisé dans la feuille xsl. Valeur par défaut utilisée";
$lang['inf_xslt_param'] = "Impossible d'ajuster le paramètre du processeur xsl. Valeur par défaut utilisée";

$lang['er_acl_create'] = "Vous n'avez pas de droits suffisants pour créer une page ici";
$lang['er_acl_edit'] = "Vous n'avez pas de droits suffisants pour modifier cette page";
$lang['er_acl_upload'] = "Vous n'avez pas de droits suffisants pour ajouter des fichiers joints";
$lang['er_apply'] = "Erreur dans l'enregistrement du résultat";
$lang['er_apply_content'] = "Impossible d'enregistrer le contenu de la page";
$lang['er_apply_img'] = "Impossible d'intégrer au wiki l'image incluse dans le fichier source";
$lang['er_apply_odtFile'] = "Impossible d'intégrer au wiki le fichier source comme document joint";
$lang['er_checkUploadResult'] = "Une anomalie a été détectée par la fonction de contrôle du fichier téléchargé";
$lang['er_class_domDocument'] = "Erreur système : Impossible d'initialiser un objet DOMDocument";
$lang['er_class_xsltProcessor'] = "Erreur système : Impossible d'initialiser un objet xsltProcessor";
$lang['er_class_zipArchive'] = "Erreur système : Impossible d'initialiser un objet ZipArchive";
$lang['er_id'] = "L'utilisation du plugin n'est pas conforme. Une page (\$ID) doit être définie";
$lang['er_img_rename'] = "Impossible de renommer l'image";
$lang['er_img_unzip'] = "Impossible d'extraire l'image de l'archive";
$lang['er_invalidRoot'] = "Le document chargé présente un noeud racine erroné. Il s'agit sans doute d'un document corrompu";
$lang['er_loadXml'] = "Impossible de charger le contenu du document OpenOffice. Il est probablement corrompu";
$lang['er_loadXsl'] = "Le fichier xsl paramétré est incompatible (au niveau xml), veuillez vous assurer que le fichier xsl est conforme";
$lang['er_logFile'] = "Impossible d'ouvrir le fichier de log spécifié dans la configuration";
$lang['er_msg_nomessage'] = "La méthode _msg est appelé sans message à transmettre";
$lang['er_odtFile_format'] = "Le fichier chargé n'est pas un fichier openOffice";
$lang['er_odtFile_getFromDownload'] = "Impossible de récupérer le fichier téléchargé";
$lang['er_odtFile_miss'] = "Transformateur appelé sans fichier OpenOffice Writer. Il s'agit d'une utilisation non conforme du plugin";
$lang['er_odtFile_tmpDir'] = "Impossible de créer le dossier temporaire indispensable au traitement du fichier";
$lang['er_odtFile_unzip'] = "Impossible d'extraire le fichier";
$lang['er_odtFile_upload'] = "Une erreur est survenue dans le chargement du fichier";
$lang['er_pg_dir'] = "Impossible de supprimer le répertoire";
$lang['er_pg_file'] = "Impossible de supprimer le fichier";
$lang['er_transform'] = "Une erreur est survenue lors de la transcription du document OpenOffice vers la syntaxe Dokuwiki.";
$lang['er_unzip_error'] = "Impossible d'extraire le fichier";
$lang['er_unzip_nofile'] = "Fichier d'archive non trouvé";
$lang['er_unzip_object'] = "Gestionnaire d'archive non chargé";
$lang['er_unzip_open'] = "Impossible d'ouvrir l'archive";
$lang['er_xslFile_exist'] = "Le fichier défini pour **parserXslFile** n'existe pas ou n'est pas joignable";
$lang['er_xslFile_isfile'] = "Le paramètre **parserXslFile** n'est pas le chemin d'un fichier";
$lang['er_xslFile_notset'] = "Paramètre **parserXslFile** mal défini dans la configuration";
$lang['er_xsltProc'] = "Une erreur critique s'est produite dans le paramétrage du processeur xsl";
$lang['er_xslt_invalid'] = "Le fichier xsl paramétré est invalide (au niveau xsl), veuillez vous assurer que le fichier xsl est conforme";

//Setup VIM: ex: et ts=2 enc=utf-8 :
