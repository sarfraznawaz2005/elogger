<?php

namespace App\Actions\Fortify;

use App\Facades\Data;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\ComponentConcerns\ReceivesEvents;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    use ReceivesEvents;
    use InteractsWithBanner;

    /**
     * Validate and update the given user's profile information.
     *
     * @param mixed $user
     * @param array $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'basecamp_org_id' => ['required', 'string', 'max:255'],
            'basecamp_org' => ['required', 'string', 'max:255'],
            'basecamp_api_key' => ['required', 'string', 'max:255'],
            'basecamp_api_user_id' => ['required', 'string', 'max:255'],
            'working_hours_count' => ['required', 'integer', 'between:1,24'],
            'holidays_count' => ['required', 'integer', 'between:0,31'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ], [
                'basecamp_org_id.required' => "Basecamp Company ID is required!",
                'basecamp_org.required' => "Basecamp Company Name is required!",
                'basecamp_api_key.required' => "Basecamp API Key is required!",
                'basecamp_api_user_id.required' => "Basecamp User ID is required!",
            ]
        )->validateWithBag('updateProfileInformation');

        $user->basecamp_org_id = $input['basecamp_org_id'];
        $user->basecamp_org = $input['basecamp_org'];
        $user->basecamp_api_key = $input['basecamp_api_key'];
        $user->basecamp_api_user_id = $input['basecamp_api_user_id'];
        $user->working_hours_count = $input['working_hours_count'] ?? 8;
        $user->holidays_count = $input['holidays_count'] ?? 0;

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param mixed $user
     * @param array $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
