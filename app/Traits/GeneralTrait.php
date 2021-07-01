<?php

namespace App\Traits;

trait GeneralTrait {

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function returnSuccessMessage($msg = "", $errNum = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "",
            'msg' => $msg,
            $key => $value
        ]);
    }

    public function returnValidationError($code, $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }

    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == 'name') {
            return 'E0011';
        } elseif ($input == 'password') {
            return 'E002';
        } elseif ($input == 'email') {
            return 'E007';
        } else {
            return '';
        }
    }

}
