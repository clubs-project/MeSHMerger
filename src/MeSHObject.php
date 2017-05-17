<?php
namespace ClubsProject\MeSHMerger;

abstract class MeSHObject {

    /**
     * The ID for this MeSH term, concept or descriptor
     * @var string
     */
    private $id = null;

    public function __construct(string $id) {
        if (!empty($id)) {
            $this->id = trim($id);
        } else {
            throw new \Exception("ID for " . get_class() . " is empty.");
        }
    }

    /**
     * Returns the ID of this MeSH object
     * @return string
     */
    public function getId() {
        return $this->id;
    }

}