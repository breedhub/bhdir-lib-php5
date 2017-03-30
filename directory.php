<?php

namespace bhdir;

require('socket.php');

class Directory {
    private $socket;

    public function __construct($path = null) {
        $this->socket = new Socket($path);
    }

    public function ls($name) {
        $request = [
            'id' => uniqid('', true),
            'command' => 'ls',
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

    public function set($name, $value) {
        if ($value === null)
            throw new \Exception('Invalid value');

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

        return $response['results'][0];
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
            'command' => 'del',
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

    public function rm($name) {
        $request = [
            'id' => uniqid('', true),
            'command' => 'rm',
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

    public function set_attr($var_name, $attr_name, $value) {
        if ($value === null)
            throw new \Exception('Invalid value');

        $request = [
            'id' => uniqid('', true),
            'command' => 'set-attr',
            'args' => [
                $var_name,
                $attr_name,
                $value
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

    public function get_attr($var_name, $attr_name = null) {
        $request = [
            'id' => uniqid('', true),
            'command' => 'get-attr',
            'args' => [
                $var_name,
                $attr_name
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

    public function delete_attr($var_name, $attr_name) {
        $request = [
            'id' => uniqid('', true),
            'command' => 'del-attr',
            'args' => [
                $var_name,
                $attr_name
            ]
        ];

        $this->socket->send(json_encode($request));
        $response = json_decode($this->socket->receive(), true);

        if ($response['id'] != $request['id'])
            throw new \Exception('Invalid response from server');

        if (!$response['success'])
            throw new \Exception('Error: ' + $response['message']);
    }

    public function upload($name, $fd) {
        $contents = '';
        while (!feof($fd))
            $contents .= fread($fd, 8192);

        $request = [
            'id' => uniqid('', true),
            'command' => 'upload',
            'args' => [
                $name,
                base64_encode($contents)
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

    public function download($name, $fd) {
        $request = [
            'id' => uniqid('', true),
            'command' => 'download',
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

        fwrite($fd, base64_decode($response['results'][0]));
    }
}
