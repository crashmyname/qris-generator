<?php

namespace App\Controllers;

use App\Services\MerchantServices;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Helpers\BaseController;
use Bpjs\Core\Request;
use Helpers\Date;
use Helpers\Validator;
use Helpers\View;
use Helpers\CSRFToken;

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
        $merchant = new MerchantServices();
        $payload  = "000201";        
        $payload .= "010212";        

        // Merchant INFO
        $payload .= env('MERCHANT-VID');
        $payload .= $merchant->collectMerchant()['gopay'];
        $payload .= env('MERCHANT-ID'); 
        $payload .= env('MERCHANT-CRITERIA');        
        $payload .= env('MERCHANT-TYPE');

        $payload .= env('MERCHANT-CATEGORY');      
        $payload .= env('MERCHANT-CURRENCY');       

        if ($request->amount !== null) {
            $amt = number_format($request->amount, 2, '.', '');
            $payload .= "54" . str_pad(strlen($amt), 2, "0", STR_PAD_LEFT) . $amt;
        }

        $payload .= env('COUNTRY-ID');       
        $payload .= env('MERCHANT-NAME');  
        $payload .= env('MERCHANT-CITY');  
        $payload .= env('POSTAL-CODE');      

        if ($invoiceId !== null) {
            $addData = "01" . str_pad(strlen($invoiceId), 2, "0", STR_PAD_LEFT) . $invoiceId;
            $payload .= "62" . str_pad(strlen($addData), 2, "0", STR_PAD_LEFT) . $addData;
        }

        $crc = $this->CRC($payload);
        $payload .= "6304" . $crc;

        // return $payload;
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
        return view('result',['data'=>$name,'amount'=>$request->amount]);
    }
}
