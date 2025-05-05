# Using PHP Native Functions for IPTC Metadata Handling

* Status: accepted
* Workload: 8 hours
* Decider: [Christian Holländer](https://github.com/christian98)
* Issue: [Kernfunktion im Backend erstelle](https://github.com/mi-classroom/mi-master-wt-beiboot-2025/issues/1)
* Date: 2025-05-05

Technical Story: Implementing IPTC metadata reading and writing functionality within a Laravel application

## Context and Problem Statement

The project requires reading and writing image metadata, specifically IPTC data, within a Laravel application. The solution needs to be maintainable, deployable, and flexible enough to allow for experimentation without additional overhead.

## Decision Drivers

* Need to avoid command line tool dependencies and their deployment challenges
* Requirement for maintainable and current implementations following IPTC standards
* Desire for flexibility during development phase for experimentation
* Backend API formalization requirement

## Considered Options

* PHP native functions (iptcparse, iptcembed, getimagesize)
* Exiftool command line utility
* Existing PHP libraries for metadata handling
* JavaScript-based solutions
* Custom PHP library development from scratch

## Decision Outcome

Chosen option: "PHP native functions (iptcparse, iptcembed, getimagesize)", because it provides the necessary functionality without external dependencies while allowing rapid experimentation and iteration within the application context.

### Positive Consequences

* No external tool dependencies to manage
* Direct integration with Laravel application
* Flexibility for experimenting with different approaches during development
* Simpler deployment without additional software requirements
* Can be easily extracted into a separate library when requirements stabilize

### Negative Consequences

* Limited to PHP's native IPTC functionality
* Potential code coupling within the Laravel application
* May require more manual IPTC standard compliance checks

## Pros and Cons of the Options

### PHP native functions

* Good, because no external dependencies required
* Good, because direct integration with PHP/Laravel
* Good, because allows rapid prototyping and experimentation
* Bad, because functionality might be more limited than specialized tools
* Bad, because code is currently embedded in application instead of being a reusable library

### Exiftool

* Good, because comprehensive metadata support
* Good, because actively maintained and standard-compliant
* Bad, because requires parsing command line output
* Bad, because deployment complexity with path dependencies
* Bad, because updates and maintenance overhead

### Existing PHP libraries

* Good, because ready-to-use implementations
* Bad, because most are unmaintained (3-7 years old)
* Bad, because may not reflect current IPTC standards
* Bad, because potential compatibility issues with modern PHP versions

### JavaScript-based solutions

* Good, because potentially robust frontend processing
* Bad, because doesn't align with backend API architecture
* Bad, because separates metadata handling from business logic

## Links

* [IPTC Standard] [IPTC Information Interchange Model](https://iptc.org/standards/photo-metadata/)
* [PHP IPTC Functions (iptcembed)] [PHP Manual - IPTC Functions](https://www.php.net/manual/en/function.iptcembed.php)
* [Exiftool] [Exiftool Documentation](https://exiftool.org/)
