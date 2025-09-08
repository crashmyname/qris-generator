<?php

namespace Region\WilayahID;

class WilayahID {
    public static $selectedProvinceCode;

    public static function getDataProvincies() {
        return self::readJsonFile(__DIR__.'/../data/provinces.json');
    }

    public static function getDataRegencies() {
        return self::readJsonFile(__DIR__.'/../data/regencies.json');
    }

    public static function getDataDistrict() {
        return self::readJsonFile(__DIR__.'/../data/district.json');
    }

    public static function getDataVillage() {
        return self::readJsonFile(__DIR__.'/../data/village.json');
    }

    public static function getPosCodeData() {
        return self::readJsonFile(__DIR__.'/../data/poscode.json');
    }

    private static function readJsonFile($file) {
        if (file_exists($file)) {
            $json = file_get_contents($file);
            return json_decode($json, true);
        }
        return [];
    }

    public static function getProvincies($codeprovinces) {
        self::$selectedProvinceCode = $codeprovinces;
        $provinces = self::getDataProvincies();
        foreach ($provinces as $province) {
            if ($province['kode_provinsi'] == $codeprovinces) {
                return new WilayahProvince($province);
            }
        }
        return null;
    }

    public static function getAllProvincies()
    {
        $provinces = self::getDataProvincies();
        return array_map(fn($p) => (object) $p, $provinces);
    }
}

class WilayahProvince {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function __get($key) {
        return $this->data[$key] ?? null;
    }

    public function getRegencies($coderegencies = null) {
        $regencies = array_filter(WilayahID::getDataRegencies(), function($r) {
            return $r['kode_provinsi'] == $this->data['kode_provinsi'];
        });

        if ($coderegencies) {
            foreach ($regencies as $regency) {
                if ($regency['kode_kabupaten'] == $coderegencies) {
                    return new WilayahRegency($regency, $this->data);
                }
            }
            return null;
        }

        return array_map(fn($r) => (object) $r, $regencies);
    }
}

class WilayahRegency {
    private $data;
    private $province;

    public function __construct($data, $province) {
        $this->data = $data;
        $this->province = $province;
    }

    public function __get($key) {
        return $this->data[$key] ?? null;
    }

    public function getDistricts($codedistrict = null) {
        $districts = array_filter(WilayahID::getDataDistrict(), function($d) {
            return $d['kode_kabupaten'] == $this->data['kode_kabupaten'];
        });

        if ($codedistrict) {
            foreach ($districts as $district) {
                if ($district['kode_kecamatan'] == $codedistrict) {
                    return new WilayahDistrict($district, $this->data, $this->province);
                }
            }
            return null;
        }

        return array_map(fn($d) => (object) $d, $districts);
    }
}

class WilayahDistrict {
    private $data;
    private $regency;
    private $province;

    public function __construct($data, $regency, $province) {
        $this->data = $data;
        $this->regency = $regency;
        $this->province = $province;
    }

    public function __get($key) {
        return $this->data[$key] ?? null;
    }

    public function getVillages($codevillage = null) {
        $villages = array_filter(WilayahID::getDataVillage(), function($v) {
            return $v['kode_kecamatan'] == $this->data['kode_kecamatan'];
        });

        if ($codevillage) {
            foreach ($villages as $village) {
                if ($village['kode_desa'] == $codevillage) {
                    return new WilayahVillage($village, $this->data, $this->regency, $this->province);
                }
            }
            return null;
        }

        return array_map(fn($v) => (object) $v, $villages);
    }
}

class WilayahVillage {
    private $data;
    private $district;
    private $regency;
    private $province;

    public function __construct($data, $district, $regency, $province) {
        $this->data = $data;
        $this->district = $district;
        $this->regency = $regency;
        $this->province = $province;
    }

    public function __get($key) {
        return $this->data[$key] ?? null;
    }

    public function getPosCode() {
        $posData = WilayahID::getPosCodeData();

        foreach ($posData as $pos) {
            if ($pos['kode_desa'] == $this->data['kode_desa']) {
                $data = [
                    'province'  => $this->province,
                    'regency'   => $this->regency,
                    'district'  => $this->district,
                    'village'   => (object) $this->data,
                    'kode_pos'  => $pos['kode_pos'],
                ];
                return json_decode(json_encode($data));
            }
        }

        return null;
    }
}