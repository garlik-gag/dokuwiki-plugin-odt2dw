<?xml version="1.0" encoding="UTF-8"?>

<!--
### xsl stylesheet used to convert content.xml file from an odtFile to dokuwiki syntax
@license    GPL 2 (http://www.gnu.org/licenses/gpl.html)

@author Greg BELLAMY <garlik.crx@gmail.com> [Gag]

Version 0.2 Alpha 2012-02-15
  * Adding this comment
  * Description of parsed markups

Howto :
  * Support of some dokuwiki plugin will be set by parameters at the begin of this stylesheet

Supported formatting :
  * headline lvl 1 to 5
  * paragraph
  * char :
    * underline
    * bold
    * italic
  * table (simple one with few fusionned cell, fusion table display the best with an error message)
  * pictures
  * list

Supported plugin :
  * numberedheading : adding numbering to titles

Advice :
  * The output is directly dependent on the quality of making the input file. The Use of styles is recommanded.

ToDo (next feature):
  * Add an language support by setting parameters like plugin support

Source :
  * This stylesheet is an adaptation of the sheet available at http://wiki.lm7.fr/ to translating a odt file format to mediawiki

###
-->

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:office="http://openoffice.org/2000/office" xmlns:style="http://openoffice.org/2000/style" xmlns:text="http://openoffice.org/2000/text" xmlns:table="http://openoffice.org/2000/table" xmlns:draw="http://openoffice.org/2000/drawing" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:number="http://openoffice.org/2000/datastyle" xmlns:svg="http://www.w3.org/2000/svg" xmlns:chart="http://openoffice.org/2000/chart" xmlns:dr3d="http://openoffice.org/2000/dr3d" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="http://openoffice.org/2000/form" xmlns:script="http://openoffice.org/2000/script" xmlns:java="http://xml.apache.org/xslt/java">
  <xsl:output
    method="text"
    encoding="utf-8"
  />
  <xsl:param
    name="numberedheadings"
    value="0"
  />
  <xsl:param
    name="subtable_message">
    <xsl:text>FIXME : Subtable detected. Formatting must be corrected.</xsl:text>
  </xsl:param>
  <!-- Wont work <xsl:param name="cmtopx" value="37.795275591"/>-->
  <!-- Get toc sources styles to check paragraphe tag with header style in text:p template -->
  <xsl:variable name="title_style" value="//text:index-source-styles"/>
  <xsl:strip-space elements="*"/>
  <!-- Catch the non-content document sections -->
  <xsl:template match="/XML"/>
  <xsl:template match="/office:document-content/office:meta"/>
  <xsl:template match="/office:document-content/office:settings"/>
  <xsl:template match="/office:document-content/office:script"/>
  <xsl:template match="/office:document-content/office:font-face-decls"/>
  <xsl:template match="/office:document-content/office:document-styles"/>
  <xsl:template match="/office:document-content/office:automatic-styles"/>
  <xsl:template match="/office:document-content/office:styles"/>
  <xsl:template match="/office:document-content/office:master-styles"/>
  <xsl:template match="/office:document-content/office:body/office:text/office:forms"/>
  <xsl:template match="/office:document-content/office:body/office:text/text:tracked-changes"/>
  <xsl:template match="/office:document-content/office:body/office:text/sequence-decls"/>
  <xsl:template match="//text:table-of-content"/>

<!-- Line-break formatting - This is not a paragraph end -->
  <xsl:template match="//text:line-break">
    <xsl:text disable-output-escaping="yes">\\ </xsl:text>
  </xsl:template>

<!-- Headline formatting -->
  <xsl:template name="titre" match="//text:h">
    <xsl:param name="level" select="@text:outline-level"/>
    <xsl:variable name="chaine"><xsl:call-template name="boucle">
      <xsl:with-param name="debut" select="1" />
      <xsl:with-param name="fin" select="7 - $level" />
      <xsl:with-param name="texte" select="'='" />
    </xsl:call-template></xsl:variable>

    <xsl:value-of select="$chaine"/>
    <xsl:text disable-output-escaping="yes"> </xsl:text>
    <xsl:if test="$numberedheadings = '1'"><xsl:text disable-output-escaping="yes">- </xsl:text></xsl:if>
    <xsl:apply-templates/>
    <xsl:text disable-output-escaping="yes"> </xsl:text>
    <xsl:value-of select="$chaine"/>
    <xsl:text disable-output-escaping="yes">

</xsl:text>
  </xsl:template>

  <!-- Text blocks
  Greg - Transcription des paragraphes
  Déroulé :
  Mise en variables des éléments (style, contenu)
  Si le text n'est pas vide
    Appel de la mise en forme ouvrante
    Intégration de contenu
    Appel de la mise en forme sortante
    Ajout de retour chariot selon les règles de syntaxe Dokuwiki (le première qui match a gagnée)
    Si cellule d'un tableau avec plusieurs paragraphes : 1 Retour chariot
    Si cellule d'un tableau : Pas de retour chariot
    Si élément d'une liste suivi d'un élément : 1 Retour chariot
    Si dernier élément d'une liste : 2 Retour chariot
  Si le text est vide
    On ne fait rien
  -->
  <xsl:template match="//text:p">
    <xsl:if test=".!='' or count(*) &gt; 0">
      <xsl:variable name="p_style" select="@text:style-name"/>
      <xsl:variable name="real_style" select="//style:style[@style:name=$p_style]/@style:parent-style-name"/>
      <xsl:choose>
        <!-- Check if it is a wrong defined header (with toc attribute compare), then call header template, otherwise it s a paragraphe -->
        <xsl:when test="not (ancestor::table:table or ancestor::text:list) and $title_style and $title_style/text:index-source-style[@text:style-name = $real_style]">
          <xsl:call-template name="titre">
            <xsl:with-param name="level" select="$title_style[@text:style-name = $real_style]/../@text:outline-level"/>
          </xsl:call-template>
        </xsl:when>
        <xsl:when test="1 = 0"></xsl:when>
        <xsl:otherwise>
          <!-- Mise en forme -->
          <xsl:call-template name="p_render"/>
          <!-- Aplication des retours chariots selon le contexte -->
          <xsl:choose>
            <!-- we shouldn't add a newline for elements inside a table -->
            <xsl:when test="ancestor::table:table-cell and not(following-sibling::text:p)"/>
            <!-- but we would want a new between two text:p in a cell -->
            <xsl:when test="ancestor::table:table-cell and following-sibling::text:p">
              <xsl:text disable-output-escaping="yes">\\ </xsl:text>
            </xsl:when>
            <!-- we do want a single newline at the end of a list item
            fixme : wont work correctly if a list containt §, something like liste & § -->
            <xsl:when test="ancestor::text:list-item and following-sibling::text:p">
              <xsl:text disable-output-escaping="yes">\\ </xsl:text>
            </xsl:when>
            <xsl:when test="ancestor::text:list-item">
              <xsl:text disable-output-escaping="yes">
</xsl:text>
              </xsl:when>
            <!-- and double at the end of the list
            FIXME Trouver la bonne liaison sinon, l'intégrer ailleurs (sortie de liste par exemple)-->
            <!--<xsl:when test="preceding-sibling::text:list"><xsl:text disable-output-escaping="yes">

    </xsl:text></xsl:when>-->
            <xsl:when test="ancestor::text:align='center'">
              <xsl:text disable-output-escaping="yes">
              </xsl:text>
            </xsl:when>

            <!-- Si note de bas de page, pas de retour chariot -->
            <xsl:when test="ancestor::text:note-body"></xsl:when>
            <xsl:otherwise>
              <xsl:text disable-output-escaping="yes">

</xsl:text>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:if>
  </xsl:template>


  <!-- Footnote -->
  <xsl:template match="//text:note-body">
    <xsl:text disable-output-escaping="yes">(( </xsl:text>
    <xsl:apply-templates/>
    <xsl:text disable-output-escaping="yes"> ))</xsl:text>
  </xsl:template>
  <xsl:template match="//text:note-citation"/>

  <xsl:template match="//text:a">
    <xsl:variable name="link">
      <xsl:value-of select="@xlink:href"/>
    </xsl:variable>
    <xsl:variable name="link-text">
      <xsl:value-of select="."/>
    </xsl:variable>
      <xsl:text disable-output-escaping="yes">[[</xsl:text>
      <xsl:value-of select="$link"/>
      <xsl:text disable-output-escaping="yes">|</xsl:text>
      <xsl:value-of select="."/>
      <xsl:text disable-output-escaping="yes">]]</xsl:text>
  </xsl:template>

  <xsl:template match="//text:list-item">
    <xsl:variable name="level">
      <xsl:value-of select="count(ancestor::text:list)"/>
    </xsl:variable>

    <xsl:choose>
      <!-- Si dernier élément et vide, n'affiche rien -->
      <xsl:when test="not(following-sibling::text:list-item) and not(text:p | text:a)"></xsl:when>
      <xsl:otherwise>
      <!-- Sinon, compte le nombre de listes imbriquées et affiche 2 espaces / niveau -->

        <xsl:call-template name="boucle">
          <xsl:with-param name="debut" select="1" />
          <xsl:with-param name="fin" select="$level" />
          <xsl:with-param name="texte" select="'  '" />
        </xsl:call-template>

        <!-- Puis affiche le symbole de liste adapté (ordonné et non ordonné) -->
        <xsl:variable name="stylename" select="./*[1]/@text:style-name"/>
        <xsl:variable name="listename" select="//style:style[@style:name=$stylename]/@style:list-style-name"/>

        <xsl:choose>
          <xsl:when test="parent::text:list and //text:list-style[@style:name=$listename]/text:list-level-style-bullet[@text:level=$level]"><xsl:text disable-output-escaping="yes">* </xsl:text></xsl:when>
          <xsl:otherwise><xsl:text disable-output-escaping="yes">- </xsl:text></xsl:otherwise>
        </xsl:choose>

      </xsl:otherwise>
    </xsl:choose>
    <!-- Enfin, affiche le contenu -->
    <xsl:apply-templates/>
  </xsl:template>

  <!-- Tables -->
  <xsl:template match="//table:table">
    <!-- Greg - Quelle utilitée ?
    Element mediawiki supprimés
    -->
    <xsl:choose>
      <xsl:when test="@table:is-sub-table='true'"><xsl:call-template name="subtable"/></xsl:when>
      <xsl:otherwise><xsl:apply-templates/></xsl:otherwise>
    </xsl:choose>
    <!-- New line under a table to dissociate following table -->
    <xsl:text disable-output-escaping="yes">
</xsl:text>
  </xsl:template>

  <xsl:template name="subtable" match="//table:sub-table">
    <!-- Subtable are hard to transform ! help needed ! -->
    <xsl:if test="descendant::text:p">
      <xsl:value-of select="$subtable_message"/>
      <xsl:apply-templates/>
    </xsl:if>
  </xsl:template>

  <!-- Table rows
  Greg - Traitement d'une ligne
  Une ligne est composée de cellule
  Déroulé :
   Traitement des cellules
   Ajout d'un caractère de fin de ligne '|\n'
  -->
  <xsl:template match="//table:table-row">
    <xsl:if test="descendant::text:*[text() != ''] or descendant::draw:image">
      <xsl:apply-templates/>
      <xsl:text disable-output-escaping="yes">|
</xsl:text>
    </xsl:if>
  </xsl:template>

  <!-- Table cells
  Greg - Traitement d'une cellule
  Déroulé :
   Si cellule dans la zone en-tête
     affichage en mode en-tête
   sinon
     affichage en mode normal
  Traitement des fusions verticales de cellules FIXME
  Affichage du contenu

  Opérationnel :
    Export
    * tableau simple,
    * tableau contenant des colonnes fusionnées
    * tableau contenant des fusions de 2 lignes maximum (au delà, OOo créée des sous-tables complexes

  ToDO : Analyser la syntaxe des tableaux ayant plus de 2 lignes fusionnées.
  -->
  <xsl:template match="//table:table//table:table-row/table:table-cell">
    <xsl:choose>
    <xsl:when test="ancestor::table:table-header-rows">
    <xsl:text disable-output-escaping="yes">^</xsl:text>
    </xsl:when>
    <xsl:otherwise>
    <xsl:text disable-output-escaping="yes">|</xsl:text>
    </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="not (*[text() != ''] )"><xsl:text disable-output-escaping="yes"> </xsl:text></xsl:if>
    <xsl:apply-templates/>
  </xsl:template>

  <!-- Handles horizontally merged cells
  Traitement des cellules fusionnées
  -->
  <xsl:template match="//table:table//table:table-row/table:covered-table-cell">
    <!-- Récupération de la position du noeud précédent -->
    <xsl:variable name="mypos" select="position()"/>
    <xsl:variable name="prevsib" select="../*[position() &lt; $mypos]"/>
    <xsl:choose>
      <xsl:when test="not($prevsib[name()= 'table:table-cell'])">
        <xsl:text disable-output-escaping="yes">| ::: </xsl:text>
      </xsl:when>
      <xsl:otherwise>
        <xsl:for-each select="$prevsib">
          <xsl:if test="name() = 'table:table-cell' and not(following-sibling::table:table-cell)">
            <xsl:choose>
              <xsl:when test="$mypos &gt; 1 and @table:number-columns-spanned and position() + @table:number-columns-spanned &gt; $mypos">
                <xsl:choose>
                  <xsl:when test="ancestor::table:table-header-rows">
                    <xsl:text disable-output-escaping="yes">^</xsl:text>
                  </xsl:when>
                  <xsl:otherwise>
                    <xsl:text disable-output-escaping="yes">|</xsl:text>
                  </xsl:otherwise>
                </xsl:choose>
              </xsl:when>
              <xsl:otherwise>
                <xsl:text disable-output-escaping="yes">| ::: </xsl:text>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:if>
        </xsl:for-each>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match="//draw:frame">
    <xsl:param name="cmtopx" value="37.795275591"/>
    <xsl:text disable-output-escaping="yes" >{{</xsl:text>
    <xsl:value-of select="draw:image/@xlink:href"/>
    <xsl:if test="not (@svg:width = '' or @svg:height = '')">
      <xsl:choose><!-- check cm, px, in, etc... -->
        <xsl:when test="contains( @svg:width,'cm' )">?<xsl:value-of select="floor(number(substring-before(@svg:width,'cm')) * 37.795275591)"/><!--x<xsl:value-of select="floor(number(substring-before(@svg:height,'cm')) * 37.795275591)"/>--></xsl:when>
        <xsl:otherwise/>
      </xsl:choose>
    </xsl:if>
    <xsl:text disable-output-escaping="yes" >}}</xsl:text>
  </xsl:template>

  <xsl:template match="//draw:image">
    <!--<xsl:text disable-output-escaping="yes" >{{</xsl:text>
    <xsl:value-of select="substring-after(@xlink:href,'/')"/>
    <xsl:text disable-output-escaping="yes" >}}</xsl:text>-->
  </xsl:template>

  <!-- Mise en forme des balises span et p
  Greg - Transcription des mise en forme en ligne
  Déroulé :
  Mise en variables des éléments (style, contenu)
  Si le text n'est pas vide
    Appel de la mise en forme ouvrante
    Intégration de contenu
    Appel de la mise en forme sortante
  Si le text est vide
    On ne fait rien
   -->
  <xsl:template name="p_render" match="//text:span">
    <xsl:variable name="style">
      <xsl:value-of select="@text:style-name"/>
    </xsl:variable>
    <xsl:variable name="italic"><xsl:if test="//office:automatic-styles/style:style[@style:name=$style]/style:text-properties/@fo:font-style = 'italic'">//</xsl:if></xsl:variable>
    <xsl:variable name="bold"><xsl:if test="//office:automatic-styles/style:style[@style:name=$style]/style:text-properties/@fo:font-weight = 'bold'">**</xsl:if></xsl:variable>
    <xsl:variable name="underline"><xsl:if test="//office:automatic-styles/style:style[@style:name=$style]/style:text-properties/@style:text-underline-style = 'solid'">__</xsl:if></xsl:variable>
    <xsl:variable name="centre"><xsl:if test="//office:automatic-styles/style:style[@style:name=$style]/style:text-properties/@fo:text-align">&lt;WRAP centeralign&gt;</xsl:if></xsl:variable>
    <xsl:variable name="indice"><xsl:if test="contains(//office:automatic-styles/style:style[@style:name=$style]/style:text-properties/@style:text-position,'sub')">&lt;sub&gt;</xsl:if></xsl:variable>
    <xsl:variable name="exposant"><xsl:if test="contains(//office:automatic-styles/style:style[@style:name=$style]/style:text-properties/@style:text-position,'sup')">&lt;sup&gt;</xsl:if></xsl:variable>
    <xsl:variable name="barre"><xsl:if test="contains(//office:automatic-styles/style:style[@style:name=$style]/style:text-properties/@style:text-crossing-out,'line')">&lt;del&gt;</xsl:if></xsl:variable>
    <xsl:variable name="text" select="."/>
    <!-- Si le paragraphe contient du texte ou d'autres éléments -->
    <xsl:if test="$text!='' or count(*) &gt; 0">
      <!-- Application des mises en formes -->
      <!-- Ouverture -->
      <xsl:value-of select="$italic"/><xsl:value-of select="$bold"/><xsl:value-of select="$underline"/><xsl:value-of select="$centre"/><xsl:value-of select="$indice"/><xsl:value-of select="$exposant"/><xsl:value-of select="$barre"/>

      <xsl:apply-templates/>

      <!-- Fermeture -->
      <xsl:if test="$barre!=''">&lt;/del&gt;</xsl:if><xsl:if test="$exposant!=''">&lt;/sup&gt;</xsl:if><xsl:if test="$indice!=''">&lt;/sub&gt;</xsl:if><xsl:if test="$centre!=''">&lt;/WRAP&gt;</xsl:if><xsl:value-of select="$underline"/><xsl:value-of select="$bold"/><xsl:value-of select="$italic"/>
      <!-- Fin application des mises en formes -->
    </xsl:if>
  </xsl:template>

  <!-- Display spacing #16 -->
  <xsl:template match="//text:s">
    <xsl:text disable-output-escaping="yes"> </xsl:text>
  </xsl:template>


  <!-- Boucle affichant une suite de n éléments identiques où n=fin-debut -->
  <xsl:template name="boucle">
    <xsl:param name="debut" select="0" />
    <xsl:param name="fin" select="0" />
    <xsl:param name="texte" select="'#'" />
    <xsl:value-of select="$texte"/>
    <xsl:if test="$debut &lt; $fin">
    <xsl:call-template name="boucle">
      <xsl:with-param name="debut" select="($debut)+1" />
      <xsl:with-param name="fin" select="$fin" />
      <xsl:with-param name="texte" select="$texte" />
    </xsl:call-template>
    </xsl:if></xsl:template>

</xsl:stylesheet>
