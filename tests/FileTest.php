<?php

declare(strict_types=1);

namespace PhpFsTest;

use PhpFs\Directory;
use PhpFs\Exception\FileException;
use PhpFs\File;
use PHPUnit\Framework\TestCase;
use Throwable;

use function var_dump;

class FileTest extends TestCase
{
    private const ARTIFACTS = './tests/artifacts';
    private const FIXTURES = './tests/fixtures';

    public function testCanCreateFile(): void
    {
        File::create($file = self::ARTIFACTS . '/cool-story-bro.txt');

        $this->assertFileExists($file);
    }

    public function testThrowsExceptionOnCreateError(): void
    {
        $this->expectException(FileException::class);
        File::create(self::ARTIFACTS . '/dir-does-not-exist/cool-story-bro.txt');
    }

    public function testDeterminesIfFileExists(): void
    {
        $this->assertTrue(File::exists(self::FIXTURES . '/content/first-level.txt'));
        $this->assertFalse(File::exists(self::FIXTURES . '/content/second-level.txt'));
    }

    public function testGetsFileInformation(): void
    {
        $file = self::ARTIFACTS . '/test.txt';
        File::create($file, 0644);
        File::write($file, 'test');

        $info = File::info($file);

        $this->assertEquals(self::ARTIFACTS, $info['dirname']);
        $this->assertEquals('test.txt', $info['basename']);
        $this->assertEquals('test', $info['filename']);
        $this->assertEquals('txt', $info['extension']);
        $this->assertEquals('text/plain', $info['mime_type']);
        $this->assertEquals('us-ascii', $info['mime_encoding']);
        $this->assertEquals('0644', $info['permissions']);
    }

    public function testCanWriteToNewFile(): void
    {
        $file = self::ARTIFACTS . '/cool-story-bro.txt';
        File::create($file);

        $this->assertEquals(20, File::write($file, 'Tell me more, lol x3'));
    }

    public function testThrowsExceptionOnWriteError(): void
    {
        $file = self::ARTIFACTS . '/cool-story-bro.txt';
        File::create($file, 0000);

        $this->expectException(FileException::class);
        File::write($file, 'Tell me more, lol x3');
    }

    public function testCanAppendToExistingFile(): void
    {
        $file = self::ARTIFACTS . '/test.txt';
        File::create($file);
        File::write($file, 'Test');

        $this->assertEquals(7, File::append($file, ' Me'));
        $this->assertEquals('Test Me', File::read($file));
    }

    public function testCanPrependToExistingFile(): void
    {
        $file = self::ARTIFACTS . '/test.txt';
        File::create($file);
        File::write($file, 'Me');

        $this->assertEquals(7, File::prepend($file, 'Test '));
        $this->assertEquals('Test Me', File::read($file));
    }

    public function testCanReadFromExistingFile(): void
    {
        $file = self::ARTIFACTS . '/cool-story-bro.txt';
        File::create($file);
        File::write($file, 'Tell me more, lol x3');

        $this->assertEquals(
            'Tell me more, lol x3',
            File::read($file)
        );
    }

    public function testThrowsExceptionOnReadError(): void
    {
        $file = self::ARTIFACTS . '/cool-story-bro.txt';
        File::create($file, 0000);

        $this->expectException(FileException::class);
        File::read($file);
    }

    public function testCanRemoveFile(): void
    {
        $file = self::ARTIFACTS . '/to-be-removed.txt';
        File::create($file);

        $this->assertFileExists($file);
        File::remove($file);
        $this->assertFileDoesNotExist($file);
    }

    public function testCanCopyFileToExistingDirectory(): void
    {
        $file = self::FIXTURES . '/content/first-level.txt';
        $target = self::ARTIFACTS . '/first-level.txt';

        File::copy($file, $target);

        $this->assertFileExists($target);
    }

    public function testCanMoveFileToExistingDirectory(): void
    {
        $file = self::ARTIFACTS . '/mover.txt';
        $target = self::ARTIFACTS . '/moved.txt';

        File::create($file);
        File::move($file, $target);

        $this->assertFileExists($target);
    }

    protected function setUp(): void
    {
        Directory::create(self::ARTIFACTS);
    }

    protected function tearDown(): void
    {
        Directory::remove(self::ARTIFACTS);
    }
}
