<?php namespace Prspace\Mailjet;

use Prspace\Mailjet\Transport\MailjetTransport;
use Illuminate\Mail\TransportManager as BaseTransportManager;

class TransportManager extends BaseTransportManager {

    /**
     * Create an instance of the Mailjet Swift Transport driver.
     *
     * @return MailjetTransport
     */
    protected function createMailjetDriver()
    {
        $config = $this->app['config']->get('services.mailjet', array());

        return new MailjetTransport($config['key'], $config['secret']);
    }

}
