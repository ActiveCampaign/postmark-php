# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Full PHP 8.4 compatibility and support
- Explicit nullable parameter type declarations for better type safety

### Changed
- Upgraded PHPUnit from ^9 to ^10.0 for better PHP 8.4 compatibility
- Updated PHPDoc type annotations to match actual method signatures
- Fixed all implicit nullable parameter deprecation warnings in PHP 8.4

### Fixed
- Resolved 20+ PHP 8.4 deprecation warnings related to implicit nullable parameters
- Fixed PHPStan static analysis issues with type mismatches
- Corrected return type declaration for `processRestRequest()` method

### Technical Details
- Updated `PostmarkAdminClient.php` - Fixed 15+ method parameters with explicit nullable types
- Updated `PostmarkAttachment.php` - Fixed constructor and static method parameters
- Updated `WebhookConfiguration.php` and `WebhookConfigurationTriggers.php` - Fixed nullable parameters
- Updated various model classes with proper nullable type declarations
- All tests pass on PHP 8.1, 8.2, 8.3, and 8.4
- Zero deprecation warnings on PHP 8.4 