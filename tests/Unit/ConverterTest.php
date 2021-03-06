<?php

declare(strict_types=1);

namespace PhpCfdi\CfdiToPdf\Tests\Unit;

use PhpCfdi\CfdiToPdf\Builders\BuilderInterface;
use PhpCfdi\CfdiToPdf\CfdiData;
use PhpCfdi\CfdiToPdf\Converter;
use PhpCfdi\CfdiToPdf\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \PhpCfdi\CfdiToPdf\Converter
 */
class ConverterTest extends TestCase
{
    public function testCreatePdfToTemporary()
    {
        /** @var CfdiData&MockObject $fakeCfdiData */
        $fakeCfdiData = $this->createMock(CfdiData::class);
        /** @var BuilderInterface&MockObject $fakeBuilder */
        $fakeBuilder = $this->createMock(BuilderInterface::class);
        $fakeBuilder->expects($spy = $this->once())->method('build');

        $converter = new Converter($fakeBuilder);
        $temporaryFile = $converter->createPdf($fakeCfdiData);
        $this->assertFileExists($temporaryFile);
        unlink($temporaryFile);

        $this->assertTrue($spy->hasBeenInvoked());
        $this->assertSame(
            [$fakeCfdiData, $temporaryFile],
            $spy->getInvocations()[0]->getParameters()
        );
    }

    public function testCreatePdfToFile()
    {
        /** @var CfdiData&MockObject $fakeCfdiData */
        $fakeCfdiData = $this->createMock(CfdiData::class);
        /** @var BuilderInterface&MockObject $fakeBuilder */
        $fakeBuilder = $this->createMock(BuilderInterface::class);
        $fakeBuilder->expects($spy = $this->once())->method('build');

        $converter = new Converter($fakeBuilder);
        $temporaryFile = 'foo-bar';
        $converter->createPdfAs($fakeCfdiData, $temporaryFile);
        $this->assertTrue($spy->hasBeenInvoked());
        $this->assertSame(
            [$fakeCfdiData, $temporaryFile],
            $spy->getInvocations()[0]->getParameters()
        );
    }
}
