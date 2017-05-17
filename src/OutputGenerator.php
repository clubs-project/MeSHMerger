<?php
namespace ClubsProject\MeSHMerger;


class OutputGenerator
{

    public static function generateXml(array $descriptors, string $filename) {
        $oXMLout = new \XMLWriter();
        $oXMLout->openURI($filename);
        $oXMLout->setIndent(true);
        $oXMLout->setIndentString("\t");
        $oXMLout->startDocument("1.0", "UTf-8");
        $oXMLout->startElement("descriptors");
        foreach ($descriptors as $descriptor) {
            /** @var MeSHDescriptor $descriptor */
            $oXMLout->startElement("descriptor");
            $oXMLout->writeAttribute("id", $descriptor->getId());
            foreach ($descriptor->getAllConcepts() as $concept) {
                /** @var MeshConcept $concept */
                $oXMLout->startElement("concept");
                $oXMLout->writeAttribute("id", $concept->getId());
                foreach ($concept->getAllTerms() as $term) {
                    /** @var MeSHTerm $term */
                    $oXMLout->startElement("term");
                    $oXMLout->writeAttribute("id", $term->getId());
                    $oXMLout->writeAttribute("lang", $term->getLanguage());
                    $oXMLout->writeAttribute("preferred", $term->getPreferredTerm() ? 'true' : 'false');
                    $oXMLout->writeElement("string", $term->getTerm());
                    foreach ($term->getPermutedTerms() as $permutation) {
                        $oXMLout->writeElement("permutation", $permutation);
                    }
                    $oXMLout->endElement();//term
                }
                $oXMLout->endElement(); //concept
            }
            $oXMLout->endElement(); //descriptor
        }
        $oXMLout->endElement(); //descriptors
    }

}