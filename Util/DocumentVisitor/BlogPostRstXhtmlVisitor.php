<?php

namespace Whitewashing\Util\DocumentVisitor;

class BlogPostRstXhtmlVisitor extends \ezcDocumentRstXhtmlVisitor
{
    public function visit( \ezcDocumentRstDocumentNode $ast )
    {
        $this->ast = $ast;
        $this->preProcessAst( $ast );

        // Reset footnote counters
        foreach ( $this->footnoteCounter as $label => $counter )
        {
            $this->footnoteCounter[$label] = 0;
        }
        reset( $this->footnoteSymbols );

        // Reset duplicate reference counter
        $this->referenceCounter = array();

        $this->document = new \DOMDocument('1.0', 'UTF-8');
        $body = $this->document->createElement( 'div' );
        $this->document->appendChild($body);

        // Visit all childs of the AST root node.
        foreach ( $ast->nodes as $node )
        {
            $this->visitNode( $body, $node );
        }

        // Visit all footnotes at the document body
        foreach ( $this->footnotes as $footnotes )
        {
            ksort( $footnotes );
            $footnoteList = $this->document->createElement( 'ul' );
            $footnoteList->setAttribute( 'class', 'footnotes' );
            $body->appendChild( $footnoteList );

            foreach ( $footnotes as $footnote )
            {
                $this->visitFootnote( $footnoteList, $footnote );
            }
        }

        return $this->document;
    }

    protected function visitSection( \DOMNode $root, \ezcDocumentRstNode $node )
    {
        $header = $this->document->createElement( 'h' . min( 6, ++$this->depth ) );
        $root->appendChild( $header );

        if ( $this->depth >= 6 )
        {
            $header->setAttribute( 'class', 'h' . $this->depth );
        }

        $reference = $this->document->createElement( 'a' );
        $reference->setAttribute( 'name', htmlspecialchars( $node->reference ) );
        $header->appendChild( $reference );

        foreach ( $node->title->nodes as $child )
        {
            $this->visitNode( $header, $child );
        }

        foreach ( $node->nodes as $child )
        {
            $this->visitNode( $root, $child );
        }

        --$this->depth;
    }

    public function visitLiteralBlockNode(\DOMNode $root, \ezcDocumentRstLiteralBlockNodee $node)
    {
        
    }
}