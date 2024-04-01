@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('AQI Data') }}</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- {{ __('List here!') }} --}}

                    @include('data.partials.fillters')
                    @include('data.partials.listing')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
