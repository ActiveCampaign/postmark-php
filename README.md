# Postmark-PHP Fork for PHP 8.1

[![Continuous Integration](https://github.com/gsteel/postmark-php/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/gsteel/postmark-php/actions/workflows/continuous-integration.yml)
[![codecov](https://codecov.io/gh/gsteel/postmark-php/branch/main/graph/badge.svg?token=uLlbdRWJwc)](https://codecov.io/gh/gsteel/postmark-php)
[![Psalm Coverage](https://shepherd.dev/github/gsteel/postmark-php/coverage.svg)](https://shepherd.dev/github/gsteel/postmark-php)
[![Psalm Level](https://shepherd.dev/github/gsteel/postmark-php/level.svg)](https://shepherd.dev/github/gsteel/postmark-php)

This is a fork of the official **Postmark-PHP** library for [Postmark](http://postmarkapp.com). You can find the original at [wildbit/postmark-php](https://github.com/wildbit/postmark-php)

This library preserves the api of the original, but has been updated to work on PHP 8.0 and 8.1 with improved type safety, internal re-organisation and BC breaking changes such as renamed/refined exception types, return type hints and parameter type hints.

You can find a summary of the differences in the pull-request on the original lib: [#94](https://github.com/wildbit/postmark-php/pull/94)

This version of the lib **does not ship** an HTTP client, or get one from composer… You'll need to install a PSR-18 compatible HTTP client up front such as [Guzzle](https://github.com/guzzle/guzzle), [php-http/curl-client](https://github.com/php-http/curl-client) etc.

## Versions

This package is marked as a replacement for `"wildbit/postmark-php": "4.0.2"` so it **will** break your project and any dependent libs if your types are out of whack.

Hopefully [#94](https://github.com/wildbit/postmark-php/pull/94) will get merged so none of this is necessary…
