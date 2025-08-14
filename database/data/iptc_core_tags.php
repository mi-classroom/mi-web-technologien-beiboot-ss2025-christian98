<?php

return [
    [
        "name" => "ApplicationRecordVersion",
        "tag" => "2#000",
        "description" => null,
        "spec" => [
            "data_type" => "number",
            "min_length" => null,
            "max_length" => null,
            "multiple" => false,
            "required" => true,
            "enum_values" => null
        ],
        "value_editable" => false
    ],
    [
        "name" => "ObjectTypeReference",
        "tag" => "2#003",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 3,
            "max_length" => 67,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ObjectAttributeReference",
        "tag" => "2#004",
        "description" => "Describes the nature, intellectual, artistic or journalistic characteristic of an image.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 4,
            "max_length" => 68,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ObjectName",
        "tag" => "2#005",
        "description" => "A shorthand reference for the digital image. Title provides a short human readable name which can be a text and/or numeric reference. It is not the same as Headline.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "EditStatus",
        "tag" => "2#007",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "EditorialUpdate",
        "tag" => "2#008",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 2,
            "max_length" => 2,
            "multiple" => false,
            "required" => false,
            "enum_values" => ["01"]
        ],
        "value_editable" => true
    ],
    [
        "name" => "Urgency",
        "tag" => "2#010",
        "description" => null,
        "spec" => [
            "data_type" => "enum",
            "min_length" => 1,
            "max_length" => 1,
            "multiple" => false,
            "required" => false,
            "enum_values" => ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"]
        ],
        "value_editable" => true
    ],
    [
        "name" => "SubjectReference",
        "tag" => "2#012",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 13,
            "max_length" => 236,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Category",
        "tag" => "2#015",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 3,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "SupplementalCategories",
        "tag" => "2#020",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "FixtureIdentifier",
        "tag" => "2#022",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Keywords",
        "tag" => "2#025",
        "description" => "Keywords to express the subject and other aspects of the content of the image. Keywords may be free text and don't have to be taken from a controlled vocabulary. Codes from the controlled vocabulary IPTC Subject NewsCodes must go to the \"Subject Code\" field.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ContentLocationCode",
        "tag" => "2#026",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 3,
            "max_length" => 3,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ContentLocationName",
        "tag" => "2#027",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ReleaseDate",
        "tag" => "2#030",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 8,
            "max_length" => 8,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ReleaseTime",
        "tag" => "2#035",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 11,
            "max_length" => 11,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ExpirationDate",
        "tag" => "2#037",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 8,
            "max_length" => 8,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ExpirationTime",
        "tag" => "2#038",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 11,
            "max_length" => 11,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "SpecialInstructions",
        "tag" => "2#040",
        "description" => "Any number of instructions from the provider or creator to the receiver of the image",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 256,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ActionAdvised",
        "tag" => "2#042",
        "description" => null,
        "spec" => [
            "data_type" => "enum",
            "min_length" => 2,
            "max_length" => 2,
            "multiple" => false,
            "required" => false,
            "enum_values" => ["01", "02", "03", "04"]
        ],
        "value_editable" => true
    ],
    [
        "name" => "ReferenceService",
        "tag" => "2#045",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 10,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ReferenceDate",
        "tag" => "2#047",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 8,
            "max_length" => 8,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ReferenceNumber",
        "tag" => "2#050",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 8,
            "max_length" => 8,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "DateCreated",
        "tag" => "2#055",
        "description" => "Designates the date and optionally the time the content of the image was created rather than the date of the creation of the digital representation.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 8,
            "max_length" => 8,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "TimeCreated",
        "tag" => "2#060",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 11,
            "max_length" => 11,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "DigitalCreationDate",
        "tag" => "2#062",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 8,
            "max_length" => 8,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "DigitalCreationTime",
        "tag" => "2#063",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 11,
            "max_length" => 11,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "OriginatingProgram",
        "tag" => "2#065",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ProgramVersion",
        "tag" => "2#070",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 10,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ObjectCycle",
        "tag" => "2#075",
        "description" => null,
        "spec" => [
            "data_type" => "enum",
            "min_length" => 1,
            "max_length" => 1,
            "multiple" => false,
            "required" => false,
            "enum_values" => ["a", "b", "p"]
        ],
        "value_editable" => true
    ],
    [
        "name" => "By-line",
        "tag" => "2#080",
        "description" => "Contains the name of the photographer, but in cases where the photographer should not be identified the name of a company or organisation may be appropriate.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "By-lineTitle",
        "tag" => "2#085",
        "description" => "Contains the job title of the photographer. As this is sort of a qualifier the Creator element has to be filled in as mandatory prerequisite for using Creator's Jobtitle.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "City",
        "tag" => "2#090",
        "description" => "Name of the city of the location shown in the image. This element is at the third level of a top-down geographical hierarchy.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Sub-location",
        "tag" => "2#092",
        "description" => "Exact name of the sublocation shown in the image. This sublocation name could either be the name of a sublocation to a city or the name of a well known location or (natural) monument outside a city. In the sense of a sublocation to a city this element is at the fourth level of a top-down geographical hierarchy.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Province-State",
        "tag" => "2#095",
        "description" => "Name of the subregion of a country of the location shown in the image. This element is at the second level of a top-down geographical hierarchy.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Country-PrimaryLocationCode",
        "tag" => "2#100",
        "description" => "Code of the country of the location shown in the image. This element is at the top/first level of a top-down geographical hierarchy. The code should be taken from ISO 3166 two or three letter code. The full name of a country should go to the \"Country\" element.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 3,
            "max_length" => 3,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Country-PrimaryLocationName",
        "tag" => "2#101",
        "description" => "Full name of the country of the location shown in the image. This element is at the top/first level of a top-down geographical hierarchy. The full name should be expressed as a verbal name and not as a code, a code should go to the element \"CountryCode\"",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "OriginalTransmissionReference",
        "tag" => "2#103",
        "description" => "now used as a job identifier",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Headline",
        "tag" => "2#105",
        "description" => "A brief synopsis of the caption. Headline is not the same as Title.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 256,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Credit",
        "tag" => "2#110",
        "description" => "The credit to person(s) and/or organisation(s) required by the supplier of the image to be used when published. This is a free-text field.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Source",
        "tag" => "2#115",
        "description" => "The name of a person or party who has a role in the content supply chain. This could be an agency, a member of an agency, an individual or a combination. Source could be different from Creator and from the entities in the Copyright Notice.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "CopyrightNotice",
        "tag" => "2#116",
        "description" => "Contains any necessary copyright notice for claiming the intellectual property for this photograph and should identify the current owner of the copyright for the photograph. Other entities like the creator of the photograph may be added in the corresponding field. Notes on usage rights should be provided in \"Rights usage terms\".",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 128,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Contact",
        "tag" => "2#118",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 128,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Caption-Abstract",
        "tag" => "2#120",
        "description" => "A textual description, including captions, of the image.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 2000,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "LocalCaption",
        "tag" => "2#121",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 256,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "Writer-Editor",
        "tag" => "2#122",
        "description" => "Identifier or the name of the person(s) involved in writing, editing or correcting the Description, Alt Text (Accessibility), or Extended Description (Accessibility) of the image.",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "RasterizedCaption",
        "tag" => "2#125",
        "description" => null,
        "spec" => [
            "data_type" => "binary",
            "min_length" => 7360,
            "max_length" => 7360,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ImageType",
        "tag" => "2#130",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 2,
            "max_length" => 2,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ImageOrientation",
        "tag" => "2#131",
        "description" => null,
        "spec" => [
            "data_type" => "enum",
            "min_length" => 1,
            "max_length" => 1,
            "multiple" => false,
            "required" => false,
            "enum_values" => ["L", "P", "S"]
        ],
        "value_editable" => true
    ],
    [
        "name" => "LanguageIdentifier",
        "tag" => "2#135",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 2,
            "max_length" => 3,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "AudioType",
        "tag" => "2#150",
        "description" => null,
        "spec" => [
            "data_type" => "enum",
            "min_length" => 2,
            "max_length" => 2,
            "multiple" => false,
            "required" => false,
            "enum_values" => ["0T", "1A", "1C", "1M", "1Q", "1R", "1S", "1V", "1W", "2A", "2C", "2M", "2Q", "2R", "2S", "2V", "2W"]
        ],
        "value_editable" => true
    ],
    [
        "name" => "AudioSamplingRate",
        "tag" => "2#151",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 6,
            "max_length" => 6,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "AudioSamplingResolution",
        "tag" => "2#152",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 2,
            "max_length" => 2,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "AudioDuration",
        "tag" => "2#153",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 6,
            "max_length" => 6,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "AudioOutcue",
        "tag" => "2#154",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "JobID",
        "tag" => "2#184",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "MasterDocumentID",
        "tag" => "2#185",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 256,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ShortDocumentID",
        "tag" => "2#186",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "UniqueDocumentID",
        "tag" => "2#187",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 128,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "OwnerID",
        "tag" => "2#188",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 128,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ObjectPreviewFileFormat",
        "tag" => "2#200",
        "description" => null,
        "spec" => [
            "data_type" => "enum",
            "min_length" => null,
            "max_length" => null,
            "multiple" => false,
            "required" => false,
            "enum_values" => ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29"]
        ],
        "value_editable" => false
    ],
    [
        "name" => "ObjectPreviewFileVersion",
        "tag" => "2#201",
        "description" => null,
        "spec" => [
            "data_type" => "number",
            "min_length" => null,
            "max_length" => null,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => false
    ],
    [
        "name" => "ObjectPreviewData",
        "tag" => "2#202",
        "description" => null,
        "spec" => [
            "data_type" => "binary",
            "min_length" => 0,
            "max_length" => 256000,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => false
    ],
    [
        "name" => "Prefs",
        "tag" => "2#221",
        "description" => "PhotoMechanic preferences",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ClassifyState",
        "tag" => "2#225",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 64,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "SimilarityIndex",
        "tag" => "2#228",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 32,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "DocumentNotes",
        "tag" => "2#230",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 1024,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "DocumentHistory",
        "tag" => "2#231",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 256,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "ExifCameraInfo",
        "tag" => "2#232",
        "description" => null,
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 4096,
            "multiple" => false,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ],
    [
        "name" => "CatalogSets",
        "tag" => "2#255",
        "description" => "written by iView MediaPro",
        "spec" => [
            "data_type" => "string",
            "min_length" => 0,
            "max_length" => 256,
            "multiple" => true,
            "required" => false,
            "enum_values" => null
        ],
        "value_editable" => true
    ]
];
