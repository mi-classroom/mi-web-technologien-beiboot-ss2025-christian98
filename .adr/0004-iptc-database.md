# File Indexing and Metadata Caching Strategy

* Status: accepted
* Workload: 6 hours
* Decider: [Christian Holländer](https://github.com/christian98)
* Date: 2025-06-10

## Context and Problem Statement

Efficiently managing image metadata requires balancing fast user interactions with reliable file storage. Directly updating file metadata on every frontend request can slow down the user experience and complicate search operations. A strategy is needed to optimize both performance and searchability.

## Decision Drivers

* Fast user interactions and request response times
* Reliable and consistent metadata storage
* Efficient search and indexing capabilities
* Scalability for large numbers of files

## Considered Options

* Directly update file metadata on each frontend request
* Index all files and metadata in a relational database, cache updates, and use background jobs for file writes

## Decision Outcome

Chosen option: "Index all files and metadata in the database, cache updates from the frontend, and trigger background jobs for actual file metadata writes."

### Positive Consequences

* Frontend requests are fast because metadata changes are cached in the database, not written directly to files
* Storage operations are offloaded to background jobs, improving scalability and reliability
* Searching and indexing is efficient since all metadata is stored in a relational database
* No need to read every file for search operations, reducing I/O and improving performance

### Negative Consequences

* Requires job queue and background worker setup
* Potential for temporary inconsistency between cached metadata and actual file metadata until jobs complete

## Pros and Cons of the Options

### Direct File Metadata Updates

* Good, because changes are immediately reflected in files
* Bad, because requests are slow due to file I/O
* Bad, because searching requires reading each file, which is inefficient

### Database Indexing and Background Jobs

* Good, because requests are fast and scalable
* Good, because searching is efficient using database queries
* Good, because background jobs can be retried and monitored
* Bad, because there is a delay before metadata is written to files
* Bad, because requires additional infrastructure for job processing

## Links

* [Laravel Queues Documentation](https://laravel.com/docs/queues)
* [Database Indexing Best Practices](https://www.postgresql.org/docs/current/indexes.html)
