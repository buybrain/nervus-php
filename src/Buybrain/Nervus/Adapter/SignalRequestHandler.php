<?php
namespace Buybrain\Nervus\Adapter;

interface SignalRequestHandler
{
    public function onRequest(SignalCallback $callback);
}