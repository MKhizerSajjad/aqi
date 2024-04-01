<section>
    <header>
        <h4 class="mt-4">
            {{ __('Data') }}
        </h4>
    </header>

    @if (count($data) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Time</th>
                        <th>PM25</th>
                        <th>PM10</th>
                        <th>Temp</th>
                        <th>Humidty</th>
                        <th>Pressure</th>
                        <th>Ozone</th>
                        <th>Wind</th>
                        <th>Wind Direction</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->date }} {{ $item->time }}</td>
                            <td>{{ $item->pm25 }} {!! getStatusButton($item->pm25_status) !!}</span></td>
                            <td>{{ $item->pm10 }} {!! getStatusButton($item->pm10_status) !!}</span></td>
                            <td>{{ $item->t }} {!! getStatusButton($item->t_status) !!}</span></td>
                            <td>{{ $item->h }} {!! getStatusButton($item->h_status) !!}</span></td>
                            <td>{{ $item->p }} {!! getStatusButton($item->p_status) !!}</span></td>
                            <td>{{ $item->o3 }} {!! getStatusButton($item->o3_status) !!}</span></td>
                            <td>{{ $item->w }} {!! getStatusButton($item->w_status) !!}</span></td>
                            <td>{{ $item->wd }}</td>
                            <td>{{ $item->city_name }}</td>
                            <td>{{ $item->state_name }}</td>
                            <td>{{ $item->country_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($data->hasPages())
            <div class="d-flex justify-content-end mt-3 me-3">
                <div class="pagination-wrap ">
                    {{ $data->onEachSide(2)->appends($filters)->links() }}
                </div>
            </div>
        @endif
    @else
        <div class="noresult">
            <div class="text-center">
                <h5 class="mt-2 text-danger">Sorry! No Result Found</h5>
                {{-- <p class="text-muted mb-0">We've searched more than 150+ Tickets We did not find any Tickets for you search.</p> --}}
            </div>
        </div>
    @endif
</section>

<style>
    .pagination-wrap{
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .pagination-wrap .w-5 {
        width: 30px !important;
    }
    .pagination-wrap .leading-5.text-sm  {
        margin-top: 7px !important;
        margin-bottom: 7px !important;
    }
    .pagination-wrap .relative.inline-flex.items-center.px-2.py-2 {
      text-decoration: none !important;
    }
    .customBtn {
        border: 0px;
        padding: 0 3px;
        font-size: 60%;
    }
</style>
