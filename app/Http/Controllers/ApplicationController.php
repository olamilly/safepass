<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Contracts\Auth\StatefulGuard;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\Models\User;

class ApplicationController extends Controller
{
    //
    // public function __invoke(){
    //     return view("layouts.app");
    // }
    public function editRecord(Request $request){
        function myencrypt($message, $encryption_key){
            $key = hex2bin($encryption_key);
        
            $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
            $nonce = openssl_random_pseudo_bytes($nonceSize);
        
            $ciphertext = openssl_encrypt(
              $message, 
              'aes-256-ctr', 
              $key,
              OPENSSL_RAW_DATA,
              $nonce
            );
            return base64_encode($nonce.$ciphertext);
        }
        $private_secret_key = bin2hex(utf8_decode(auth()->user()->password)) ;
        $encryptedid = myencrypt($request->identifier,$private_secret_key);
        $encrypteduser = myencrypt($request->username,$private_secret_key);
        $encryptedpass = myencrypt($request->password,$private_secret_key);

        $user=User::where('vaultname', auth()->user()->vaultname)->first();
        $fname=$user->filename;
        $storagePath=storage_path('app/csv');
        $filePath = $storagePath . '/' . $fname . '.csv';
        $cnt = 0;
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($csvadata = fgetcsv($handle, 0, ",")) !== FALSE) {      
            $data[$cnt++] = $csvadata;
            }
            fclose($handle);
            //unset($data[$id]);
            //\Log::info($data[$request->index]);
            $data[$request->index][0]= $encryptedid;
            $data[$request->index][1]= $encrypteduser;
            $data[$request->index][2]= $encryptedpass;
            //\Log::info($data[$request->index]);
        }
        $fp = fopen($filePath, 'w');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    public function addNewRecord(Request $request) {
        function myencrypt($message, $encryption_key){
            $key = hex2bin($encryption_key);
        
            $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
            $nonce = openssl_random_pseudo_bytes($nonceSize);
        
            $ciphertext = openssl_encrypt(
              $message, 
              'aes-256-ctr', 
              $key,
              OPENSSL_RAW_DATA,
              $nonce
            );
            return base64_encode($nonce.$ciphertext);
        } 
        $private_secret_key = bin2hex(utf8_decode(auth()->user()->password)) ;
        //tryin to get url icon with link
        if($request->url){
            $url = 'https://www.google.com/s2/favicons?domain=' . $request->url . '&sz=128';
            $client = new Client();
            try {
                $response = $client->get($url);
                $imgUrl = $response->getHeader('Content-Location')[0];
            } 
            catch(ClientException $e) {
                $imgUrl = '';
            }
        }
        else{
            $imgUrl = '';
        }

        $user=User::where('vaultname', $request->vaultname)->first();
        $fname=$user->filename;
        $storagePath=storage_path('app/csv');
        $filePath = $storagePath . '/' . $fname . '.csv';

        $file = fopen($filePath, 'a');
        $encryptedid = myencrypt($request->recordname,$private_secret_key);
        $encrypteduser = myencrypt($request->recordusername,$private_secret_key);
        $encrypted = myencrypt($request->password,$private_secret_key);

        if($request->recordusername){
            fputcsv($file, [
                $encryptedid,
                $encrypteduser,
                $encrypted,
                $imgUrl,
                $request->url
             ]);
        }
        else{
            fputcsv($file, [
                $encryptedid,
                myencrypt(auth()->user()->name,$private_secret_key),
                $encrypted,
                $imgUrl,
                $request->url
             ]); 
        }
        
        fclose($file);
    }
    public function getAllRecords() {
        function mydecrypt($message,$encryption_key){
            $key = hex2bin($encryption_key);
            $message = base64_decode($message);
            $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
            $nonce = mb_substr($message, 0, $nonceSize, '8bit');
            $ciphertext = mb_substr($message, $nonceSize, null, '8bit');
        
            $plaintext= openssl_decrypt(
                $ciphertext, 
                'aes-256-ctr', 
                $key,
                OPENSSL_RAW_DATA,
                $nonce
            );
            return $plaintext;
        }
        $private_secret_key = bin2hex(utf8_decode(auth()->user()->password)) ;
        
        $storagePath=storage_path('app/csv');
        $filePath = $storagePath . '/' . auth()->user()->filename . '.csv';
     
        $rows = SimpleExcelReader::create($filePath)->getRows()->toArray();
        for ($x = 0; $x < count($rows); $x++) {
            $rows[$x]["identifier"]=mydecrypt($rows[$x]["identifier"],$private_secret_key);
            $rows[$x]["username"]=mydecrypt($rows[$x]["username"],$private_secret_key);
            $rows[$x]["password"]=mydecrypt($rows[$x]["password"],$private_secret_key);
        }
        return $rows; 
    }
    public function getMyRecord(String $id) {
        function mydecrypt($message,$encryption_key){
            $key = hex2bin($encryption_key);
            $message = base64_decode($message);
            $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
            $nonce = mb_substr($message, 0, $nonceSize, '8bit');
            $ciphertext = mb_substr($message, $nonceSize, null, '8bit');
        
            $plaintext= openssl_decrypt(
                $ciphertext, 
                'aes-256-ctr', 
                $key,
                OPENSSL_RAW_DATA,
                $nonce
            );
            return $plaintext;
        }
        $storagePath=storage_path('app/csv');
        $filePath = $storagePath . '/' . auth()->user()->filename . '.csv';
        $rows = SimpleExcelReader::create($filePath)->getRows()->toArray();
        $original_string = $rows[$id]["password"];
        $private_secret_key = bin2hex(utf8_decode(auth()->user()->password)) ;
        $decrypted = mydecrypt($original_string,$private_secret_key);
        return $decrypted;
        
    }
    public function deleteMyRecord(String $id){
        $storagePath=storage_path('app/csv');
        $filePath = $storagePath . '/' . auth()->user()->filename . '.csv';
        $cnt = 0;
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($csvadata = fgetcsv($handle, 0, ",")) !== FALSE) {      
            $data[$cnt++] = $csvadata;
            }
            fclose($handle);
            unset($data[$id]);
        }
        $fp = fopen($filePath, 'w');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

    }

    
      
      
}
