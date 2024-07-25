<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\SimpleExcel\SimpleExcelWriter;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'vaultname' => ['required',Rule::unique(User::class)],
            'filename'=>['required']
        ])->validate();
        
        //\Log::info($input);
        $storagePath = storage_path('app/csv');
        $filePath = $storagePath . '/' . $input['filename'] . '.csv';

        // Create directory if it doesn't exist
        if (!\File::exists($storagePath)) {
            \File::makeDirectory($storagePath, 0755, true);
        }

        SimpleExcelWriter::create($filePath, 'csv')->addHeader(['identifier', 'username', 'password', 'imgurl','url']);
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'vaultname'=> $input['vaultname'],
            'filename'=> $input['filename'],
        ]);
    }
}
