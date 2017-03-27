<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Adapter\Config\SignalAdapterConfig;
use Buybrain\Nervus\Adapter\Handler\CallableSignaler;
use Buybrain\Nervus\Adapter\Message\Signal;
use Buybrain\Nervus\Adapter\Message\SignalAckRequest;
use Buybrain\Nervus\Adapter\Message\SignalAckResponse;
use Buybrain\Nervus\Adapter\Message\SignalRequest;
use Buybrain\Nervus\Adapter\Message\SignalResponse;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\TestIO;
use PHPUnit_Framework_TestCase;

class SignalAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testSignalAdapter()
    {
        $request = new SignalRequest();
        $signal = new Signal([new EntityId('test', '123')]);
        $response = new SignalAckRequest(true);

        $io = (new TestIO())->write($request)->write($response);

        $ackResponse = null;
        $SUT = (new SignalAdapter(new CallableSignaler(function (SignalCallback $callback) use ($signal, &$ackResponse) {
            $callback->onSignal($signal->getIds(), function ($ack) use (&$ackResponse) {
                $ackResponse = $ack;
            });
        })))
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec())
            ->interval(10);

        $SUT->step();

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'signal', new SignalAdapterConfig(10))) .
            $io->encode(SignalResponse::success($signal), SignalAckResponse::success());

        $this->assertEquals($expected, $io->writtenData());
        $this->assertTrue($ackResponse);
    }

    public function testErrorDuringSignal()
    {
        $io = (new TestIO())->write(new SignalRequest());

        $ackResponse = null;
        $SUT = (new SignalAdapter(new CallableSignaler(function (SignalCallback $callback) {
            throw new \RuntimeException('Oh no');
        })))
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec());

        $SUT->step();

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'signal', new SignalAdapterConfig(0))) .
            $io->encode(SignalResponse::error(new \RuntimeException('Oh no')));

        $this->assertEquals($expected, $io->writtenData());
    }

    public function testErrorDuringAck()
    {
        $io = (new TestIO())
            ->write(new SignalRequest())
            ->write(new SignalAckRequest(true)) // This one will fail, try again
            ->write(new SignalAckRequest(true)); // This one will succeed

        
        $counter = 0;
        $SUT = (new SignalAdapter(new CallableSignaler(function (SignalCallback $callback) use (&$counter) {
            $callback->onSignal([], function ($ack) use (&$counter) {
                if ($counter++ === 0) {
                    throw new \RuntimeException('Oh no');
                }
                // okay, no problem
            });
        })))
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec());

        $SUT->step();

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'signal', new SignalAdapterConfig(0))) .
            $io->encode(
                SignalResponse::success(new Signal([])),
                SignalAckResponse::error(new \RuntimeException('Oh no')),
                SignalAckResponse::success()
            );

        $this->assertEquals($expected, $io->writtenData());
    }
}
