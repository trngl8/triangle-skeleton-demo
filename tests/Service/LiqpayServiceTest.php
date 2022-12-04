<?php

namespace App\Tests\Service;

use App\Service\LiqpayService;
use App\Service\Payment\LiqPayAPI;
use PHPUnit\Framework\TestCase;

class LiqpayServiceTest extends TestCase
{
    public function testFormSuccess() :void
    {
        $apiClient = $this->createMock(LiqPayAPI::class);
        $apiClient->expects($this->once())
            ->method('getFormParams')
            ->willReturn([
                'data' => 'test',
                'signature' => 'test'
            ]);
        $apiClient->expects($this->once())
            ->method('api')
            ->willReturn([
                'status' => 'success'
            ]);

        $liqpayService = new LiqpayService($apiClient);

        $this->assertIsArray($liqpayService->getFormParams([]));
        $this->assertIsArray($liqpayService->apiCall('test', []));
    }
}
