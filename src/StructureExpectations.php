<?php
namespace ClubsProject\MeSHMerger;

class StructureExpectations {

    public static function checkExpectations(array $descriptors) {
        self::checkNoTermWithoutTerm($descriptors);
        self::checkNoTermWithoutLanguage($descriptors);
        self::checkJustOnePreferredTermPerLanguage($descriptors);
    }

    /**
     * Checks that every term has a term string and not e.g. just permutations
     * @throws \Exception
     */
    private static function checkNoTermWithoutTerm(array $descriptors) {
        foreach ($descriptors as $descriptor) {
            /** @var MeSHDescriptor $descriptor */
            foreach ($descriptor->getAllConcepts() as $concept) {
                /** @var MeSHConcept $concept */
                foreach ($concept->getAllTerms() as $term) {
                    /** @var MeSHTerm $term */
                    if (is_null($term->getTerm())) {
                        throw new \Exception("Term " . $term->getId() . " has no associated term string.");
                    }
                }
            }
        }
    }

    /**
     * Checks that every term has an associated language
     * @throws \Exception
     */
    private static function checkNoTermWithoutLanguage(array $descriptors) {
        foreach ($descriptors as $descriptor) {
            /** @var MeSHDescriptor $descriptor */
            foreach ($descriptor->getAllConcepts() as $concept) {
                /** @var MeSHConcept $concept */
                foreach ($concept->getAllTerms() as $term) {
                    /** @var MeSHTerm $term */
                    if (is_null($term->getLanguage())) {
                        throw new \Exception("Term " . $term->getId() . " has no associated language.");
                    }
                }
            }
        }
    }

    /**
     * Checks that every concept has just one preferred term per language
     * @throws \Exception
     */
    private static function checkJustOnePreferredTermPerLanguage(array $descriptors) {
        foreach ($descriptors as $descriptor) {
            /** @var MeSHDescriptor $descriptor */
            foreach ($descriptor->getAllConcepts() as $concept) {
                /** @var MeSHConcept $concept */
                $preferred_terms_for_language = [];
                foreach ($concept->getAllTerms() as $term) {
                    /** @var MeSHTerm $term */
                    if ($term->getPreferredTerm()) {
                        if (array_key_exists($term->getLanguage(), $preferred_terms_for_language)) {
                            throw new \Exception("Term " . $term->getId() . " is defined as preferred term for concept " . $concept->getId() . " in language " . $term->getLanguage() . ", but there is already a preferred term for this language with ID " . $preferred_terms_for_language[$term->getLanguage()]);
                        } else {
                            $preferred_terms_for_language[$term->getLanguage()] = $term->getId();
                        }
                    }
                }
            }
        }
    }

}