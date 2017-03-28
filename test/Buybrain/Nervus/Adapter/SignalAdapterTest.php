<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Handler\CallableSignaler;
use Buybrain\Nervus\Adapter\Message\Signal;
use Buybrain\Nervus\Adapter\Message\SignalAckRequest;
use Buybrain\Nervus\Adapter\Message\SignalRequest;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\MockIO;
use PHPUnit_Framework_TestCase;

class SignalAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testSignalAdapter()
    {
        $request = new SignalRequest();
        $signal = new Signal([new EntityId('test', '123')]);
        $response = new SignalAckRequest(true);

        $io = (new MockIO())->write($request)->write($response);

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

        $expected = '{"Codec":"json","AdapterType":"signal","Extra":{"Interval":10}}' .
            '{"Status":true,"Error":null,"Signal":{"Ids":[{"Type":"test","Id":"123"}]}}' . "\n" .
            '{"Status":true,"Error":null}';

        $this->assertEquals($expected, $io->writtenData());
        $this->assertTrue($ackResponse);
    }

    public function testErrorDuringSignal()
    {
        $io = (new MockIO())->write(new SignalRequest());

        $ackResponse = null;
        $SUT = (new SignalAdapter(new CallableSignaler(function (SignalCallback $callback) {
            throw new \RuntimeException('Oh no');
        })))
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec());

        $SUT->step();

        $expected = '{"Codec":"json","AdapterType":"signal","Extra":{"Interval":0}}' .
            '{"Status":false,"Error":"Oh no","Signal":null}';

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

        $expected = '{"Codec":"json","AdapterType":"signal","Extra":{"Interval":0}}' .
            '{"Status":true,"Error":null,"Signal":{"Ids":[]}}' . "\n" .
            '{"Status":false,"Error":"Oh no"}' . "\n" .
            '{"Status":true,"Error":null}';

        $this->assertEquals($expected, $io->writtenData());
    }
}
