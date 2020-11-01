# Warehouse

## How to install?

1. Make sure PHP (>= 7.2) is installed in the system.
2. Install composer (https://getcomposer.org/download/)
3. Clone the repository
4. Access the project folder 
5. Run composer install
6. Run the unit tests from the project root with the command ./vendor/bin/phpunit test/

## Architecture

The system tries to follow DDD principles to provide clear separation between application and domain logic. The main folders are:
- Application: Contains the two main services (Article and Product) to handle the use cases (search and remove)
- Domain: Contains the business logic related entities, services and value objects.
- Infrastructure: Contains the concrete implementation of the repositories and domain services.

The current state of the application only provides in-memory implementations for the sake of simplicity. If the system were to go live, it would 
be easy to add proper implementations of each one of the repositories and services.

The system provides to Application services:
- ArticleService to handle any use case related to the Article entity
- ProductService to handle any use case related to the Product entity

Both services rely in a in-memory Repository as storage of the entities. As commented before, one proper impelmemtation for those repositories would
be a document oriented database, like MongoDb or ElasticSearch, since both store information related to the entities that may be used in complex 
searches. 

But, what about the inventory? Is not part of the Article or the Product. Yes, but given that the access patterns to the inventory information differ 
(there might be a more real time constraint), the idea behind the current design is to be handled separately (either in a different storage within the 
same service or as a standalone service if the traffic requirements are different from the Article and Product services). In both cases, due to the size
of the information (16 bytes per entry) and the fast change nature of the stock data, the best storage seems an memory based storage engine, like 
Redis or Riak.
