<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        $existingDocument = DB::table('users')
            ->where('documento_identidad', $input['documento_identidad'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($existingDocument) {
            throw ValidationException::withMessages([
                'documento_identidad' => ['Este documento de identidad ya está en uso.'],
            ]);
        }

        $existingEmail = DB::table('users')
            ->where('email', $input['email'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($existingEmail) {
            throw ValidationException::withMessages([
                'email' => ['Ya existe una cuenta con este correo.'],
            ]);
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'apellido' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:255'],
            'documento_identidad' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'genero' => ['nullable', 'string', 'in:Masculino,Femenino,Otro'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'apellido' => $input['apellido'] ?? $user->apellido,
                'telefono' => $input['telefono'] ?? $user->telefono,
                'documento_identidad' => $input['documento_identidad'] ?? $user->documento_identidad,
                'ciudad' => $input['ciudad'] ?? $user->ciudad,
                'direccion' => $input['direccion'] ?? $user->direccion,
                'genero' => $input['genero'] ?? $user->genero,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
