<?php

namespace ClubsProject\MeSHMerger;

use Hobnob\XmlStreamReader\Parser;

class MeSHDataCollector {

    /**
     * Data structure to collect the multilingual information.
     * Key is the ID of the descriptor.
     * Value is a MeSHDescriptor with this ID.
     * @var MeSHDescriptor[]
     */
    public $descriptors = [];

    /**
     * The language of the currently processed Descriptor set
     * @var null|string
     */
    private $current_descriptors_language = null;

    /**
     * The Id of the currently processed Descriptor
     * @var null|string
     */
    private $current_descriptor_id = null;

    /**
     * The Id of the currently processed concept
     * @var null|string
     */
    private $current_concept_id = null;

    /**
     * Handles DescriptorRecordSet tags and sets the expected language of the terms from their value
     * @param Parser $parser
     * @param string $attrValue
     */
    public function handleDescriptorRecordSetLanguage(Parser $parser, string $attrValue) {
        $this->current_descriptors_language = $attrValue;
        echo("Expecting $attrValue term data from DescriptorRecordSet..." . PHP_EOL);
    }

    /**
     * Handle a new Descriptor
     * @param Parser            $parser
     * @param \SimpleXMLElement $node
     */
    public function handleDescriptorUI(Parser $parser, \SimpleXMLElement $node) {
        $identifier = strval($node);
        if (empty($identifier)) {
            echo("Could not get identifier for Descriptor" . PHP_EOL);
            die(1);
        }
        if (!array_key_exists($identifier, $this->descriptors)) {
            $this->descriptors[$identifier] = new MeSHDescriptor($identifier);
            echo("Added new descriptor $identifier" . PHP_EOL);
        }
        //update current descriptor id
        $this->current_descriptor_id = $identifier;
    }

    /**
     * Handle a new Concept. The concept is added to the current Descriptor.
     * @param Parser            $parser
     * @param \SimpleXMLElement $node
     */
    public function handleConceptUI(Parser $parser, \SimpleXMLElement $node) {
        //get the descriptor for this concept
        $descriptor = $this->descriptors[$this->current_descriptor_id];
        //handle the new concept
        $identifier = strval($node);
        if (empty($identifier)) {
            echo("Could not get identifier for Concept" . PHP_EOL);
            die(1);
        }
        if (is_null($descriptor->getConcept($identifier))) {
            $descriptor->addConcept(new MeSHConcept($identifier));
            $this->descriptors[$this->current_descriptor_id] = $descriptor;
            echo("Added new concept $identifier for descriptor " . $descriptor->getId() . PHP_EOL);
        }
        //update current concept id
        $this->current_concept_id = $identifier;
    }

    /**
     * Handel new terms. New terms are added to the current Concept.
     * @param Parser            $parser
     * @param \SimpleXMLElement $node
     */
    public function handleTerm(Parser $parser, \SimpleXMLElement $node) {
        //get the descriptor for this term
        $descriptor = $this->descriptors[$this->current_descriptor_id];
        //get the concept for this term
        $concept = $descriptor->getConcept($this->current_concept_id);
        //check if term already exists for this concept and if not, create new one
        $identifier = strval($node->termui);
        $term = $concept->getTerm($identifier);
        if (is_null($term)) {
            echo("Added new term $identifier for concept " . $this->current_concept_id . PHP_EOL);
            $term = new MeSHTerm($identifier);
        }
        //handle "normal" term, i.e. non permutation
        if ($node->attributes()->ispermutedtermyn == "N") {
            $term->setTerm($node->string);
            //if this is a preferred term, set is as such
            if ($node->attributes()->conceptpreferredtermyn == "Y") {
                $term->setPreferredTerm(true);
            }
        }
        //if this is just a permutation of a term, it should have the same term id as its base form and the appropriate attribute set to y
        if ($node->attributes()->ispermutedtermyn == "Y") {
            $term->addPermutedTerm($node->string);
        }
        //try to establish the language of the term. If the start of the termUI matches the current DescriptorRecordSet language,
        //we consider it to be in this language, otherwise English. This is crude, but there is no explicit language field on any term.
        if (substr($identifier, 0, strlen($this->current_descriptors_language)) == $this->current_descriptors_language) {
            $term->setLanguage($this->current_descriptors_language);
        } else {
            $term->setLanguage('eng');
        }
        //store the new data
        $concept->addTerm($term);
        $descriptor->addConcept($concept);
        $this->descriptors[$descriptor->getId()] = $descriptor;
    }

}