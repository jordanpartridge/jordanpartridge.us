## Priority
Medium

## Description
Add clear documentation to explain the difference between body and content fields in the Post model.

## Problem
CodeRabbit review found that the Post model has both body and content fields with similar purposes but no clear documentation explaining the difference.

## Solution
1. Add PHPDoc comments to the Post model describing each field
2. Update README documentation with field descriptions
3. Ensure form labels clearly differentiate the fields

## Acceptance Criteria
- Add PHPDoc comments explaining each fields purpose
- Update README with field descriptions
- Update any related form labels for clarity

## Related PR
Part of #179 addressing CodeRabbit feedback
