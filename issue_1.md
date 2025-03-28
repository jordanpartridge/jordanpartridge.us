## Priority
High

## Description
Create a migration to add the missing is_published column to the posts table that the model is expecting.

## Problem
The posts model relies on an is_published column that does not exist in the database schema.

## Solution
1. Create a new migration that adds the column
2. Update existing records based on published_at value
3. Document the column in the model

## Acceptance Criteria
- Migration creates boolean column with appropriate defaults
- Existing records are updated based on published_at value
- Column is properly documented in the model
- Tests verify the column works correctly

## Related PR
Part of #179 addressing CodeRabbit feedback
