<?php
namespace ClubsProject\MeSHMerger;


class MeshConcept
{

    private $id = null;

    private $terms = [];

    public function __construct(string $id)
    {
        if (!empty($id)) {
            $this->id = trim($id);
        } else {
            throw new \Exception("ID for MeshConcept is empty.");
        }
    }

    /**
     * Returns the ID of this Concept
     * @return string
     */
    public function getId() {
        return $this->id;
    }

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