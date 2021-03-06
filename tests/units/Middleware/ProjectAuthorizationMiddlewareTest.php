<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Middleware\ProjectAuthorizationMiddleware;

require_once __DIR__.'/../Base.php';

class ProjectAuthorizationMiddlewareMiddlewareTest extends Base
{
    /**
     * @var ProjectAuthorizationMiddleware
     */
    private $middleware;
    private $nextMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->container['helper'] = new stdClass();

        $this->container['helper']->user = $this
            ->getMockBuilder('Hiject\Helper\UserHelper')
            ->setConstructorArgs(array($this->container))
            ->setMethods(array('hasProjectAccess'))
            ->getMock();

        $this->container['request'] = $this
            ->getMockBuilder('Hiject\Core\Http\Request')
            ->setConstructorArgs(array($this->container))
            ->setMethods(array('getIntegerParam'))
            ->getMock();

        $this->nextMiddleware = $this
            ->getMockBuilder('Hiject\Middleware\ProjectAuthorizationMiddleware')
            ->setConstructorArgs(array($this->container))
            ->setMethods(array('execute'))
            ->getMock();

        $this->middleware = new ProjectAuthorizationMiddleware($this->container);
        $this->middleware->setNextMiddleware($this->nextMiddleware);
    }

    public function testWithAccessDenied()
    {
        $this->container['request']
            ->expects($this->any())
            ->method('getIntegerParam')
            ->will($this->returnValue(123));

        $this->container['helper']->user
            ->expects($this->once())
            ->method('hasProjectAccess')
            ->will($this->returnValue(false));

        $this->nextMiddleware
            ->expects($this->never())
            ->method('execute');

        $this->setExpectedException('Hiject\Core\Controller\AccessForbiddenException');
        $this->middleware->execute();
    }

    public function testWithAccessGranted()
    {
        $this->container['request']
            ->expects($this->any())
            ->method('getIntegerParam')
            ->will($this->returnValue(123));

        $this->container['helper']->user
            ->expects($this->once())
            ->method('hasProjectAccess')
            ->will($this->returnValue(true));

        $this->nextMiddleware
            ->expects($this->once())
            ->method('execute');

        $this->middleware->execute();
    }
}
