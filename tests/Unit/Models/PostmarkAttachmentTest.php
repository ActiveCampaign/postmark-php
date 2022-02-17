<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Postmark\Exception\AttachmentFileCannotBeLoaded;
use Postmark\Models\PostmarkAttachment;

use function base64_encode;
use function file_get_contents;

class PostmarkAttachmentTest extends TestCase
{
    private static function assertFileContentEquals(string $expected, PostmarkAttachment $file): void
    {
        $data = $file->jsonSerialize();
        self::assertArrayHasKey('Content', $data);
        self::assertEquals($expected, $data['Content'], 'File content did not match the expected value');
    }

    private static function assertMimeTypeEquals(string $expected, PostmarkAttachment $file): void
    {
        $data = $file->jsonSerialize();
        self::assertArrayHasKey('ContentType', $data);
        self::assertEquals($expected, $data['ContentType'], 'Mime type did not match the expected value');
    }

    private static function assertNameEquals(string $expected, PostmarkAttachment $file): void
    {
        $data = $file->jsonSerialize();
        self::assertArrayHasKey('Name', $data);
        self::assertEquals($expected, $data['Name'], 'Attachment filename did not match the expected value');
    }

    private static function assertContentIdEquals(string $expected, PostmarkAttachment $file): void
    {
        $data = $file->jsonSerialize();
        self::assertArrayHasKey('ContentId', $data);
        self::assertEquals($expected, $data['ContentId'], 'Content ID did not match the expected value');
    }

    public function testThatFromRawDataEncodesTheValueToBase64(): void
    {
        $expect = base64_encode('Foo');
        $file = PostmarkAttachment::fromRawData('Foo', 'Foo.txt', 'text/plain');
        self::assertFileContentEquals($expect, $file);
        self::assertMimeTypeEquals('text/plain', $file);
        self::assertNameEquals('Foo.txt', $file);
    }

    public function testThatAnExceptionIsThrownWhenAFileCannotBeRead(): void
    {
        $path = __DIR__ . '/not-here';
        $this->expectException(AttachmentFileCannotBeLoaded::class);
        $this->expectExceptionMessage($path);
        PostmarkAttachment::fromFile($path, 'Foo.txt');
    }

    public function testThatAlreadyEncodedDataIsTrustedToBeEncodedEvenIfItsNot(): void
    {
        $file = PostmarkAttachment::fromBase64EncodedData('Not encoded', 'foo.txt');
        self::assertFileContentEquals('Not encoded', $file);
    }

    public function testThatAFileWillBeReadFromDiskAndEncodedWithBase64Encoding(): void
    {
        $path = __DIR__ . '/../../postmark-logo.png';
        $expect = base64_encode(file_get_contents($path));
        $file = PostmarkAttachment::fromFile($path, 'Foo.txt');
        self::assertFileContentEquals($expect, $file);
    }

    public function testThatAllGivenValuesWillBePreserved(): void
    {
        $file = PostmarkAttachment::fromBase64EncodedData('foo', 'bar.txt', 'anything', 'whatever');
        self::assertFileContentEquals('foo', $file);
        self::assertNameEquals('bar.txt', $file);
        self::assertMimeTypeEquals('anything', $file);
        self::assertContentIdEquals('whatever', $file);
    }

    public function testThatTheContentIdDefaultsToTheFileNameWhenNull(): void
    {
        $file = PostmarkAttachment::fromBase64EncodedData('foo', 'bar.txt', 'anything');
        self::assertContentIdEquals('bar.txt', $file);
    }
}
