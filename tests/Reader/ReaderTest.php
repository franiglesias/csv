<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Tests\Reader;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TalkingBit\Csv\Reader\CSVFileNotFound;
use TalkingBit\Csv\Reader\Mapper\ArrayMapper;
use TalkingBit\Csv\Reader\Reader;
use TalkingBit\Csv\Shared\NoTargetFileDefined;

class ReaderTest extends TestCase
{
    private $reader;
    private $fileSystem;

    protected function setUp(): void
    {
        $this->fileSystem = vfsStream::setup('root');
        $this->reader = new Reader();
    }

    public function testShouldFailIfTargetFileIsNotDefined(): void
    {
        $this->expectException(NoTargetFileDefined::class);
        $rows = $this->reader->readAll();
        $this->assertCount(0, $rows);
    }

    public function testShouldFailIfNoFile(): void
    {
        $this->expectException(CSVFileNotFound::class);
        $file = vfsStream::url('root/file.csv');
        $rows = $this->reader
            ->fromFile($file)
            ->readAll();
        $this->assertCount(0, $rows);
    }

    public function testShouldManageEmptyFile(): void
    {
        $file = vfsStream::newFile('empty.csv')
                            ->at($this->fileSystem)
                            ->setContent("");
        $rows = $this->reader
            ->fromFile($file->url())
            ->readAll();
        $this->assertCount(0, $rows);
    }

    public function testShouldManageOneLine(): void
    {
        $file = vfsStream::newFile('one_line.csv')
                            ->at($this->fileSystem)
                            ->setContent("123;My Name");
        $rows = $this->reader
            ->fromFile($file->url())
            ->readAll();
        $this->assertCount(1, $rows);
    }

    public function testShouldReadOneLine(): void
    {
        $file = vfsStream::newFile('one_line.csv')
    ->at($this->fileSystem)
    ->setContent("123;My Name");
        $rows = $this->reader
            ->fromFile($file->url())
            ->readAll();
        $this->assertEquals(['123', 'My Name'], $rows->current());
    }

    public function testShouldManageMultipleLines(): void
    {
        $file = vfsStream::newFile('multiline.csv')
    ->at($this->fileSystem)
    ->setContent(
            "123;My Name\n456;Other name\n789;Last Name"
        );
        $rows = $this->reader
            ->fromFile($file->url())
            ->readAll();
        $this->assertCount(3, $rows);
    }

    public function testShouldManageHeaders(): void
    {
        $file = vfsStream::newFile('only_headers.csv')
                ->at($this->fileSystem)
                ->setContent("id;name");
        $rows = $this->reader
            ->withHeaders()
            ->fromFile($file->url())
            ->readAll();
        $this->assertCount(0, $rows);
    }

    public function testShouldReadLinesWithHeaders(): void
    {
        $file = vfsStream::newFile('one_line_with_headers.csv')
                            ->at($this->fileSystem)
                            ->setContent(
            "id;name\n123;My Name"
        );
        $rows = $this->reader
            ->withHeaders()
            ->fromFile($file->url())
            ->readAll();
        $this->assertEquals(['id' => '123', 'name' => 'My Name'], $rows->current());
    }

    public function testShouldPrioritizeExplicitMapper(): void
    {
        $file = vfsStream::newFile('one_line_with_headers.csv')
                            ->at($this->fileSystem)
                            ->setContent(
            "id;name\n123;My Name"
        );
        $rows = $this->reader
            ->withHeaders()
            ->usingMapper(new ArrayMapper())
            ->fromFile($file->url())
            ->readAll();
        $this->assertEquals(['123', 'My Name'], $rows->current());
    }

    public function testShouldAllowConfigureDelimiter(): void
    {
        $file = vfsStream::newFile('comma_delimited.csv')
            ->at($this->fileSystem)
            ->setContent("id,name\n123,My Name");
        $rows = $this->reader
            ->withDelimiter(',')
            ->withHeaders()
            ->fromFile($file->url())
            ->readAll();
        $this->assertEquals(['id' => '123', 'name' => 'My Name'], $rows->current());
    }

    public function testShouldAllowConfigureEnclosure(): void
    {
        $file = vfsStream::newFile('with_enclosure.csv')
            ->at($this->fileSystem)
            ->setContent("id;name\n123;'My Name'");
        $rows = $this->reader
            ->withEnclosure('\'')
            ->withHeaders()
            ->fromFile($file->url())
            ->readAll();
        $this->assertEquals(['id' => '123', 'name' => 'My Name'], $rows->current());
    }
}
