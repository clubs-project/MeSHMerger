<?php
namespace ClubsProject\MeSHMerger;

use Hobnob\XmlStreamReader\Parser;

class MeSHDataCollector
{

    /**
     * Data structure to collect the multilingual information.
     * Key is the ID of the concept.
     * Value is an array of term IDs for this concept.
     * @var string[]
     */
    private $concepts = [];

    /**
     * Array of MeSH terms.
     * Key is the id of the term.
     * Value is a MeSHTerm object.
     * @var MeSHTerm[]
     */
    private $terms = [];

    private $current_descriptor_language = null;
    private $current_concept_id = null;

    public function handleDescriptorRecordSetLanguage(Parser $parser, string $attrValue  ) {
        $this->current_descriptor_language = $attrValue;
        echo("Processing $attrValue data..." . PHP_EOL);
    }

    public function handleConceptUI(Parser $parser, \SimpleXMLElement $node) {
        $identifier = strval($node);
        if (empty($identifier)) {
            echo("Could not get identifier for Concept" . PHP_EOL);
            die(1);
        }
        if (!array_key_exists($identifier, $this->concepts)) {
            $this->concepts[$identifier] = [];
            echo("Added new concept $identifier" . PHP_EOL);
        }
        //update current concept id
        $this->current_concept_id = $identifier;
    }

    public function handleTerm(Parser $parser, \SimpleXMLElement $node) {
        $identifier = strval($node->termui);
        if (empty($identifier)) {
            echo("Could not get identifier for Term" . PHP_EOL);
            die(1);
        }
        //add term identifier to list of terms for this concept
        if (empty($this->current_concept_id)) {
            echo("Tried to add Term $identifier outside of a Concept");
            die(1);
        }
        $this->concepts[$this->current_concept_id] = array_unique(array_merge($this->concepts[$this->current_concept_id], [$identifier]));
        if (!array_key_exists($identifier, $this->terms)) {
            $this->terms[$identifier] = new MeSHTerm($identifier);
            echo("Added new term $identifier from concept $this->current_concept_id" . PHP_EOL);
        }
        $term = $this->terms[$identifier];
        /* @var $term MeSHTerm */
        //handle "normal" term, i.e. non-preferred and permutations
        if ($node->attributes()->conceptpreferredtermyn == "N" && $node->attributes()->ispermutedtermyn == "N") {
            $term->setTerm($node->string);
        }
        //if this is a preferred term, set is as such
        if ($node->attributes()->conceptpreferredtermyn == "Y") {
            $term->setPreferredTerm($node->string);
        }
        //if this is just a permutation of a term, it should have the same term id as its base form and the appropriate attribute set to y
        if ($node->attributes()->ispermutedtermyn == "Y") {
            $term->addPermutedTerm($node->string);
        }
        //try to establish the language of th term. If the start of the termUI matches the current DescriptorRecordSet language,
        //we consider it to be in this language, otherwise English. This is crude, but as there is no explicit language field on each term we have no alternative.
        if (substr($identifier,0,strlen($this->current_descriptor_language)) == $this->current_descriptor_language) {
            $term->setLanguage($this->current_descriptor_language);
        } else {
            $term->setLanguage('eng');
        }
        $this->terms[$identifier] = $term;
    }

    public function toXml(string $filename) {
        $oXMLout = new \XMLWriter();
        $oXMLout->openURI($filename);
        $oXMLout->setIndent(true);
        $oXMLout->setIndentString("\t");
        $oXMLout->startElement("concepts");
        foreach ($this->concepts as $concept_id => $term_ids) {
            $oXMLout->startElement("concept");
            $oXMLout->writeAttribute("id", $concept_id);
            foreach ($term_ids as $term_id) {
                if (!array_key_exists($term_id, $this->terms)) {
                    die("Concepts reference a Term not contained in the term list. This is a serious bug.");
                }
                $term = $this->terms[$term_id];
                $oXMLout->startElement("term");
                $oXMLout->writeAttribute("id", $term->getId());
                $oXMLout->writeAttribute("lang", $term->getLanguage());
                if (!is_null($term->getPreferredTerm())) {
                    $oXMLout->writeElement("preferred", $term->getPreferredTerm());
                }
                if (!is_null($term->getTerm())) {
                    $oXMLout->writeElement("normal", $term->getTerm());
                }
                if (!empty($term->getPermutedTerms())) {
                    foreach ($term->getPermutedTerms() as $permuted_term) {
                        $oXMLout->writeElement("permuted", $permuted_term);
                    }
                }
                $oXMLout->endElement();//term
            }
            $oXMLout->endElement(); //concept
        }
        $oXMLout->endElement(); //concepts
    }

}