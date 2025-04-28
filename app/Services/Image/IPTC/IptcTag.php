<?php

namespace App\Services\Image\IPTC;

enum IptcTag: int
{
    // region IPTC Core 1.5
    // case AltText =;
    /**
     * Name of the city of the location shown in the image. This element is at the third level of a top-down geographical hierarchy.
     */
    case City = 90;

    /**
     * Contains any necessary copyright notice for claiming the intellectual property for this photograph and should identify the current owner of the copyright for the photograph. Other entities like the creator of the photograph may be added in the corresponding field. Notes on usage rights should be provided in "Rights usage terms".
     */
    case CopyrightNotice = 116;

    /**
     * Full name of the country of the location shown in the image. This element is at the top/first level of a top-down geographical hierarchy. The full name should be expressed as a verbal name and not as a code, a code should go to the element "CountryCode"
     */
    case Country = 101;

    /**
     * Code of the country of the location shown in the image. This element is at the top/first level of a top-down geographical hierarchy. The code should be taken from ISO 3166 two or three letter code. The full name of a country should go to the "Country" element.
     */
    case CountryCode = 100;

    /**
     * Contains the name of the photographer, but in cases where the photographer should not be identified the name of a company or organisation may be appropriate.
     */
    case Creator = 80;
    // case CreatorsContactInfo =;

    /**
     * Contains the job title of the photographer. As this is sort of a qualifier the Creator element has to be filled in as mandatory prerequisite for using Creator’s Jobtitle.
     */
    case CreatorsJobTitle = 85;

    /**
     * The credit to person(s) and/or organisation(s) required by the supplier of the image to be used when published. This is a free-text field.
     */
    case CreditLine = 110;

    /**
     * Designates the date and optionally the time the content of the image was created rather than the date of the creation of the digital representation.
     */
    case DateCreated = 55;

    /**
     * A textual description, including captions, of the image.
     */
    case Description = 120;

    /**
     * Identifier or the name of the person(s) involved in writing, editing or correcting the Description, Alt Text (Accessibility), or Extended Description (Accessibility) of the image.
     */
    case DescriptionWriter = 122;
    // case ExtendedDescription = ;

    /**
     * A brief synopsis of the caption. Headline is not the same as Title.
     */
    case Headline = 105;

    /**
     * Any number of instructions from the provider or creator to the receiver of the image
     */
    case Instructions = 40;

    /**
     * Describes the nature, intellectual, artistic or journalistic characteristic of an image.
     */
    case IntellectualGenre = 4;

    /**
     * Number or identifier for the purpose of improved workflow handling. This is a user created identifier related to the job for which the image is supplied.
     */
    case JobId = 103;

    /**
     * Keywords to express the subject and other aspects of the content of the image. Keywords may be free text and don’t have to be taken from a controlled vocabulary. Codes from the controlled vocabulary IPTC Subject NewsCodes must go to the "Subject Code" field.
     */
    case Keywords = 25;

    /**
     * Name of the subregion of a country of the location shown in the image. This element is at the second level of a top-down geographical hierarchy.
     */
    case ProvinceOrState = 95;
    // case RightsUsageTerms =;
    // case SceneCode =;

    /**
     * The name of a person or party who has a role in the content supply chain. This could be an agency, a member of an agency, an individual or a combination. Source could be different from Creator and from the entities in the Copyright Notice.
     */
    case Source = 115;

    /**
     * Specifies one or more Subjects from the IPTC Subject-NewsCodes taxonomy to categorise the image. Each Subject is represented as a string of 8 digits in an unordered list.
     */
    case SubjectCode = 12;

    /**
     * Exact name of the sublocation shown in the image. This sublocation name could either be the name of a sublocation to a city or the name of a well known location or (natural) monument outside a city. In the sense of a sublocation to a city this element is at the fourth level of a top-down geographical hierarchy.
     */
    case SubLocation = 92;
    case TimeCreated = 60;

    /**
     * A shorthand reference for the digital image. Title provides a short human readable name which can be a text and/or numeric reference. It is not the same as Headline.
     */
    case Title = 05;
    // endregion


    // case ObjectName = 0x5;
    // case Caption = 0x78;
    // case Datetime = 0x19;
    // case ByLine = 0x50;
    // case ByLineTitle = 0x55;
    case Version = 0;
    case ObjectName = 1;
    case Credit = 0x7b;

    public function toString(): string
    {
        return sprintf('2#%03d', $this->value);
    }

    public static function fromString(string $tag): self
    {
        $tag = explode('#', $tag)[1];
        return self::from((int)$tag);
    }

    public static function tryFromString(string $tag): ?self
    {
        $tag = explode('#', $tag)[1];
        return self::tryFrom((int)$tag);
    }
}
