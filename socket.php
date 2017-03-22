<?php

namespace bhdir;

class Socket {
    private $sock = null;
    private $path = '/var/run/bhdir/daemon.sock';
    private $connected = false;

    public function __construct($path = null) {
        if ($path)
            $this->path = $path;
    }

    public function connect() {
        if ($this->connected)
            return;

//        if (!file_exists($this->path))
//            throw new \Exception('Daemon is not listening to socket');

        $this->sock = stream_socket_client('unix://' . $this->path, $errno, $errstr);
        if (!$this->sock)
            throw new \Exception($errstr);

        $this->connected = true;
    }

    public function disconnect() {
        if (!$this->connected);
            return;

        fclose($this->sock);
        $this->connected = false;
    }

    public function send($msg, $leave_open = true) {
        $this->connect();

        $length = pack('N', mb_strlen($msg));
        $sent = fwrite($this->sock, $length);
        if ($sent != 4)
            throw new \Exception("Socket terminated");
        $sent = fwrite($this->sock, $msg);
        if ($sent != mb_strlen($msg))
            throw new \Exception("Socket terminated");

        if (!$leave_open)
            $this->disconnect();
    }

    public function receive($leave_open = true) {
        $this->connect();

        $chunk = fread($this->sock, 4);
        if (!$chunk)
            throw new \Exception("socket terminated");
        $data = unpack('Nlength', $chunk);
        $length = $data['length'];

        $chunks = [];
        $bytes_recd = 0;
        while ($bytes_recd < $length) {
            $next_length = min($length - $bytes_recd, 2048);
            $chunk = fread($this->sock, $next_length);
            if (!$chunk)
                throw new \Exception("socket terminated");
            $chunks[] = $chunk;
            $bytes_recd = $bytes_recd + $next_length;
        }

        if (!$leave_open)
            $this->disconnect();

        return join('', $chunks);
    }
}    
