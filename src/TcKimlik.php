<?php

/**
 * Created by PhpStorm.
 * User: Murat AYGÜN
 * Date: 15.4.2017
 * Time: 00:52
 */

namespace murataygun;

define('validationFields', ["tcNo", "name", "surName", "birthyear"]);


class TcKimlik
{

    /**
     * @param $data
     * @return bool
     */
    public static function confirm($data)
    {
        $tcNo = $data;
        if (is_array($data) && !empty($data['tcNo']))
            $tcNo = $data['tcNo'];


        if (!preg_match('/^[1-9]{1}[0-9]{9}[0,2,4,6,8]{1}$/', $tcNo)) {
            return false;
        }

        $odd = $tcNo[0] + $tcNo[2] + $tcNo[4] + $tcNo[6] + $tcNo[8];
        $even = $tcNo[1] + $tcNo[3] + $tcNo[5] + $tcNo[7];
        $digit10 = ($odd * 7 - $even) % 10;
        $total = ($odd + $even + $tcNo[9]) % 10;

        if ($digit10 != $tcNo[9] || $total != $tcNo[10]) {
            return false;
        }
        return true;
    }

    /**
     * @param array $data
     * @param bool $auto_uppercase
     * @return bool
     */
    public static function validate(Array $data, $auto_uppercase = TRUE)
    {
        if (!self::confirm($data))
            return false;

        if (count(array_diff(validationFields, array_keys($data))) != 0) {
            return false;
        }
        if ($auto_uppercase) {
            foreach (validationFields as $field) {
                $data[$field] = self::trUppercase($data[$field]);
            }
        }
        $post_data = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			<soap:Body>
				<TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
					<TCKimlikNo>' . $data['tcNo'] . '</TCKimlikNo>
					<Ad>' . $data['name'] . '</Ad>
					<Soyad>' . $data['surName'] . '</Soyad>
					<DogumYili>' . $data['birthyear'] . '</DogumYili>
				</TCKimlikNoDogrula>
			</soap:Body>
		</soap:Envelope>';

        $ch = curl_init();
        $options = array(
            CURLOPT_URL => 'https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => array(
                'POST /Service/KPSPublic.asmx HTTP/1.1',
                'Host: tckimlik.nvi.gov.tr',
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"',
                'Content-Length: ' . strlen($post_data)
            ),
        );

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        return (strip_tags($response) === 'true') ? true : false;
    }


    /**
     * @param $string
     * @return string
     */
    private function trUppercase($string)
    {
        $string = str_replace(array('i'), array('İ'), $string);
        return mb_convert_case($string, MB_CASE_UPPER, "UTF-8");
    }
}