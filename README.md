# MeSHMerger

The MeSHMerger software combines multiple translations of the Medical Subject Headings (MeSH) thesaurus into multilingual term lists.

### What does MeSHMerger do?

The MeSH have a three level structure of descriptors, concepts and associated terms. A Descriptor is often broader than a single concept and so may consist of a class of concepts. Concepts, in turn, correspond to a class of terms which are synonymous with each other. If you want to learn more about the MeSH structure, please read the [MeSH relations documentation of the NLM](https://www.nlm.nih.gov/mesh/meshrels.html).

Each descriptor, concept and term has a unique ID. MeSHMerger aggregates the different term translations from different files under the appropriate concepts and descriptors and creates a single XML file with all term translations merged.

##### Where to get MeSH and its translations?
MeSH has been, at least partially, translated in various languages. The NLM maintains a special [overview page for MeSH translations](https://www.nlm.nih.gov/mesh/MTMS_MeSH.html).

Before using MeSHMerger and its output, make sure you have the necessary rights for the translations.

## Usage examples

To combine several MeSH translations just give the file names of the uncompressed XML files as parameters to MeSHMerger.

`php mesh-merger.phar source/de/MeSH-2017.xml source/en/desc2017.xml source/fr/fredesc2017.xml` 

##### Example output

```xml
<?xml version="1.0" encoding="UTF-8"?>
<descriptors>
    <descriptor id="D011595">
		<concept id="M0018023">
			<term id="T034238" lang="eng" preferred="true">
				<string>Psychomotor Agitation</string>
			</term>
			<term id="T372239" lang="eng" preferred="false">
				<string>Psychomotor Hyperactivity</string>
				<permutation>Hyperactivity, Psychomotor</permutation>
			</term>
			<term id="T034237" lang="eng" preferred="false">
				<string>Agitation, Psychomotor</string>
			</term>
			<term id="T034239" lang="eng" preferred="false">
				<string>Restlessness</string>
			</term>
			<term id="T034240" lang="eng" preferred="false">
				<string>Excitement, Psychomotor</string>
				<permutation>Psychomotor Excitement</permutation>
			</term>
			<term id="T372238" lang="eng" preferred="false">
				<string>Psychomotor Restlessness</string>
				<permutation>Restlessness, Psychomotor</permutation>
			</term>
			<term id="ger0011271" lang="ger" preferred="true">
				<string>Psychomotorische Agitiertheit</string>
			</term>
			<term id="ger0043352" lang="ger" preferred="false">
				<string>Agitiertheit, psychomotorische</string>
			</term>
			<term id="ger0043401" lang="ger" preferred="false">
				<string>Erregung, psychomotorische</string>
			</term>
			<term id="ger0043353" lang="ger" preferred="false">
				<string>Rastlosigkeit</string>
			</term>
			<term id="fre0011223" lang="fre" preferred="true">
				<string>Agitation psychomotrice</string>
			</term>
			<term id="fre0062872" lang="fre" preferred="false">
				<string>Excitation psychomotrice</string>
			</term>
			<term id="fre0047534" lang="fre" preferred="false">
				<string>Hyperactivité psychomotrice</string>
			</term>
			<term id="fre0047536" lang="fre" preferred="false">
				<string>Incapacité à rester en place</string>
			</term>
			<term id="fre0047535" lang="fre" preferred="false">
				<string>Instabilité psychomotrice</string>
			</term>
		</concept>
		<concept id="M0018025">
			<term id="T034241" lang="eng" preferred="true">
				<string>Akathisia</string>
			</term>
			<term id="ger0038357" lang="ger" preferred="true">
				<string>Akathisie</string>
			</term>
			<term id="fre0047532" lang="fre" preferred="true">
				<string>Acathisie</string>
			</term>
			<term id="fre0062871" lang="fre" preferred="false">
				<string>Acathésie</string>
			</term>
			<term id="fre0047533" lang="fre" preferred="false">
				<string>Akathisie</string>
			</term>
		</concept>
	</descriptor>
</descriptors
```

## Getting the software

You can download a ready-to-use version of the MeSHMerger software on the [publications page of the CLUBS project](https://www.clubs-project.eu/en/publications/).

##### Building the software yourself
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
   