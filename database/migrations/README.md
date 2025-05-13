# Gmail Integration Migrations

This document outlines the database schema for the Gmail integration feature.

## Migration Order

1. `2025_05_13_024325_create_client_emails_table.php` - Core table for email metadata
2. `create_client_email_bodies_migration` (Upcoming) - Stores email body content
3. `create_client_email_attachments_migration` (Upcoming) - Stores email attachments

## Schema Design

### client_emails
Stores essential metadata about emails associated with clients.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| client_id | bigint | Foreign key to clients table |
| gmail_message_id | string | Unique ID from Gmail API |
| subject | string | Email subject line |
| snippet | string | Preview text (up to 1000 chars) |
| from_email | string | Sender email address |
| from_name | string | Sender name |
| is_sent | boolean | Whether email was sent by the client |
| received_at | timestamp | When email was received |
| thread_id | string | Gmail thread ID for conversation grouping |
| labels | json | Gmail labels for organization |
| has_attachment | boolean | Whether email has attachments |
| created_at | timestamp | Record creation timestamp |
| updated_at | timestamp | Record update timestamp |

**Indexes:**
- `gmail_message_id` (unique) - Prevents duplicate emails
- `thread_id` - For grouping emails by conversation
- `[client_id, received_at]` - For efficient client email timeline queries
- `[is_sent, received_at]` - For filtering sent vs received emails

The design separates content (bodies) and attachments into separate tables for:
1. Performance - Prevents loading large content when only metadata is needed
2. Storage efficiency - Allows appropriate column types for different content
3. Query optimization - Allows selective loading of only needed components