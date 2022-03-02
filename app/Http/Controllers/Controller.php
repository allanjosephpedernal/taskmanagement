<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @throws  Exception
     * @param $validator
     * @return mixed
     */
    protected static function validateRequest($validator)
    {
        $err_arr = [];
        if ($validator->fails()) {
            $has_error = checkEmptyParam($validator->errors()->all());
            if (count($has_error) > 0) {
                foreach ($validator->errors()->messages() as $key => $err) {
                    if (is_array($err)) {
                        foreach ($err as $item) {
                            $err_arr[$key] = $item;
                        }
                    }
                }
            }
        }
        return $err_arr;
    }
}
