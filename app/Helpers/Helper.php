<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function responseSuccess($data, int $status = 200, string $message = "")
	{
		return response()->json(
			[
				"status" => $status,
				"message" => $message,
				"data" => $data,
			],
			$status
		);
	}

	public static function responseError($data=[], int $status = 501, string $message = "")
	{
		return response()->json(
			[
				"status" => $status,
				"message" => $message,
                "data" => $data,
			],
			$status
		);
	}

    public static function generateNumericOTP($n) {
      
        // Take a generator string which consist of
        // all numeric digits
        $generator = "1357902468";
    
        // Iterate for n-times and pick a single character
        // from generator and append it to $result
        
        // Login for generating a random character from generator
        //     ---generate a random number
        //     ---take modulus of same with length of generator (say i)
        //     ---append the character at place (i) from generator to result
    
        $result = "";
    
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
    
        // Return result
        return $result;
    }   
}