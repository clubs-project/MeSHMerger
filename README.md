# MeSHMerger

The MeSHMerger software combines multiple translations of the Medical Subject Headings (MeSH) thesaurus into multilingual term lists.

## What does MeSHMerger do?

The MeSH have a three level structure of descriptors, concepts and associated terms. A Descriptor is often broader than a single concept and so may consist of a class of concepts. Concepts, in turn, correspond to a class of terms which are synonymous with each other. If you want to learn more about the MeSH structure, please read the [MeSH relations documentation of the NLM](https://www.nlm.nih.gov/mesh/meshrels.html).

Each descriptor, concept and term has a unique ID. MeSHMerger aggregates the different term translations from different files under the appropriate concepts and descriptors and creates a single XML file with all term translations merged.

## Where to get MeSH and its translations?
MeSH has been, at least partially, translated in various languages. The NLM maintains a special [page for MeSH translations](https://www.nlm.nih.gov/mesh/MTMS_MeSH.html).

Before using MeSHMerger and its output, make sure you have the necessary rights for the translations.

## Usage examples

To combine several MeSH translations just give the file names of the uncompressed XML files as parameters to MeSHMerger.

`php mesh-merger.phar source/de/MeSH-2017.xml source/en/desc2017.xml source/fr/fredesc2017.xml` 

## Getting the software

You can download a ready-to-use version of the MeSHMerger software on the [publications page of the CLUBS project](https://www.clubs-project.eu/en/publications/).

## Building the software yourself
If you want to build MeSHMerger yourself, you will need a working [Composer installation](https://getcomposer.org/doc/00-intro.md) on your computer. Then follow these instructions:

1. Check out this repository

   `git clone https://github.com/clubs-project/MeSHMerger.git`

2. Install dependencies via Composer

   ```
   cd MeSHMerger
   composer install
   ```
3. Create PHAR archive

   `php -d phar.readonly=off vendor/bin/phar-composer build .`
   