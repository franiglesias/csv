<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Tests\Writer;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TalkingBit\Csv\Shared\NoTargetFileDefined;
use TalkingBit\Csv\Tests\Reader\Mapper\Dto\RowDto;
use TalkingBit\Csv\Writer\Writer;

class WriterTest extends TestCase
{
    private $root;
    private $writer;

    protected function setUp(): void
    {
        $this->writer = new Writer();
        $this->root = vfsStream::setup('root');
    }

    public function testShouldFailIfTargetFileIsNotDefined(): void
    {
        $this->expectException(NoTargetFileDefined::class);
        $this->writer
            ->writeRow(['123', 'My name']);
    }

    public function testShouldCreateTheFile(): void
    {
        $file = vfsStream::url('root/one_line.csv');
        $this->writer
            ->toFile($file)
            ->writeRow(['123', 'My name']);
        $this->assertTrue($this->root->hasChild('root/one_line.csv'));
    }

    public function testShouldWriteARow(): void
    {
        $file = vfsStream::url('root/one_line.csv');
        $this->writer
            ->toFile($file)
            ->writeRow(['123', 'My name']);
        $content = $this->root->getChild('root/one_line.csv')->getContent();
        $this->assertEquals('123,"My name"', trim($content));
    }

    public function testShouldAllowToConfigureDelimiter(): void
    {
        $file = vfsStream::url('root/one_line.csv');
        $this->writer
            ->toFile($file)
            ->withDelimiter(';')
            ->writeRow(['123', 'My name']);
        $content = $this->root->getChild('root/one_line.csv')->getContent();
        $this->assertEquals('123;"My name"', trim($content));
    }

    public function testShouldAllowToConfigureEnclosure(): void
    {
        $file = vfsStream::url('root/one_line.csv');
        $this->writer
            ->toFile($file)
            ->withEnclosure('~')
            ->writeRow(['123', 'My name']);
        $content = $this->root->getChild('root/one_line.csv')->getContent();
        $this->assertEquals('123,~My name~', trim($content));
    }

    public function testShouldWriteHeadersIfPresent(): void
    {
        $file = vfsStream::url('root/multi_line_with_headers.csv');
        $this->writer
            ->toFile($file)
            ->writeRow(['id' => 123, 'name' => 'My name']);
        $this->writer
            ->writeRow(['id' => 456, 'name' => 'Another name']);
        $content = $this->root->getChild('root/multi_line_with_headers.csv')->getContent();
        $this->assertEquals('id,name' . PHP_EOL . '123,"My name"'. PHP_EOL . '456,"Another name"', trim($content));
    }

    public function testShouldWriteDtoWithFieldsAsHeaders(): void
    {
        $dto = $this->givenExampleDto('123', 'My name');

        $file = vfsStream::url('root/one_line_with_headers.csv');
        $this->writer
            ->toFile($file)
            ->writeRow($dto);
        $content = $this->root->getChild('root/one_line_with_headers.csv')->getContent();
        $this->assertEquals('id,name' . PHP_EOL . '123,"My name"', trim($content));
    }

    private function givenExampleDto($id, $name): RowDto
    {
        $dto = new RowDto();
        $dto->id = $id;
        $dto->name = $name;

        return $dto;
    }

}
