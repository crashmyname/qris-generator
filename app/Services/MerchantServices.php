<?php

namespace App\Services;
use Helpers\Validator;

class MerchantServices
{
    // Service logic here
    public function collectMerchant()
    {
        $merchant = [
            'gopay' => '0014COM.GO-JEK.WWW',
            'ovo' => '0011COM.OVO.WWW',
            'dana' => '0012COM.DANA.WWW',
            'shopee' => '0017COM.SHOPEEPAY.WWW',
            'linkaja' => '0016COM.LINKAJA.WWW',
            'bca' => '0014COM.BCA.WWW',
            'mandiri' => '0019COM.MANDIRI.ID.WWW',
            'bri' => '0014COM.BRI.ID.WWW',
            'bni' => '0014COM.BNI.ID.WWW',
            'cimb' => '0015COM.CIMB.ID.WWW'
        ];
        
        return $merchant;
    }
}
