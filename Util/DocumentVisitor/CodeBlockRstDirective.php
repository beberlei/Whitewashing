<?php

namespace Whitewashing\Util\DocumentVisitor;

class CodeBlockRstDirective extends \ezcDocumentRstDirective implements \ezcDocumentRstXhtmlDirective
{
    protected function getProgramingLanguage()
    {
        return (strlen(trim($this->node->parameters)) == 0) ? "php" : $this->node->parameters;
    }

    protected function getCode()
    {
        $code = "";
        foreach ($this->node->nodes AS $literal) {
            $code .= $literal->token->content;
        }

        return trim($code);
    }

    public function toDocbook(\DOMDocument $document, \DOMElement $root)
    {
        $programListing = $document->createElement('programlisting');
        $programListing->setAttribute('language', $this->getProgramingLanguage());

        $cdata = $document->createCDATASection($this->getCode());
        $programListing->appendChild($cdata);

        $root->appendChild($programListing);
    }

    /**
     * Transform directive to HTML
     *
     * Create a XHTML structure at the directives position in the document.
     *
     * @param \DOMDocument $document
     * @param \DOMElement $root
     * @return void
     */
    public function toXhtml( \DOMDocument $document, \DOMElement $root )
    {
        $geshi = new \GeSHi($this->getCode(), $this->getProgramingLanguage());
        $geshi->enable_classes();
        $geshi->set_header_type(\GESHI_HEADER_PRE);
        $codePart = $geshi->parse_code();

        $root->appendChild($document->importNode($this->xhtmlToDomNode($codePart), true));
    }

    /**
     * @link http://stackoverflow.com/questions/4062792/domdocumentvalidate-problem
     * @link http://stackoverflow.com/questions/4081090/how-to-import-xml-string-in-a-php-domdocument
     * @param string $xhtml
     * @return string
     */
    private function xhtmlToDomNode($xhtml) {
        $dom = new \DOMDocument();
        $dom->loadHtml("<html><body>".$xhtml."</body></html>");
        return $dom->getElementsByTagName("pre")->item(0);
    }

}