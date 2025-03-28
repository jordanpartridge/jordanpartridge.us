## Priority
High

## Description
Standardize exception handling across all methods in AIContentService to ensure consistent error reporting.

## Problem
CodeRabbit review found inconsistent exception handling in AIContentService. Some exceptions include a prefix while others do not.

## Solution
1. Update all catch blocks to include the "AI content generation failed:" prefix
2. Ensure all exceptions provide detailed context
3. Create consistent error formatting pattern

## Acceptance Criteria
- All exceptions include "AI content generation failed:" prefix
- Error messages provide meaningful context
- Existing tests are updated to reflect new error format

## Related PR
Part of #179 addressing CodeRabbit feedback
