<?php
namespace ClubsProject\MeSHMerger;

class MeSHDescriptor
{

    private $id = null;
    private $concepts = [];

    public function __construct(string $id)
    {
        if (!empty($id)) {
            $this->id = trim($id);
        } else {
            throw new \Exception("ID for MeSHDescriptor is empty.");
        }
    }

    /**
     * Returns the ID of this Descriptor
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    public function addConcept(MeshConcept $concept) {
        $this->concepts[$concept->getId()] = $concept;
    }

    /**
     * Returns the Concept with the given Id or null, if this ID is unknown
     * @param string $concept_id
     * @return MeshConcept|null
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
     * @return array MeshConcept
     */
    public function getAllConcepts() {
        return $this->concepts;
    }

}