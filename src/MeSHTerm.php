<?php
namespace ClubsProject\MeSHMerger;

class MeSHTerm extends MeSHObject {

    /**
     * This term as string
     * @var null|string
     */
    private $term = null;
    /**
     * If this term is the preferred term of the concept
     * @var bool
     */
    private $preferred_term = false;
    /**
     * An array of permutations for this term
     * @var string[]
     */
    private $permuted_terms = [];
    /**
     * The language of this term.
     * @var string|null
     */
    private $language = null;


    /**
     * Adds the given string as permutation of this term
     * @param string $term
     */
    public function addPermutedTerm(string $term) {
        $this->permuted_terms = array_unique(array_merge($this->permuted_terms, [$term]));
    }

    /**
     * Returns all permutations for this term
     * @return \string[]
     */
    public function getPermutedTerms() {
        return $this->permuted_terms;
    }

    /**
     * Sets the language of the term to the given string
     * @param string $lang
     */
    public function setLanguage(string $lang) {
        $this->language = $lang;
    }

    /**
     * Returns the language iof the term
     * @return null|string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Returns the information, if this term is the preferred term of the concept
     * @return bool
     */
    public function isPreferredTerm() {
        return $this->preferred_term;
    }

    /**
     * Sets if this term is the preferred therm of the associated concept
     * @param bool $preferred_term
     */
    public function setPreferredTerm(bool $preferred_term) {
        $this->preferred_term = $preferred_term;
    }

    /**
     * Returns the term
     * @return null|string
     */
    public function getTerm() {
        return $this->term;
    }

    /**
     * Sets the term. If the term has already been set, check if it is the same string and if not throw an Exception.
     * @param string $term
     * @throws \Exception
     */
    public function setTerm(string $term) {
        if (!is_null($this->term) && $this->term !== $term) {
            throw new \Exception("Trying to set term '$term'' - but it is already set to '$this->term'");
        }
        $this->term = $term;
    }
}