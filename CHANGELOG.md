# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- `PostmarkClientBase::sdkVersion()` reports the installed SDK version, resolved at runtime from
  Composer's package metadata so it cannot drift from the tag a customer actually has. Falls back
  to `PostmarkClientBase::SDK_VERSION_FALLBACK` when that metadata is unavailable — a vendored
  copy, a Phar, or a php-scoper'd build — rather than throwing.
- `X-Client-Type`, `X-Client-Version` and `X-Client-Language` request headers, so API traffic can
  be attributed to an SDK and version without scraping the User-Agent.
- `composer-runtime-api: ^2.0` is now a declared dependency, since the version lookup uses it.

### Changed
- `User-Agent` is now `Postmark-PHP/<version> (PHP/<x.y.z>; OS/<family>)`, matching the
  `product/version (comment)` grammar in RFC 9110 §10.1.5. The `Postmark-PHP` product token is
  unchanged, so any server-side reporting keyed on it keeps working; the `/<version>` suffix and
  the restructured comment are new.

## [v7.0.0](https://github.com/ActiveCampaign/postmark-php/tree/v7.0.0)

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