# Data Abstraction Layer

Abstraction layer for data persistency based on domain entities and ORM/ODM (using Doctrine).
Only writing operations supported in this server.
Operations managed via a POST with a payload (data and metadata) indicating the command to be executed.