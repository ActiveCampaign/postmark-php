# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v7.0.1](https://github.com/ActiveCampaign/postmark-php/tree/v7.0.1)

### Fixed
- **Getters backed by nullable properties no longer throw `TypeError`.** Each of these promised a
  non-nullable return while its constructor explicitly assigns `null` when the API omits the
  field, so they were guaranteed to fatal on a perfectly valid response:
  `PostmarkOpen::getGeo()`, `PostmarkOpen::getClient()`, `PostmarkOpen::getOS()`,
  `PostmarkMessageBase::getMetadata()`, `PostmarkMessageBase::getMessageStream()` and
  `WebhookConfiguration::getHttpAuth()` now declare nullable return types.
  Reported against `getGeo()` for a broadcast-stream message with no geo data.
  (`PostmarkClick` already declared its equivalents loosely, which is why clicks worked and
  opens did not.)
- **`PostmarkClient::getBounces()` accepts a MessageID again.** The `$messageID` filter was
  changed to `?int` in `c7a4371` and released from v5.0.1 onward. Postmark MessageIDs are GUIDs,
  so the filter has been unusable since — `getBounces(1, 0, null, null, null, null, $guid)` threw
  `Argument #7 ($messageID) must be of type ?int, string given`. It is `?string` again, matching
  the pre-v5.0.1 documented type.

### Notes for upgraders
These are all bug fixes and none should require a change on your side. Widening a return type to
nullable is covariant, so a subclass that overrides one of the getters with the narrower type
stays compatible. The one thing to be aware of: a subclass that overrides `getBounces()` with
`?int $messageID` will need to change to `?string`, since parameter types are contravariant. If
you were catching the `TypeError` from any of the getters above as a workaround, you can drop it.

### Internal
- `.php-cs-fixer.dist.php` never called `setFinder()`, so `php-cs-fixer` aborted with
  "You must call one of in() or append() methods" and had never actually run. Fixed.
- CI gained a credential-free `static-analysis` job running PHPStan, which is the only check in
  this repo a fork PR can currently exercise.

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