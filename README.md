### Postmark-PHP

**Postmark-PHP** is the _officially supported_ PHP library for [Postmark](http://postmarkapp.com).

With Postmark, you can send and _receive_ emails effortlessly.

## Requirements

- PHP 8.1, 8.2, 8.3, or 8.4
- Guzzle HTTP client

> **Security:** the Brotli decoder (libbrotli 1.0.3–1.0.9, 1.1.0 and 1.2.0) is affected by [CVE-2026-111497](https://www.cve.org/CVERecord?id=CVE-2026-111497). The default Guzzle client this library creates sends no
> `Accept-Encoding` header, so the Postmark API answers uncompressed, but it enables all of libcurl's decoders: a Brotli response from a
> proxy or tampered connection is decoded with your libbrotli when libcurl is built with it. A client you pass in with `setClient()` uses its own settings. Apply your distro's security update for
> `libbrotli`/`brotli` and `curl` (upstream fixed in 1.0.10, 1.1.1 and 1.2.1; distros often backport the fix without
> changing the version).

## Getting Started

[Check out our wiki](https://github.com/ActiveCampaign/postmark-php/wiki/Getting-Started) to get started using Postmark-PHP now.

[![Build Status](https://circleci.com/gh/ActiveCampaign/postmark-php.svg?style=shield)](https://circleci.com/gh/ActiveCampaign/postmark-php)
