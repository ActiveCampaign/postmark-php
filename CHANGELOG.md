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

## [Unreleased] — v8.0.0 (breaking)

### Removed
- **Dropped support for PHP 8.1** (EOL 2025-12-31). `composer.json` now requires `^8.2`.
  Projects on 8.1 stay on v7.x — Composer will not offer them this release.
  Note the previous `~8.1 || ~8.2 || ~8.3 || ~8.4` already resolved to `>=8.1 <9.0`, so 8.5 was
  always permitted; dropping 8.1 is the only real constraint change.

### Changed
- **BREAKING** — `PostmarkAttachment::fromRawData()`, `::fromBase64EncodedData()` and `::fromFile()`
  now declare `string` for their first two parameters and a `PostmarkAttachment` return type.
  Passing `null`, an array, or a non-Stringable object now raises a `TypeError`; previously it
  silently produced an empty attachment. `int` and `Stringable` still coerce, except under
  `declare(strict_types=1)`. **Subclasses overriding these factories must add the
  `: PostmarkAttachment` return type or PHP will fatal at class-load.**
- **BREAKING** — `PostmarkAttachment::fromFile()` now throws `RuntimeException` when the file
  cannot be read, instead of sending an attachment with empty content.

### Added
- PHP 8.5 to the CI matrix.
- **Guzzle 8 is now supported** alongside Guzzle 7 (`^7.15.2 || ^8.0.1`), thanks to
  [@simPod](https://github.com/simPod) (#165). Both majors are exercised in CI rather than
  assumed compatible. The floors are deliberate: Guzzle 8.0.0 and 7.x below 7.15.2 carry
  [GHSA-v5mv-p594-2x33](https://github.com/advisories/GHSA-v5mv-p594-2x33) (high, host-check
  bypass) and [GHSA-f7vp-7xgx-4w4r](https://github.com/advisories/GHSA-f7vp-7xgx-4w4r).

  **Read this if you catch Guzzle exceptions.** Composer resolves the highest satisfying
  version, so upgrading puts you on Guzzle 8 unless you pin otherwise — this is not opt-in.
  Guzzle 8 reclassified transport exceptions, and because this SDK sets `http_errors => false`
  and maps responses to `PostmarkException` itself, the transport family is the *only* Guzzle
  family that reaches your code. Most notably a plain timeout is no longer a `ConnectException`:

  | cURL condition | Guzzle 7 | Guzzle 8 |
  | --- | --- | --- |
  | timeout, connect phase | `ConnectException` | `ConnectTimeoutException` (extends `ConnectException`) |
  | timeout, no response | `ConnectException` | **`NetworkTimeoutException`** |
  | timeout, body stalled | `ConnectException` | **`ResponseTimeoutException`** |
  | send/recv error | `RequestException` | **`NetworkException`** |

  Everything still implements `GuzzleException`, so the SDK's documented contract is unchanged —
  but `catch (ConnectException $e)` around a send will silently stop matching a timeout.

### Fixed
- **`getDeliveryStatistics()` reported `Count = 0` for every bounce category, in every released
  version.** `PostmarkBounceSummary` read the `FirstOpen` key instead of `Count` — a copy-paste
  from `PostmarkOpen`. Any dashboard calibrated against the broken zero will start seeing real
  numbers.
- `PostmarkBounce` assigned its constructor fallbacks to the wrong properties (`Type` got `0`,
  `TypeCode` got `''`), throwing `TypeError` on a response missing either field.
- List models no longer emit `Undefined array key` / `foreach() argument must be of type
  array|object` warnings when the API response omits the collection key. These were fatal under
  application error handlers that promote warnings to exceptions (Laravel, Symfony).


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