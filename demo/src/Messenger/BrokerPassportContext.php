<?php

namespace App\Messenger;

use App\Messenger\Stamp\BrokerPassportStamp;

/** Thread-local passport aktuálně zpracovávané zprávy. */
final class BrokerPassportContext
{
    private ?BrokerPassportStamp $stamp = null;

    public function set(?BrokerPassportStamp $stamp): void
    {
        $this->stamp = $stamp;
    }

    public function get(): ?BrokerPassportStamp
    {
        return $this->stamp;
    }
}
