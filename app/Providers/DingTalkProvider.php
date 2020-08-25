<?php

namespace App\Providers;
use EasyDingTalk\Application;

class DingTalkProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function runed()
    {
        $config = [

            'corp_id' => 'dingb5ad56059048bb8135c2f4657eb6378f',

            'app_key' => 'dingd4gxnq17iw3hlcf4',

            'app_secret' => 'nkPQzly3MnPiCR_uu1NeyQhfikPD0zGWmX1BI10YPQi5GvVgZlWRF08M2j8oNDMh'
        ];
        return new Application($config);
    }
}
