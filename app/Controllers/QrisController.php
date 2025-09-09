<?php

namespace App\Controllers;

use App\Models\Qris;
use App\Services\MerchantServices;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Helpers\BaseController;
use Bpjs\Core\Request;
use Helpers\Date;
use Helpers\Response;
use Helpers\Session;
use Helpers\Validator;
use Helpers\View;
use Helpers\CSRFToken;
use Zxing\QrReader;

class QrisController extends BaseController
{
    // Controller logic here
    public function CRC($payload)
    {
        $crc = 0xFFFF;
        $polynomial = 0x1021;
        $payload .= "6304";

        for ($i = 0; $i < strlen($payload); $i++) {
            $crc ^= (ord($payload[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if (($crc & 0x8000) != 0) {
                    $crc = (($crc << 1) ^ $polynomial) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    public function generate(Request $request, $invoiceId = null)
    {
        $cekmerchant = Qris::query()->where('userId','=',Session::user()->userId)->first();
        if(!$cekmerchant){
            return redirect('decoder');
        }
        $merchantvendor = str_pad(strlen($cekmerchant->merchantvendor),2,'0',STR_PAD_LEFT);
        $merchantid = str_pad(strlen($cekmerchant->merchantid),2,'0',STR_PAD_LEFT);
        $merchantcriteria = str_pad(strlen($cekmerchant->merchantcriteria),2,'0',STR_PAD_LEFT);
        $merchanttype = str_pad(strlen($cekmerchant->merchanttype),2,'0',STR_PAD_LEFT);
        $merchantcategory = str_pad(strlen($cekmerchant->merchantcategory),2,"0",STR_PAD_LEFT);
        $merchantcurrency = str_pad(strlen($cekmerchant->merchantcurrency),2,'0',STR_PAD_LEFT);
        $countryid = str_pad(strlen($cekmerchant->countryid),2,'0',STR_PAD_LEFT);
        $merchantname = str_pad(strlen($cekmerchant->merchantname),2,'0',STR_PAD_LEFT);
        $merchantcity = str_pad(strlen($cekmerchant->merchantcity),2,'0',STR_PAD_LEFT);
        $merchantpostalcode = str_pad(strlen($cekmerchant->merchantpostalcode),2,'0',STR_PAD_LEFT);
        $total = strlen('00'.$merchantvendor.$cekmerchant->merchantvendor)+strlen('01'.$merchantid.$cekmerchant->merchantid)+strlen('02'.$merchantcriteria.$cekmerchant->merchantcriteria)+strlen('03'.$merchanttype.$cekmerchant->merchanttype);

        $payload  = "000201";        
        $payload .= "010212";        

        // Merchant INFO
        $payload .= '26'.$total;
        $payload .= '00'.$merchantvendor.$cekmerchant->merchantvendor;
        $payload .= '01'.$merchantid.$cekmerchant->merchantid; 
        $payload .= '02'.$merchantcriteria.$cekmerchant->merchantcriteria;        
        $payload .= '03'.$merchanttype.$cekmerchant->merchanttype; 

        $payload .= '52'.$merchantcategory.$cekmerchant->merchantcategory;      
        $payload .= '53'.$merchantcurrency.$cekmerchant->merchantcurrency;       

        if ($request->amount !== null) {
            $amt = number_format($request->amount, 2, '.', '');
            $payload .= "54" . str_pad(strlen($amt), 2, "0", STR_PAD_LEFT) . $amt;
        }

        $payload .= '58'.$countryid.$cekmerchant->countryid;       
        $payload .= '59'.$merchantname.$cekmerchant->merchantname;  
        $payload .= '60'.$merchantcity.$cekmerchant->merchantcity;  
        $payload .= '61'.$merchantpostalcode.$cekmerchant->merchantpostalcode;         

        if ($invoiceId !== null) {
            $addData = "01" . str_pad(strlen($invoiceId), 2, "0", STR_PAD_LEFT) . $invoiceId;
            $payload .= "62" . str_pad(strlen($addData), 2, "0", STR_PAD_LEFT) . $addData;
        }

        $crc = $this->CRC($payload);
        $payload .= "6304" . $crc;

        $options = new QROptions([
            'version'      => 15, 
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG, 
            'eccLevel'     => QRCode::ECC_L, 
            'scale'        => 10,
            'imageBase64'  => false, 
            'quietzoneSize'=> 4, 
        ]);

        $qrcode = new QRCode($options);
        
        $directory = __DIR__ . '/../../public/barcode';
        $name = 'qris_'.$request->amount.'_'.date('YmdHis');
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $filepath = $directory . '/' . $name.'.png';
        
        $qrcode->render($payload, $filepath);
        return view('result',['data'=>$name,'amount'=>$request->amount,'name'=>$cekmerchant->merchantname]);
    }

    public function decoderQris(Request $request)
    {
        $cek = Qris::query()->where('userId','=',Session::user()->userId)->first();
        if($cek){
            return Response::json([
                'status' => 500,
                'message' => 'Qris Sudah ada',
            ]);
        }
        if($request->file('image')){
            $tmpPath = $request->file('image')['tmp_name'];
            $qrcode = new QrReader($tmpPath);
            $qrisData = $qrcode->text();

            if($qrisData){
                $parsed = $this->parseTLV($qrisData);
                $mapping = $this->mapSimplified($parsed);
                $dbqris = Qris::create([
                    'userId' => Session::user()->userId,
                    'merchantvendor' => $mapping['merchantVid'],
                    'merchantid' => $mapping['merchantId'],
                    'merchantcriteria' => $mapping['merchantCriteria'],
                    'merchanttype' => $mapping['merchantType'],
                    'merchantcategory' => $mapping['merchantCategory'],
                    'merchantcurrency' => $mapping['currency'],
                    'countryid' => $mapping['country'],
                    'merchantname' => $mapping['merchantName'],
                    'merchantcity' => $mapping['merchantCity'],
                    'merchantpostalcode' => $mapping['postalCode'],
                ]);
                return Response::json([
                    'status' => 200,
                    'message' => 'success',
                    'parser' => $parsed,
                    'data' => $mapping
                ]);
            } else {
                return Response::json([
                    'status' => 500,
                    'message' => 'error',
                ]);
            }
        }
    }

    private function parseTLV($data) {
        $result = [];
        $pos = 0;
        while ($pos < strlen($data)) {
            $id = substr($data, $pos, 2);
            $pos += 2;

            $len = intval(substr($data, $pos, 2));
            $pos += 2;

            $value = substr($data, $pos, $len);
            $pos += $len;

            if (in_array($id, ['26','51','62','64','65'])) {
                $result[$id] = $this->parseTLV($value);
            } else {
                $result[$id] = $value;
            }
        }
        return $result;
    }

    private function mapQrisFields(array $parsed)
    {
        $mapping = [
            '00' => 'payloadFormat',
            '01' => 'pointOfInitiation',
            '26' => 'merchantAccountInfo',
            '51' => 'merchantAccountInfoAdditional',
            '52' => 'merchantCategoryCode',
            '53' => 'transactionCurrency',
            '54' => 'transactionAmount',
            '58' => 'countryCode',
            '59' => 'merchantName',
            '60' => 'merchantCity',
            '61' => 'postalCode',
            '62' => 'additionalData',
            '63' => 'crc',
        ];

        $result = [];
        foreach ($parsed as $id => $value) {
            $key = $mapping[$id] ?? $id;
            $result[$key] = $value;
        }

        return $result;
    }

    private function mapSimplified(array $parsed)
    {
        return [
            'payloadFormat'    => $parsed['00'] ?? null,
            'pointOfInitiation'=> $parsed['01'] ?? null,

            'merchantVid'      => $parsed['26']['00'] ?? null,
            'merchantId'       => $parsed['26']['01'] ?? null,
            'merchantCriteria' => $parsed['26']['02'] ?? null,
            'merchantType'     => $parsed['26']['03'] ?? null,

            'merchantCategory' => $parsed['52'] ?? null,
            'currency'         => $parsed['53'] ?? null,
            'amount'           => $parsed['54'] ?? null,
            'country'          => $parsed['58'] ?? null,
            'merchantName'     => $parsed['59'] ?? null,
            'merchantCity'     => $parsed['60'] ?? null,
            'postalCode'       => $parsed['61'] ?? null,
            'crc'              => $parsed['63'] ?? null,
        ];
    }

    private function buildTLV($id, $value)
    {
        $len = str_pad(strlen($value), 2, '0', STR_PAD_LEFT);
        return $id . $len . $value;
    }

    public function rebuildQris(array $parsed)
    {
        $payload  = '';
        $payload .= $this->buildTLV('00', $parsed['00']); // payloadFormat
        $payload .= $this->buildTLV('01', $parsed['01']); // pointOfInitiation

        // Merchant Account Info
        if (isset($parsed['26'])) {
            $ma = '';
            foreach ($parsed['26'] as $subId => $subVal) {
                $ma .= $this->buildTLV($subId, $subVal);
            }
            $payload .= $this->buildTLV('26', $ma);
        }

        // Merchant Category
        $payload .= $this->buildTLV('52', $parsed['52']);
        // Currency
        $payload .= $this->buildTLV('53', $parsed['53']);
        // Amount
        if (!empty($parsed['54'])) {
            $payload .= $this->buildTLV('54', $parsed['54']);
        }

        // Country
        $payload .= $this->buildTLV('58', $parsed['58']);
        // Merchant Name
        $payload .= $this->buildTLV('59', $parsed['59']);
        // City
        $payload .= $this->buildTLV('60', $parsed['60']);
        // Postal Code
        if (!empty($parsed['61'])) {
            $payload .= $this->buildTLV('61', $parsed['61']);
        }

        $crc = $this->CRC($payload);
        $payload .= '6304' . $crc;

        return $payload;
    }

}
