<x-jet-form-section submit="updateProfileInformation">

    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and other settings.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            wire:model="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-jet-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-jet-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-jet-secondary-button>
                @endif

                <x-jet-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autocomplete="name" />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-jet-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Basecamp Company ID -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="basecamp_org_id" value="{{ __('Basecamp Company ID') }}" />
            <x-jet-input id="basecamp_org_id" type="text" class="mt-1 block w-full" wire:model.defer="state.basecamp_org_id" autocomplete="basecamp_org_id" />
            <x-jet-input-error for="basecamp_org_id" class="mt-2" />
        </div>

        <!-- Basecamp Company Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="basecamp_org" value="{{ __('Basecamp Company Name') }}" />
            <x-jet-input id="basecamp_org" type="text" class="mt-1 block w-full" wire:model.defer="state.basecamp_org" autocomplete="basecamp_org" />
            <x-jet-input-error for="basecamp_org" class="mt-2" />
        </div>

        <!-- Basecamp API Key -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="basecamp_api_key" value="{{ __('Basecamp API Key') }}" />
            <x-jet-input id="basecamp_api_key" type="text" class="mt-1 block w-full" wire:model.defer="state.basecamp_api_key" autocomplete="basecamp_api_key" />
            <x-jet-input-error for="basecamp_api_key" class="mt-2" />
        </div>

        <!-- Basecamp User ID -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="basecamp_api_user_id" value="{{ __('Basecamp User ID') }}" />
            <x-jet-input id="basecamp_api_user_id" type="text" class="mt-1 block w-full" wire:model.defer="state.basecamp_api_user_id" autocomplete="basecamp_api_user_id" />
            <x-jet-input-error for="basecamp_api_user_id" class="mt-2" />
        </div>

        <!-- Daily Working Hours -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="working_hours_count" value="{{ __('Daily Working Hours') }}" />
            <x-jet-input id="working_hours_count" type="text" class="mt-1 block w-full" wire:model.defer="state.working_hours_count" autocomplete="working_hours_count" />
            <x-jet-input-error for="working_hours_count" class="mt-2" />
        </div>

        <!-- Total Holidays This Month -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="holidays_count" value="{{ __('Total Public Holidays This Month (Excluding Sat/Sun)') }}" />
            <x-jet-input id="holidays_count" type="text" class="mt-1 block w-full" wire:model.defer="state.holidays_count" autocomplete="holidays_count" />
            <x-jet-input-error for="holidays_count" class="mt-2" />
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
