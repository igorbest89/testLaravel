<?php

namespace App;

use App\Api\Shopify\Shopify;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'phone',
        'country_code'
    ];

    /**
     * Get customers from shopify application
     * @return mixed
     */
    public static function getRemoteUsers()
    {
        $shopify = new Shopify('eb4a83a231d60691efe7a637ade9be63', '62d6984111a1cd91a4035cb7270f1605');
        return $shopify->getCustomers();
    }

    /**
     *  Check country_cod of $this Customer
     */
    public function checkCountry()
    {
        $location = \Location::get(\Request::ip());
        $this->country_code = $location->countryCode;
        $this->save();
    }

}
