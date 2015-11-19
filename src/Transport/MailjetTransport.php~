<?php namespace Sboo\Laravel5Mailjet\Transport;

use GuzzleHttp\Post\PostBody;
use Swift_Transport;
use GuzzleHttp\Client;
use Swift_Mime_Message;
use GuzzleHttp\Post\PostFile;
use Swift_Events_EventListener;

class MailjetTransport implements Swift_Transport {

    /**
     * The Mailjet API key.
     *
     * @var string
     */
    protected $key;

    /**
     * The Mailjet API secret.
     *
     * @var string
     */
    protected $secret;

    /**
     * THe Mailjet API end-point.
     *
     * @var string
     */
    protected $url;

    /**
     * Create a new Mailgun transport instance.
     *
     * @param  string  $key
     * @param  string  $secret
     * @return void
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->url = 'https://api.mailjet.com/v3/send/message';
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $client = $this->getHttpClient();

        return $client->post($this->url, ['auth' => [$this->key, $this->secret],
            'body' => $this->getBody($message)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
        //
    }

    /**
     * Get the "to" payload field for the API request.
     *
     * @param  \Swift_Mime_Message  $message
     * @return array
     */
    protected function getTo(Swift_Mime_Message $message)
    {
        $formatted = [];

        $contacts = array_merge(
            (array) $message->getTo(), (array) $message->getCc(), (array) $message->getBcc()
        );

        foreach ($contacts as $address => $display)
        {
            $formatted[] = $display ? $display." <$address>" : $address;
        }

        return implode(',', $formatted);
    }

    /**
     * Get the "from" payload field for the API request.
     *
     * @param  \Swift_Mime_Message  $message
     * @return array
     */
    protected function getFrom(Swift_Mime_Message $message)
    {
        $formatted = [];
        foreach ($message->getFrom() as $address => $display)
        {
            $formatted[] = $display ? $display." <$address>" : $address;
        }

        return $formatted[0];
    }

    /**
     * Get the "body" payload field for the Guzzle request.
     *
     * @param Swift_Mime_Message $message
     * @return PostBody
     */
    protected function getBody(Swift_Mime_Message $message) {
        $body = new PostBody();
        $body->setField('from', $this->getFrom($message));
        $body->setField('to',   $this->getTo($message) );
        $body->setField('subject',   $message->getSubject() );

        $messageHtml = $message->getBody();
        if($message->getChildren()) {
            foreach($message->getChildren() as $child) {

                if(str_contains($messageHtml, $child->getId())) {
                    $messageHtml = str_replace($child->getId(),$child->getFilename(),$messageHtml);
                    $body->addFile(new PostFile(
                            'inlineattachment',
                            $child->getBody(),
                            $child->getFilename(),
                            ['Content-Type' => $child->getContentType()]
                        )
                    );
                } else {
                    switch(get_class($child)) {
                        case 'Swift_Attachment':
                        case 'Swift_Image':
                            $body->addFile(new PostFile(
                                    'attachment',
                                    $child->getBody(),
                                    $child->getFilename(),
                                    ['Content-Type' => $child->getContentType()]
                                )
                            );
                            break;
                        case 'Swift_MimePart':
                            switch($child->getContentType()){
                                case 'text/plain':
                                    $body->setField('text',   $child->getBody() );
                                    break;
                            }
                            break;
                    }
                }
            }
        }
        $body->setField('html',   $messageHtml );
        return $body;
    }

    /**
     * Get a new HTTP client instance.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        return new Client;
    }

    /**
     * Get the API key being used by the transport.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the API key being used by the transport.
     *
     * @param  string  $key
     * @return void
     */
    public function setKey($key)
    {
        return $this->key = $key;
    }

    /**
     * Get the API secret being used by the transport.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set the API secret being used by the transport.
     *
     * @param  string  $secret
     * @return void
     */
    public function setSecret($secret)
    {
        return $this->secret = $secret;
    }

}