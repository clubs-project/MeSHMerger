<?php
namespace ClubsProject\MeSHMerger;

class MeSHTerm
{

    private $id = null;
    private $term = null;
    private $preferred_term = null;
    private $permuted_terms = [];
    private $language = null;

    public function __construct(string $id)
    {
        $this->setId($id);
    }

    public function setId(string $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function addPermutedTerm(string $term) {
        $this->permuted_terms = array_unique(array_merge($this->permuted_terms, [$term]));
    }

    public function getPermutedTerms() {
        return $this->permuted_terms;
    }

    public function setLanguage(string $lang) {
        $this->language = $lang;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getPreferredTerm()
    {
        return $this->preferred_term;
    }

    public function setPreferredTerm(string $preferred_term)
    {
        if (!is_null($this->preferred_term) && $this->preferred_term !== $preferred_term) {
            throw new \Exception("Trying to set preferred term '$preferred_term'' - but it is already set to '$this->preferred_term'");
        }
        $this->preferred_term = $preferred_term;
    }

    public function getTerm()
    {
        return $this->term;
    }

    public function setTerm(string $term)
    {
        if (!is_null($this->term) && $this->term !== $term) {
            throw new \Exception("Trying to set term '$term'' - but it is already set to '$this->term'");
        }
        $this->term = $term;
    }


}