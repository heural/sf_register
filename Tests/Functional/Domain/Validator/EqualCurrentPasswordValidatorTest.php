<?php
namespace Evoweb\SfRegister\Tests\Functional\Domain\Validator;

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

use Evoweb\SfRegister\Domain\Repository\FrontendUserRepository;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;

class EqualCurrentPasswordValidatorTest extends \Evoweb\SfRegister\Tests\Functional\FunctionalTestCase
{
    /**
     * @var \Evoweb\SfRegister\Validation\Validator\EqualCurrentPasswordValidator|AccessibleObjectInterface
     */
    protected $subject;

    public function setUp()
    {
        parent::setUp();
        $this->importDataSet(__DIR__. '/../Fixtures/pages.xml');
        $this->importDataSet(__DIR__. '/../Fixtures/sys_template.xml');
        $this->importDataSet(__DIR__. '/../Fixtures/fe_groups.xml');
        $this->importDataSet(__DIR__. '/../Fixtures/fe_users.xml');

        $this->subject = $this->getAccessibleMock(
            \Evoweb\SfRegister\Validation\Validator\EqualCurrentPasswordValidator::class,
            ['dummy']
        );
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function settingsContainsValidTyposcriptSettings()
    {
        $this->assertArrayHasKey(
            'badWordList',
            $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sfregister.']['settings.']
        );
    }

    /**
     * @test
     */
    public function isUserLoggedInReturnsFalseIfNotLoggedIn()
    {
        $this->assertFalse($this->subject->_call('isUserLoggedIn'));
    }

    /**
     * @test
     */
    public function isUserLoggedInReturnsTrueIfLoggedIn()
    {
        $this->createAndLoginFrontEndUser('2', ['password' => 'testOld']);

        $this->assertTrue($this->subject->_call('isUserLoggedIn'));
    }

    /**
     * @test
     */
    public function loggedinUserFoundInDbHasEqualUnencryptedPassword()
    {
        if (isset($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sfregister.']['settings.']['encryptPassword'])) {
            unset($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sfregister.']['settings.']['encryptPassword']);
        }
        $this->subject->_set('settings', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sfregister.']['settings.']);

        $expected = 'myFancyPassword';

        $userId = $this->createAndLoginFrontEndUser('2', ['password' => $expected]);

        /** @var \Evoweb\SfRegister\Domain\Model\FrontendUser|\PHPUnit_Framework_MockObject_MockObject $userMock */
        $userMock = $this->getAccessibleMock(\Evoweb\SfRegister\Domain\Model\FrontendUser::class);
        $userMock->expects($this->any())->method('getPassword')->willReturn($expected);

        /** @var FrontendUserRepository|\PHPUnit_Framework_MockObject_MockObject $repositoryMock */
        $repositoryMock = $this->getAccessibleMock(
            FrontendUserRepository::class,
            ['dummy'],
            [],
            '',
            false
        );
        $repositoryMock->expects($this->once())
            ->method('findByUid')
            ->with($userId)
            ->will($this->returnValue($userMock));
        $this->subject->injectUserRepository($repositoryMock);

        $this->assertTrue($this->subject->isValid($expected));
    }

    /**
     * @test
     */
    public function loggedinUserFoundInDbHasEqualMd5EncryptedPassword()
    {
        $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sfregister.']['settings.']['encryptPassword'] = 'md5';

        $this->subject->_set('settings', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sfregister.']['settings.']);

        $expected = 'myFancyPassword';

        $userId = $this->createAndLoginFrontEndUser('2', ['password' => $expected]);

        /** @var \Evoweb\SfRegister\Domain\Model\FrontendUser|\PHPUnit_Framework_MockObject_MockObject $userMock */
        $userMock = $this->getAccessibleMock(\Evoweb\SfRegister\Domain\Model\FrontendUser::class);
        $userMock->expects($this->any())->method('getPassword')->willreturn($expected);

        /** @var FrontendUserRepository|\PHPUnit_Framework_MockObject_MockObject $repositoryMock */
        $repositoryMock = $this->getAccessibleMock(
            FrontendUserRepository::class,
            ['dummy'],
            [],
            '',
            false
        );
        $repositoryMock->expects($this->once())
            ->method('findByUid')
            ->with($userId)
            ->will($this->returnValue($userMock));
        $this->subject->injectUserRepository($repositoryMock);

        $this->assertTrue($this->subject->isValid($expected));
    }
}
