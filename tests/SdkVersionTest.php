<?php

namespace Postmark\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Composer\InstalledVersions;
use PHPUnit\Framework\TestCase;
use Postmark\PostmarkClientBase;

/**
 * Unit coverage for the SDK version reported in request headers.
 *
 * Deliberately extends TestCase rather than PostmarkClientBaseTest: this needs no
 * API credentials and must run in CI, where the integration suite cannot.
 */
class SdkVersionTest extends TestCase
{
    /**
     * The reported version must be a valid RFC 9110 product-version token.
     *
     * A Composer branch install yields "dev-feature/x", and "/" is a delimiter
     * rather than a token character, which would mis-split the User-Agent.
     */
    public function testSdkVersionIsAValidToken(): void
    {
        $version = PostmarkClientBase::sdkVersion();

        $this->assertNotSame('', $version);
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9._+-]+$/', $version);
    }

    /** The leading "v" on a git tag must not reach the header. */
    public function testSdkVersionHasNoLeadingV(): void
    {
        $this->assertStringStartsNotWith('v', PostmarkClientBase::sdkVersion());
    }

    public function testSdkVersionIsMemoized(): void
    {
        $this->assertSame(PostmarkClientBase::sdkVersion(), PostmarkClientBase::sdkVersion());
    }

    /**
     * Resolution must degrade rather than throw.
     *
     * getPrettyVersion() throws OutOfBoundsException for a package absent from the
     * installed map — the normal case for a vendored copy or a Phar — so this
     * asserts the guard, not the happy path.
     */
    public function testUnknownPackageFallsBackInsteadOfThrowing(): void
    {
        $this->assertTrue(
            class_exists(InstalledVersions::class),
            'Composer runtime API must be present; composer-runtime-api is a declared dependency.'
        );

        $this->assertFalse(InstalledVersions::isInstalled('wildbit/definitely-not-installed'));

        $this->expectException(\OutOfBoundsException::class);
        InstalledVersions::getPrettyVersion('wildbit/definitely-not-installed');
    }

    /** @dataProvider versionNormalizationProvider */
    public function testNormalizeVersion(string $input, string $expected): void
    {
        // No setAccessible() needed: private members are reflectively invocable as of PHP 8.1,
        // which is this package's floor.
        $method = new \ReflectionMethod(PostmarkClientBase::class, 'normalizeVersion');

        $this->assertSame($expected, $method->invoke(null, $input));
    }

    public static function versionNormalizationProvider(): array
    {
        return [
            'tagged release keeps its digits' => ['v7.0.0', '7.0.0'],
            'untagged release is unchanged' => ['7.0.0', '7.0.0'],
            'dev branch survives' => ['dev-main', 'dev-main'],
            'slash in a branch name is replaced' => ['dev-feature/slash', 'dev-feature-slash'],
            'pre-release metadata is preserved' => ['v8.0.0-beta.1+build', '8.0.0-beta.1+build'],
            'empty falls back' => ['', PostmarkClientBase::SDK_VERSION_FALLBACK],
        ];
    }

    /**
     * The hand-maintained fallback drifts from the released tag unless something
     * enforces it; vendored installs report it verbatim.
     */
    public function testFallbackVersionMatchesNewestChangelogEntry(): void
    {
        $changelog = file_get_contents(__DIR__ . '/../CHANGELOG.md');
        $this->assertIsString($changelog, 'CHANGELOG.md must be readable.');

        $this->assertSame(
            1,
            preg_match('/^## \[v?([0-9]+\.[0-9]+\.[0-9]+)\]/m', $changelog, $matches),
            'CHANGELOG.md must carry at least one released "## [vX.Y.Z]" heading.'
        );

        $this->assertSame(
            $matches[1],
            PostmarkClientBase::SDK_VERSION_FALLBACK,
            'SDK_VERSION_FALLBACK must match the newest released CHANGELOG entry.'
        );
    }
}
