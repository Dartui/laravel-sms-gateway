<?php

namespace Dartui\SmsGateway;

use Dartui\SmsGateway\Manager;
use Dartui\SmsGateway\Notification\Message;
use GuzzleHttp\Client as HttpClient;

class Client
{
    /**
     * Create SMS Gateway client.
     *
     * @param \Dartui\SmsGateway\Manager $manager
     * @param \GuzzleHttp\Clients $http
     * @return void
     */
    public function __construct(Manager $manager, HttpClient $http)
    {
        $this->manager = $manager;
        $this->http    = $http;
    }

    /**
     * Get all messages.
     *
     * @param  integer|null $page
     * @return object
     */
    public function getMessages($page = null)
    {
        return $this->getAll('messages', $page);
    }

    /**
     * Get single message.
     *
     * @param  integer $id
     * @return object
     */
    public function getMessage($id)
    {
        return $this->getSingle('messages', $id);
    }

    /**
     * Send message to single number.
     *
     * @param  \Dartui\SmsGateway\Notification\Message|array|string $message
     * @param  integer|string|null $number
     * @return object
     */
    public function sendMessage($message, $number = null)
    {
        $params = $this->getMessageParams($message, $number);
        $params = $this->manager->addDevice($params);

        return $this->request('post', 'messages/send', $params);
    }

    /**
     * Parse message to required request fields.
     *
     * @param  \Dartui\SmsGateway\Notification\Message|array|string $message
     * @param  integer|string|null $number
     * @return object
     */
    protected function getMessageParams($message, $number = null)
    {
        $params = [];

        if ($message instanceof Message) {
            $params = $message->toArray();
        } elseif (is_array($message)) {
            $params = $message;
        } elseif (is_string($message)) {
            $params['message'] = $message;
        }

        if (!is_null($number)) {
            $params['number'] = $number;
        }

        return $params;
    }

    /**
     * Get all devices.
     *
     * @param  integer|null $page
     * @return object
     */
    public function getDevices($page = null)
    {
        return $this->getAll('devices', $page);
    }

    /**
     * Get single device.
     *
     * @param  integer $id
     * @return object
     */
    public function getDevice($id)
    {
        return $this->getSingle('devices', $id);
    }

    /**
     * Get all contacts.
     *
     * @param  integer|null $page
     * @return object
     */
    public function getContacts($page = null)
    {
        return $this->getAll('contacts', $page);
    }

    /**
     * Get single contact.
     *
     * @param  integer $id
     * @return object
     */
    public function getContact($id)
    {
        return $this->getSingle('contacts', $id);
    }

    /**
     * Create contact.
     *
     * @param  string $name
     * @param  integer|string $number
     * @return object
     */
    public function createContact($name, $number)
    {
        $this->request('post', 'contacts/create', compact('name', 'number'));
    }

    /**
     * Helper for getting all rows of given type.
     *
     * @param  string $url
     * @param  integer|null $page
     * @return object
     */
    protected function getAll($url, $page = null)
    {
        return $this->request('get', $url, compact('page'));
    }

    /**
     * Helper for getting single row of given type.
     *
     * @param  string $url
     * @param  integer $id
     * @return object
     */
    protected function getSingle($url, $id)
    {
        $url = sprintf('%s/view/%d', $url, $id);

        return $this->request('get', $url);
    }

    /**
     * Make a request to SMS Gateway API.
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $params
     * @return object
     */
    protected function request($method, $url, $params = [])
    {
        $url = $this->manager->buildUrl($url);

        $params = $this->manager->addCredentials($params);

        $response = $this->http->request($method, $url, [
            $this->getRequestOption($method) => $params,
        ]);

        return $this->manager->getBody($response);
    }

    /**
     * Determine request option for sending values.
     *
     * @param  string $method
     * @return string
     */
    protected function getRequestOption($method)
    {
        $method = mb_strtoupper($method);

        return $method == 'GET' ? 'query' : 'form_params';
    }
}
