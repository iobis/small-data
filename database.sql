
-- occurrences

CREATE TABLE occurrences (
	id uuid PRIMARY KEY,
	eventDate text,-- REQUIRED: ISO 8601
	vernacularName, -- OPTIONAL
	scientificName -- REQUIRED: the name as found in the paper/herbarium/...
	scientificNameID -- REQUIRED: from WoRMS
	identificationRemarks -- OPTIONAL
	identificationReferences -- OPTIONAL list of references used for the identification
	occurrenceID -- REQUIRED: globally unique
	institutionCode -- can be used to compose an occurrence ID: urn:catalog:[institutionCode]:[collectionCode]:[catalogNumber] 
	collectionCode -- can be used to compose an occurrence ID: urn:catalog:[institutionCode]:[collectionCode]:[catalogNumber] 
	catalogNumber -- can be used to compose an occurrence ID: urn:catalog:[institutionCode]:[collectionCode]:[catalogNumber] 
	associatedSequences -- example "GenBank: U34853.1"
	decimalLongitude numeric NOT NULL, -- REQUIRED
	decimalLatitude numeric NOT NULL, -- REQUIRED
	coordinateUncertaintyInMeters numeric, -- OPTIONAL 
	locality text, -- OPTIONAL
	locationID text, -- OPTIONAL e.g. marinespecies identifier
	occurrenceRemarks text, -- OPTIONAL: remarks about occurrence (e.g. stranded on the beach) 
	occurrenceStatus -- REQUIRED: present/absent
	basisOfRecord -- REQUIRED: PreservedSpecimen/FossilSpecimen/LivingSpecimen/HumanObservation/MachineObservation
	associatedReferences -- OPTIONAL: literature references
	doi -- OPTIONAL: doi for the literature reference
	associatedMedia -- OPTIONAL: upload images to flickr (upload API) then put the link here OR let people add the url to the image
	habitat -- vocabulary ???? EMODNet
)


-- logins