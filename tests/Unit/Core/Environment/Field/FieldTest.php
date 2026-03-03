<?php

/*
 * Tappet - Enjoyable GUI testing
 * Copyright (c) Dan Phillimore (asmblah)
 * https://github.com/nytris/tappet/
 *
 * Released under the MIT license.
 * https://github.com/nytris/tappet/raw/main/MIT-LICENSE.txt
 */

declare(strict_types=1);

namespace Tappet\Tests\Unit\Core\Environment\Field;

use Mockery\MockInterface;
use Tappet\Core\Automation\AutomationInterface;
use Tappet\Core\Environment\Field\Field;
use Tappet\Tests\AbstractTestCase;

/**
 * Class FieldTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class FieldTest extends AbstractTestCase
{
    private AutomationInterface&MockInterface $automation;
    private Field $field;

    public function setUp(): void
    {
        parent::setUp();

        $this->automation = mock(AutomationInterface::class);

        $this->field = new Field($this->automation, 'username');
    }

    public function testGetHandleReturnsHandle(): void
    {
        static::assertSame('username', $this->field->getHandle());
    }

    public function testTypeDelegatesToAutomation(): void
    {
        $this->automation->expects()
            ->typeField('username', 'hello world')
            ->once();

        $this->field->type('hello world');
    }

    public function testTypePassesCorrectTextToAutomation(): void
    {
        $this->automation->expects()
            ->typeField('username', 'another text')
            ->once();

        $this->field->type('another text');
    }
}
