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

    public function put_fd($fd, $name) {
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

    public function put_file($filename, $name) {
        $fd = fopen($filename, 'r');
        if (!$fd)
            throw new \Exception('Could not open ' . $filename);
        $result = $this->put_fd($fd, $name);
        fclose($fd);
        return $result;
    }

    public function get_fd($name, $fd = null) {
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

        $contents = base64_decode($response['results'][0]);

        if (!$fd) {
            $fd = fopen('/tmp/' . $this->random_filename(), 'w+');
            if (!$fd)
                throw new \Exception('Could not open temporary file');
        }

        fwrite($fd, $contents);
        fseek($fd, 0);

        return $fd;
    }

    public function get_file($name, $filename) {
        $fd = fopen($filename, 'w');
        if (!$fd)
            throw new \Exception('Could not open ' . $filename);
        $this->get_fd($name, $fd);
        fclose($fd);
    }

    private function random_filename() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 8; $i++)
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        return $randomString;
    }
}
