<?php

declare(strict_types=1);

namespace PhpFsTest;

use PhpFs\Directory;
use PhpFs\Exception\FileException;
use PhpFs\File;
use PHPUnit\Framework\TestCase;

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

    public function testCanWriteToExistingFile(): void
    {
        $file = self::ARTIFACTS . '/cool-story-bro.txt';
        File::create($file);

        $this->assertTrue(File::write($file, 'Tell me more, lol x3'));
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
