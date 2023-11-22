<?php

declare(strict_types=1);

namespace PhpFsTest;

use PhpFs\Directory;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    private const FIXTURES  = './tests/fixtures';
    private const ARTIFACTS = './tests/artifacts';

    public function testCanRecursivelyCreateDirectory(): void
    {
        Directory::create(self::ARTIFACTS);

        $this->assertDirectoryExists(self::ARTIFACTS);
    }

    public function testDeterminesIfDirectoryExists(): void
    {
        $this->assertTrue(Directory::exists(self::FIXTURES . '/content/sub-directory'));
        $this->assertFalse(Directory::exists(self::FIXTURES . '/content/sub-sub-directory'));
    }

    /**
     * @depends testCanRecursivelyCreateDirectory
     */
    public function testCanRecursivelyCopyDirectory(): void
    {
        Directory::copy(self::FIXTURES . '/content', self::ARTIFACTS . '/content');

        $this->assertDirectoryExists(self::ARTIFACTS . '/content');
        $this->assertDirectoryExists(self::ARTIFACTS . '/content/sub-directory');
        $this->assertFileExists(self::ARTIFACTS . '/content/first-level.txt');
        $this->assertFileExists(self::ARTIFACTS . '/content/sub-directory/second-level.txt');
    }

    /**
     * @depends testCanRecursivelyCopyDirectory
     */
    public function testCanRecursivelyMoveDirectory(): void
    {
        Directory::move(self::ARTIFACTS . '/content', self::ARTIFACTS . '/moved-content');

        $this->assertDirectoryExists(self::ARTIFACTS . '/moved-content');
        $this->assertDirectoryExists(self::ARTIFACTS . '/moved-content/sub-directory');
        $this->assertFileExists(self::ARTIFACTS . '/moved-content/first-level.txt');
        $this->assertFileExists(self::ARTIFACTS . '/moved-content/sub-directory/second-level.txt');
    }

    /**
     * @depends testCanRecursivelyMoveDirectory
     */
    public function testCanRecursivelyEmptyDirectory(): void
    {
        Directory::empty(self::ARTIFACTS . '/moved-content');

        $this->assertDirectoryExists(self::ARTIFACTS . '/moved-content');
        $this->assertDirectoryDoesNotExist(self::ARTIFACTS . '/moved-content/sub-directory');
        $this->assertFileDoesNotExist(self::ARTIFACTS . '/moved-content/first-level.txt');
        $this->assertFileDoesNotExist(self::ARTIFACTS . '/moved-content/sub-directory/second-level.txt');
    }

    /**
     * @depends testCanRecursivelyEmptyDirectory
     */
    public function testCanRecursivelyRemoveDirectory(): void
    {
        $dir = self::ARTIFACTS;

        Directory::remove(self::ARTIFACTS);

        $this->assertDirectoryDoesNotExist($dir);
    }
}
