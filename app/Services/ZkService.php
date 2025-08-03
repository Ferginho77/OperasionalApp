<?php

namespace App\Services;

// Ganti use ZKLib\ZKLib;
use Rats\Zkteco\Lib\Zkteco;

class ZkService
{
    protected $zk;

    public function __construct($ipAddress, $port = 4370)
    {
        // Ganti new ZKLib(...) dengan new Zkteco(...)
        $this->zk = new Zkteco($ipAddress, $port);
    }

    public function connect()
    {
        // Metode untuk koneksi tetap sama
        return $this->zk->connect();
    }

    public function disconnect()
    {
        // Metode untuk disconeksi tetap sama
        return $this->zk->disconnect();
    }

    public function getAttendance()
    {
        // Metode untuk mengambil data absensi tetap sama
        return $this->zk->getAttendance();
    }
}