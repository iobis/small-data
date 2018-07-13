
-- occurrences/distribution records (!!! spelling oCCuRRence)

DROP TABLE IF EXISTS occurrences ;

CREATE TABLE occurrences (
	id_uuid numeric PRIMARY KEY,
	occurrence_ID text NOT NULL,-- REQUIRED: globally unique (UNIQUE in the occurences table, not in the history table)
	event_Date text,-- REQUIRED: ISO 8601
	vernacular_Name text, -- OPTIONAL
	scientific_Name text NOT NULL, -- REQUIRED: the name as found in the paper/herbarium/...
	scientific_Name_ID text NOT NULL, -- REQUIRED: from WoRMS
	identification_Remarks text, -- OPTIONAL
	identification_References text, -- OPTIONAL list of references used for the identification
	institution_Code text, -- can be used to compose an occurrence ID: urn:catalog:[institutionCode]:[collectionCode]:[catalogNumber] 
	collection_Code text, -- can be used to compose an occurrence ID: urn:catalog:[institutionCode]:[collectionCode]:[catalogNumber] 
	catalog_Number text,-- can be used to compose an occurrence ID: urn:catalog:[institutionCode]:[collectionCode]:[catalogNumber] 
	associated_Sequences text,-- example "GenBank: U34853.1"
	decimal_Longitude numeric NOT NULL, -- REQUIRED
	decimal_Latitude numeric NOT NULL, -- REQUIRED
	coordinate_Uncertainty_In_Meters numeric, -- OPTIONAL 
	locality text, -- OPTIONAL
	location_ID text, -- OPTIONAL e.g. marine region identifier
	occurrence_Remarks text, -- OPTIONAL: remarks about occurrence (e.g. stranded on the beach) 
	occurrence_Status text, -- REQUIRED: present/absent
	basis_Of_Record text, -- REQUIRED: PreservedSpecimen/FossilSpecimen/LivingSpecimen/HumanObservation/MachineObservation
	associated_References text, -- OPTIONAL: literature references
	doi text, -- OPTIONAL: doi for the literature reference
	associated_Media text, -- OPTIONAL: upload images to flickr (upload API) then put the link here OR let people add the url to the image
	habitat text,-- vocabulary ???? EMODNet
	minimum_depth_in_meters numeric,
	maximum_depth_in_meters numeric,
    created_by_user numeric NOT NULL,
	creation_date timestamp NOT NULL,
	validated_by_user numeric,
	validation_date timestamp,
	last_modified_by_user numeric,
	last_modification_date timestamp
);

-- users and logins (normalization and several tables needed, with encrytion,...)

DROP TABLE IF EXISTS users;


CREATE TABLE users (
	id numeric PRIMARY KEY,
	last_name text NOT NULL,
	first_name text NOT NULL,
	username text NOT NULL,
	password text NOT NULL,
	email text NOT NULL,
	institution text,
	scope_of_expertise text
);







