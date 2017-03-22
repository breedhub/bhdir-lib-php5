<?php

namespace bhdir;

require('socket.php');

class Directory {
    private $socket;

    public function __construct($path = null) {
        $this->socket = new Socket($path);
    }

    public function set($name, $value) {
        if ($value === null)
            throw new \Exception('Invlid value');

        $request = [
            'id' => uniqid('', true),
            'command' => 'set',
            'args' => [
                $name,
                $value
            ]
        ];

        $this->socket->send(json_encode($request));
        $response = json_decode($this->socket->receive(), true);

        if ($response['id'] != $request['id'])
            throw new \Exception('Invalid response from server');

        if (!$response['success'])
            throw new \Exception('Error: ' + $response['message']);
    }

    public function get($name) {
        $request = [
            'id' => uniqid('', true),
            'command' => 'get',
            'args' => [
                $name
            ]
        ];

        $this->socket->send(json_encode($request));
        $response = json_decode($this->socket->receive(), true);

        if ($response['id'] != $request['id'])
            throw new \Exception('Invalid response from server');

        if (!$response['success'])
            throw new \Exception('Error: ' + $response['message']);

        return $response['results'][0];
    }

    public function delete($name) {
        $request = [
            'id' => uniqid('', true),
            'command' => 'unset',
            'args' => [
                $name
            ]
        ];

        $this->socket->send(json_encode($request));
        $response = json_decode($this->socket->receive(), true);

        if ($response['id'] != $request['id'])
            throw new \Exception('Invalid response from server');

        if (!$response['success'])
            throw new \Exception('Error: ' + $response['message']);
    }

    public function wait($name, $timeout = 0) {
        $request = [
            'id' => uniqid('', true),
            'command' => 'wait',
            'args' => [
                $name,
                round($timeout * 1000)
            ]
        ];

        $this->socket->send(json_encode($request));
        $response = json_decode($this->socket->receive(), true);

        if ($response['id'] != $request['id'])
            throw new \Exception('Invalid response from server');

        if (!$response['success'])
            throw new \Exception('Error: ' + $response['message']);

        if ($response['timeout'])
            return $this->get($name);

        return $response['results'][0];
    }

    public function touch($name) {
        $request = [
            'id' => uniqid('', true),
            'command' => 'touch',
            'args' => [
                $name
            ]
        ];

        $this->socket->send(json_encode($request));
        $response = json_decode($this->socket->receive(), true);

        if ($response['id'] != $request['id'])
            throw new \Exception('Invalid response from server');

        if (!$response['success'])
            throw new \Exception('Error: ' + $response['message']);
    }
}
