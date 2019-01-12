<?php
namespace Evoweb\SfRegister\Tests\Unit\Domain\Model;

/*
 * This file is developed by evoWeb.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class PasswordTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Evoweb\SfRegister\Domain\Model\Password
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = new \Evoweb\SfRegister\Domain\Model\Password();
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function passwordOnInitializeIsNull()
    {
        $this->assertNull($this->subject->getPassword());
    }

    /**
     * @test
     */
    public function passwordReturnsStringSetBySetPassword()
    {
        $expected = 'test string';

        $this->subject->setPassword($expected);

        $this->assertSame($expected, $this->subject->getPassword());
    }

    /**
     * @test
     */
    public function passwordRepeatOnInitializeIsNull()
    {
        $this->assertNull($this->subject->getPasswordRepeat());
    }

    /**
     * @test
     */
    public function passwordAgainReturnsStringSetBySetPassword()
    {
        $expected = 'test string';

        $this->subject->setPasswordRepeat($expected);

        $this->assertSame($expected, $this->subject->getPasswordRepeat());
    }

    /**
     * @test
     */
    public function oldPasswordOnInitializeIsNull()
    {
        $this->assertNull($this->subject->getOldPassword());
    }

    /**
     * @test
     */
    public function oldPasswordReturnsStringSetBySetPassword()
    {
        $expected = 'test string';

        $this->subject->setOldPassword($expected);

        $this->assertSame($expected, $this->subject->getOldPassword());
    }
}
