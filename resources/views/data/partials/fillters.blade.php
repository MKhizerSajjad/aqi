<section>
    <header>
        <h4 class="">
            {{ __('Fillters') }}
        </h4>

        <span class="">
            {{ __("Fillter data as per your need.") }}
        </span>
    </header>

    <form method="get" action="{{ route('data.list') }}" class="mt-6 space-y-6">
        <div class="row">
            <div class="col-xl-2 col-md-3 col-sm-12">
                <lable for="date">{{ __("Date") }}</lable>
                <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date', isset($filters['date']) ? $filters['date'] : null) }}" autocomplete="date">
                <span class="mt-2" :messages="$errors->get('date')"></span>
            </div>

            <div class="col-xl-3 col-md-3 col-sm-12">
                <lable for="date">{{ __("City") }}</lable>
                <input id="city_name" type="city_name" class="form-control @error('city_name') is-invalid @enderror" name="city_name" value="{{ old('city_name', isset($filters['city_name']) ? $filters['city_name'] : null) }}" autocomplete="city_name">
                <span class="mt-2" :messages="$errors->get('city_name')"></span>
            </div>

            <div class="col-xl-3 col-md-3 col-sm-12">
                <lable for="date">{{ __("State") }}</lable>
                <input id="state_name" type="state_name" class="form-control @error('state_name') is-invalid @enderror" name="state_name" value="{{ old('state_name', isset($filters['state_name']) ? $filters['state_name'] : null) }}" autocomplete="state_name">
                {{-- <input id="state_name" name="state_name" type="text" class="mt-1 block w-full" :value="old('state_name', isset($filters['state_name']) ? $filters['state_name'] : null)" autocomplete="state_name" /> --}}
                <span class="mt-2" :messages="$errors->get('state_name')"></span>
            </div>

            <div class="col-xl-2 col-md-3 col-sm-12">
                <lable for="date">{{ __("Country") }}</lable>
                <input id="country_name" type="country_name" class="form-control @error('country_name') is-invalid @enderror" name="country_name" value="{{ old('country_name', isset($filters['country_name']) ? $filters['country_name'] : null) }}" autocomplete="country_name">
                {{-- <input id="country_name" name="country_name" type="text" class="mt-1 block w-full" :value="old('country_name', isset($filters['country_name']) ? $filters['country_name'] : null)" autocomplete="country_name" /> --}}
                <span class="mt-2" :messages="$errors->get('country_name')"></span>
            </div>
        {{-- </div>

        <div class="flex items-center gap-4"> --}}

        <div class="col-xl-2 col-md-2 col-sm-12 mt-4">
            <button class="btn btn-info">{{ __('Search') }}</button>
            @if ($filters)
                <a href="{{route('data.list')}}" class="btn btn-danger">Reset</a>
                {{-- <a href="{{ $filters ? route('data.list') : null  }}" class="btn btn-danger">Reset</a> --}}
            @endif

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif


            {{-- @if ($filters)
                <div class="col-xl-2 col-md-2 col-sm-12">
                    <button class="btn btn-info">{{ __('Search') }}</button>
                    <a href="{{ route('data.list') }}" class="btn btn-danger">Reset</a>

                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >{{ __('Saved.') }}</p>
                    @endif
                </div>
            @else
            <div class="col-xl-2 col-md-2 col-sm-12">
                <button class="btn btn-info">{{ __('Search') }}</button>
            </div>
            @endif --}}
        </div>
    </form>
</section>
