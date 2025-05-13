# Gmail Integration Migrations

This document outlines the database schema for the Gmail integration feature.

## Migration Order

1. `2025_05_11_235024_create_gmail_client_table.php` - Core table for email metadata
2. `2025_05_12_002442_create_gmail_tokens_table.php` - Stores OAuth tokens for Gmail API
3. `2025_05_13_030914_create_client_email_bodies_table.php` - Stores email body content
4. `create_client_email_attachments_migration` (Upcoming) - Stores email attachments

## Schema Design

### client_emails

Stores essential metadata about emails associated with clients.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| client_id | bigint | Foreign key to clients table |
| gmail_message_id | string | Unique ID from Gmail API |
| subject | string | Email subject line |
| from | string | Sender email address |
| to | json | Recipient email addresses |
| cc | json | Carbon copy recipients |
| bcc | json | Blind carbon copy recipients |
| snippet | text | Preview text |
| body | longtext | Full email content (HTML) - **Deprecated: Will be removed in future migration** |
| label_ids | json | Gmail labels for organization |
| raw_payload | json | Raw message data from Gmail API |
| email_date | timestamp | When email was sent/received |
| synchronized_at | timestamp | When email was synced |
| created_at | timestamp | Record creation timestamp |
| updated_at | timestamp | Record update timestamp |

**Indexes:**

- `gmail_message_id` - Prevents duplicate emails
- `client_id` with `received_at` - For efficient client email timeline queries

### gmail_tokens

Stores OAuth tokens for Gmail API authentication.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users table |
| access_token | text | OAuth access token |
| refresh_token | text | OAuth refresh token |
| expires_at | timestamp | Token expiration time |
| created_at | timestamp | Record creation timestamp |
| updated_at | timestamp | Record update timestamp |

### client_email_bodies

Stores email body content separately for performance optimization.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| client_email_id | bigint | Foreign key to client_emails table |
| html_content | longtext | HTML version of email body |
| text_content | longtext | Plain text version of email body |
| created_at | timestamp | Record creation timestamp |
| updated_at | timestamp | Record update timestamp |

**Indexes:**

- `client_email_id` - For efficient lookups

## Design Rationale

The design separates email content (bodies) and attachments into separate tables for:

1. **Performance** - Prevents loading large content when only metadata is needed
2. **Storage efficiency** - Allows appropriate column types for different content
3. **Query optimization** - Allows selective loading of only needed components
4. **Scalability** - Handles large volumes of emails with attachments efficiently

## Migration Plan

1. **Phase 1** (Current): Create separate tables for bodies and attachments
2. **Phase 2** (Future): Migrate existing data from `client_emails.body` column to the new tables
3. **Phase 3** (Future): Remove deprecated columns through a separate migration
