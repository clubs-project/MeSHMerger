<?php
namespace ClubsProject\MeSHMerger;


class MeSHConcept extends MeSHObject
{
    private $terms = [];

    public function addTerm(MeSHTerm $term) {
        $this->terms[$term->getId()] = $term;
    }

    /**
     * Returns the Term with the given Id or null, if this ID is unknown
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

    public function getAllTerms() {
        return $this->terms;
    }

}