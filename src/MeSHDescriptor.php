<?php
namespace ClubsProject\MeSHMerger;

class MeSHDescriptor extends MeSHObject {

    /**
     * array of Concepts for this Descriptor
     * @var MeSHConcept[]
     */
    private $concepts = [];

    /**
     * Adds the given Concept to this Descriptor
     * @param MeSHConcept $concept
     */
    public function addConcept(MeSHConcept $concept) {
        $this->concepts[$concept->getId()] = $concept;
    }

    /**
     * Returns the Concept with the given ID or null, if this ID is unknown
     * @param string $concept_id
     * @return MeSHConcept|null
     */
    public function getConcept(string $concept_id) {
        if (array_key_exists($concept_id, $this->concepts)) {
            return $this->concepts[$concept_id];
        } else {
            return null;
        }
    }

    /**
     * Returns all Concepts associated with the descriptor.
     * @return array MeSHConcept
     */
    public function getAllConcepts() {
        return $this->concepts;
    }

}