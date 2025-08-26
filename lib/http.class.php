<?php

class HTTP
{
    /**
     * Make a POST request with JSON or form data
     */
    public static function post($url, $data = [], $headers = [])
    {
        $ch = curl_init();
        
        $default_headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
            'User-Agent: Web-App-Starter/1.0'
        ];
        
        $headers = array_merge($default_headers, $headers);
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => is_array($data) ? http_build_query($data) : $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return ['error' => 'cURL Error: ' . $error, 'http_code' => 0];
        }
        
        $decoded = json_decode($response, true);
        
        return [
            'success' => $http_code >= 200 && $http_code < 300,
            'http_code' => $http_code,
            'raw' => $response,
            'data' => $decoded ?: $response,
            'error' => $http_code >= 400 ? "HTTP Error $http_code" : null
        ];
    }
    
    /**
     * Make a GET request
     */
    public static function get($url, $params = [], $headers = [])
    {
        if (!empty($params)) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
        }
        
        $ch = curl_init();
        
        $default_headers = [
            'Accept: application/json',
            'User-Agent: Web-App-Starter/1.0'
        ];
        
        $headers = array_merge($default_headers, $headers);
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return ['error' => 'cURL Error: ' . $error, 'http_code' => 0];
        }
        
        $decoded = json_decode($response, true);
        
        return [
            'success' => $http_code >= 200 && $http_code < 300,
            'http_code' => $http_code,
            'raw' => $response,
            'data' => $decoded ?: $response,
            'error' => $http_code >= 400 ? "HTTP Error $http_code" : null
        ];
    }
    
    /**
     * Make a request with Bearer token authentication
     */
    public static function get_with_token($url, $token, $params = [])
    {
        return self::get($url, $params, [
            'Authorization: Bearer ' . $token
        ]);
    }
}
