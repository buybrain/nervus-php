<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Handler\CallableSignaler;
use Buybrain\Nervus\Adapter\Message\Signal;
use Buybrain\Nervus\Adapter\Message\SignalAckRequest;
use Buybrain\Nervus\Adapter\Message\SignalRequest;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\MockIO;
use PHPUnit_Framework_TestCase;
use RuntimeException;

class SignalAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testSignalAdapter()
    {
        $request = new SignalRequest();
        $signal = new Signal([new EntityId('test', '123')]);
        $ackRequest = new SignalAckRequest(true);

        $io = (new MockIO())->write($request)->write($ackRequest);

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

        $expected = '{"codec":"json","adapterType":"signal","extra":{"interval":10}}' .
            '{"status":true,"error":null,"signal":{"ids":[{"type":"test","id":"123"}]}}' . "\n" .
            '{"status":true,"error":null}';

        $this->assertEquals($expected, $io->writtenData());
        $this->assertTrue($ackResponse);
    }

    public function testErrorDuringSignal()
    {
        $io = (new MockIO())->write(new SignalRequest());

        $SUT = (new SignalAdapter(new CallableSignaler(function (SignalCallback $callback) {
            throw new RuntimeException('Oh no');
        })))
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec());

        $SUT->step();

        $expected = '{"codec":"json","adapterType":"signal","extra":{"interval":0}}' .
            '{"status":false,"error":"Oh no","signal":null}';

        $this->assertEquals($expected, $io->writtenData());
    }

    public function testErrorDuringAck()
    {
        $io = (new MockIO())
            ->write(new SignalRequest())
            ->write(new SignalAckRequest(true))// This one will fail, try again
            ->write(new SignalAckRequest(true)); // This one will succeed

        $counter = 0;
        $SUT = (new SignalAdapter(new CallableSignaler(function (SignalCallback $callback) use (&$counter) {
            $callback->onSignal([], function ($ack) use (&$counter) {
                $counter++;
                if ($counter === 1) {
                    // First time, error
                    throw new RuntimeException('Oh no');
                }
                // Second time, no problem
            });
        })))
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec());

        $SUT->step();

        $expected = '{"codec":"json","adapterType":"signal","extra":{"interval":0}}' .
            '{"status":true,"error":null,"signal":{"ids":[]}}' . "\n" .
            '{"status":false,"error":"Oh no"}' . "\n" .
            '{"status":true,"error":null}';

        $this->assertEquals($expected, $io->writtenData());
    }
}
