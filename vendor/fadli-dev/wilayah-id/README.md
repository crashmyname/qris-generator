# WilayahID ✨
- Versi 1.0

## Installation ✨✨
- composer require fadli-dev/wilayah-id

## Penggunaan Library
- Ada Beberapa function dalam library ini yaitu :
    - getAllProvincies()
    - Untuk Memanggil semua provincies
    - Example
```php
require 'vendor/autoload.php';

use Region\WilayahID\WilayahID;

$data = WilayahID::getAllProvincies();
foreach($data as $provincies){
    $provincies->kode_provinsi
    $provincies->nama_provinsi
}
```
    - getProvincies()
    - Untuk memanggil data provinsi tertentu
    - Example
```php
$data = WilayahID::getProvincies(11);
echo $data->nama_provinsi;

<-- hasilnya = 'Banten'; -->
```
    - getRegencies()
    - Untuk memanggil data provinsi tertentu
    - Example
```php
$data = WilayahID::getProvincies(11)->getRegencies(1101);
echo $data->nama_kabupaten;

<-- hasilnya = 'Tangerang'; -->
```
    - getDistricts()
    - Untuk memanggil data provinsi tertentu
    - Example
```php
$data = WilayahID::getProvincies(11)->getRegencies(1101)->getDistricts(110101);
echo $data->nama_kecamatan;

<-- hasilnya = 'Bogor'; -->
```
    - getVillages()
    - Untuk memanggil data provinsi tertentu
    - Example
```php
$data = WilayahID::getProvincies(11)->getRegencies(1101)->getDistricts(110101)->getVillages(1101012003);
echo $data->nama_desa;

<-- hasilnya = 'Bogor Nangka'; -->
```
    - getPosCode()
    - Untuk memanggil data provinsi tertentu
    - Example
```php
$data = WilayahID::getProvincies(11)->getRegencies(1101)->getDistricts(110101)->getVillages(1101012003)->getPosCode();
print_r($data);

<-- hasilnya = 'stdClass Object ( [province] => stdClass Object ( [kode_provinsi] => 11 [nama_provinsi] => Aceh (NAD) ) [regency] => stdClass Object ( [kode_kabupaten] => 1101 [nama_kabupaten] => Aceh Selatan [kode_provinsi] => 11 ) [district] => stdClass Object ( [kode_kabupaten] => 1101 [kode_kecamatan] => 110101 [nama_kecamatan] => Bakongan ) [village] => stdClass Object ( [kode_kecamatan] => 110101 [kode_desa] => 1101012003 [nama_desa] => Ujong Padang (Ujung Padang) ) [kode_pos] => 23773 )' -->

jika manggil data tertentu tinggal 
echo $data->province->nama_provinsi
<-- Hasilnya = 'Aceh' -->
```