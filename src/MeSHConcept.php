<?php
namespace ClubsProject\MeSHMerger;


class MeSHConcept extends MeSHObject {

    /**
     * array of Terms for this oncept
     * @var MeSHTerm[]
     */
    private $terms = [];

    /**
     * Adds the given Term to this Concept
     * @param MeSHTerm $term
     */
    public function addTerm(MeSHTerm $term) {
        $this->terms[$term->getId()] = $term;
    }

    /**
     * Returns the Term with the given ID or null, if this ID is unknown
     * @param string $term_id
     * @return MeSHTerm|null
     */
    public function getTerm(string $term_id) {
        if (array_key_exists($term_id, $this->terms)) {
            return $this->terms[$term_id];
        } else {
            return null;
        }
    }

    /**
     * Returns all Terms associated with the Concept.
     * @return array MeSHTerm
     */
    public function getAllTerms() {
        return $this->terms;
    }

}