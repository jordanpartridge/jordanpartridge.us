## Priority
Medium

## Description
Create a Filament resource for managing AI service configuration.

## Problem
There is currently no admin UI for configuring AI settings like API keys and default models.

## Solution
1. Create AISettingsResource with appropriate form fields
2. Implement secure storage of API keys (encrypted)
3. Add model validation rules
4. Restrict access to admin users only

## Acceptance Criteria
- Form for managing API keys and configurations
- Secure storage of sensitive credentials
- Model validation rules
- Access controls for admin users only

## Related PR
Part of #179 addressing CodeRabbit feedback
