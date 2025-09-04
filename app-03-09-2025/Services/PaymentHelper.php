<?php
namespace App\Services;

class PaymentHelper
{
    public static function encryptAES($data, $key)
    {
        // Apply PKCS5 padding
        $paddedData = self::pkcs5_pad($data);

        // Fixed IV provided by Al Rajhi
        $iv = "PGKEYENCDECIVSPC";

        // Encrypt using AES-256-CBC
        $encrypted = openssl_encrypt(
            $paddedData,
            "aes-256-cbc",
            $key,
            OPENSSL_ZERO_PADDING,
            $iv
        );

        // Convert to hex
        $encrypted = base64_decode($encrypted);
        $encrypted = unpack('C*', $encrypted);
        $encrypted = self::byteArray2Hex($encrypted);

        // URL encode before sending
        return urlencode($encrypted);
    }

    private static function pkcs5_pad($text, $blocksize = 16)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private static function byteArray2Hex($byteArray)
    {
        $hex = '';
        foreach ($byteArray as $byte) {
            $hex .= str_pad(dechex($byte), 2, '0', STR_PAD_LEFT);
        }
        return $hex;
    }

    public static function decryptAES ($code, $key)
    {
        $code = self::hex2ByteArray(trim($code));
        $code=self::byteArray2String($code);
        $iv = "PGKEYENCDECIVSPC";

        $code = base64_encode($code);
        $decrypted = openssl_decrypt($code, 'AES-256-CBC', $key, OPENSSL_ZERO_PADDING,
        $iv);
        return self::pkcs5_unpad($decrypted);
    }

    private static function hex2ByteArray($hexString)
    {
        $byteArray = [];
        // Make sure string length is even
        $hexString = strlen($hexString) % 2 !== 0 ? '0' . $hexString : $hexString;

        // Loop over each pair of hex digits
        for ($i = 0; $i < strlen($hexString); $i += 2) {
            $byteArray[] = hexdec(substr($hexString, $i, 2));
        }

        return $byteArray;
    }
    private static function byteArray2String($byteArray)
    {
        $str = '';
        foreach ($byteArray as $byte) {
            $str .= chr($byte);
        }
        return $str;
    }
    private static function pkcs5_unpad($text)
    {
        // If $text is false or empty, return false
        if ($text === false || empty($text)) {
            return false;
        }

        $pad = ord($text[strlen($text) - 1]);

        // Basic validation of padding
        if ($pad < 1 || $pad > 16) {
            return false;
        }

        return substr($text, 0, -$pad);
    }


}