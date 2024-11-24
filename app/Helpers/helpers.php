<?php

if (!function_exists('phoneFormatter')) {
    function phoneFormatter($phone, $pretty = false)
    {
        if (is_null($phone))
            return null;

        $phone = trim($phone);
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $phone = (substr($phone, 0, 1) == 8 || substr($phone, 0, 1) == 7)
            ? $phone : '+7' . $phone;
        if ($pretty) {
            return (strlen($phone) < 3)
                ? null
                : preg_replace('/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/', '+7($2)$3-$4-$5', $phone);
        } else
            return strlen($phone) < 10 ? null : preg_replace('/[\+]?([7|8])[-|\s]?(\d{10})/', '+7$2', $phone);

    }
}

if (!function_exists('currentAtelierId')) {
    function currentAtelierId()
    {
        return auth()->user()->current_atelier_id;
    }
}

if (!function_exists('trim_zero')) {
    function trim_zero($var)
    {
        $var = (string)$var;
        $var = rtrim($var, '0');
        $var = rtrim($var, '.');
        return $var;
    }
}


if (!function_exists('cleanChars')) {
    function cleanChars($char)
    {
        return preg_replace('/[^0-9]/', '', $char);
    }
}


if (!function_exists('translit_sef')) {
    function translit_sef($value)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '_', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }
}


if (!function_exists('FIO')) {
    function FIO($name)
    {
        $name = preg_replace('~(\pL)\S+|\s+~u', '$1', $name);
        return (mb_strlen($name) > 2) ? mb_substr($name, 0, -1) : $name;
    }
}

if (!function_exists('avatarNP')) {
    function avatarNP($user)
    {
        return mb_substr($user->name, 0, 1) . mb_substr($user->patronymic, 0, 1);
        //return mb_substr($user->tg_name, 0, 2);
    }
}

if (!function_exists('gen_uuid')) {
    function gen_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

if (!function_exists('cff')) {
    function cff($format, $time)
    {
        return \Carbon\Carbon::createFromFormat($format, $time);
    }
}

if (!function_exists('selopt')) {
    function selopt($curr_val, $db_val, $old_val = null)
    {
        if ($old_val)
            return ($curr_val == $old_val) ? 'selected' : '';
        return ($curr_val == $db_val) ? 'selected' : '';
    }
}

if (!function_exists('serviceName')) {
    function serviceName($value)
    {
        $arr = [
            'tailoring' => ['name' => 'Пошив', 'route_name' => 'tailoringOrder'],
            'repair' => ['name' => 'Ремонт', 'route_name' => 'repairOrder'],
            'sale' => ['name' => 'Продажа', 'route_name' => 'saleOrder'],
        ];
        return $arr[$value];
    }
}

if (!function_exists('twoArrPivotData')) {
    function twoArrPivotData($arr1, $arr2, $type = 'quantity')
    {
        $result = array();
        if (empty($arr1)) {
            return $result;
        }
        foreach ($arr1 as $key => $value) {
            $result[$value] = [$type => $arr2[$key]];
        }

        return $result;
    }
}

if (!function_exists('threeArrPivotData')) {
    function threeArrPivotData($arr1, $arr2, $arr3)
    {
        $result = array();
        if (empty($arr1)) {
            return $result;
        }
        foreach ($arr1 as $key => $value) {
            $result[$value] = ['price' => $arr2[$key], 'quantity' => $arr3[$key]];
        }
        return $result;
    }
}

if (!function_exists('convert')) {
    function convert($money, $desimal = 2)
    {
        return str_replace(',', ' ', number_format($money, $desimal));
    }
}
