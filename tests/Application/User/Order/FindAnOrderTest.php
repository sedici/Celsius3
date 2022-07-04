<?php

declare(strict_types=1);

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\Tests\Application\User\Order;

use Celsius3\Application\User\Order\UserOrderFinder;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\Repository\OrderRepositoryInterface;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class FindAnOrderTest extends TestCase
{
    /**
     * @var OrderRepositoryInterface|MockInterface
     */
    private $orderRepository;

    public function testItShouldFindACategory(): void
    {
        $id = 1;
        $instance = new Instance();
        $user = new BaseUser();
        $searched = new Order();

        $this->orderRepository
            ->shouldReceive('findUserOrder')
            ->with($id, $instance, $user)
            ->once()
            ->andReturn($searched);

        $order = (new UserOrderFinder($this->orderRepository))($id, $instance, $user);

        self::assertEquals($searched, $order);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = Mockery::mock(OrderRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }
}
