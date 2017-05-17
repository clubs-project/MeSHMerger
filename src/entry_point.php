<?php
require "vendor/autoload.php";

//TODO: Check for dependencies: XMLwriter, Xmlreader, PHP 7

//try to disable PHP memory limit
ini_set('memory_limit', '-1');

if (count($argv) < 3) {
    echo("Please specify at least two MeSH XML files to be merged" . PHP_EOL);
    echo("Usage example: 'php $argv[0] en.xml de.xml fr.xml es.xml'" . PHP_EOL);
    die(1);
}
$source_files = array_slice($argv, 1); //slice off script filename

//validate all given source files exist and are accessible
foreach ($source_files as $source_file) {
    if (!file_exists($source_file)) {
        echo("File $source_file does not exist..." . PHP_EOL);
        die(1);
    }
    if (!is_readable($source_file)) {
        echo("File $source_file is not readable..." . PHP_EOL);
        die(1);
    }
    if (!is_file($source_file)) {
        echo("File $source_file is not a file..." . PHP_EOL);
        die(1);
    }
}

//set up event handling
$xmlParser = new \Hobnob\XmlStreamReader\Parser();
$mesh_data_collector = new \ClubsProject\MeSHMerger\MeSHDataCollector();

$xmlParser->registerCallback('/DescriptorRecordSet/@LanguageCode', [$mesh_data_collector, 'handleDescriptorRecordSetLanguage']);
$xmlParser->registerCallback('/DescriptorRecordSet/DescriptorRecord/DescriptorUI', [$mesh_data_collector, 'handleDescriptorUI']);
$xmlParser->registerCallback('/DescriptorRecordSet/DescriptorRecord/ConceptList/Concept/ConceptUI', [$mesh_data_collector, 'handleConceptUI']);
$xmlParser->registerCallback('/DescriptorRecordSet/DescriptorRecord/ConceptList/Concept/TermList/Term', [$mesh_data_collector, 'handleTerm']);

//process all input files
foreach ($source_files as $file) {
    $xmlParser->parse(fopen($file, 'r'));
}

//TODO Check if data structure is according to spec, e.g. no terms with only permutations, etc.

//TODO make output filename/path configurable
\ClubsProject\MeSHMerger\OutputGenerator::generateXml($mesh_data_collector->descriptors, "merged_MeSH.xml");