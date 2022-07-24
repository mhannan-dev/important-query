@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Review List'))

@push('css_or_js')
    <style>
        .form-inline {
            display: inline;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('reviews') }}</li>
            </ol>
        </nav>


        <div class="card p-4">
            <div class="">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h5>{{ \App\CPU\translate('Review') }} {{ \App\CPU\translate('Table') }} <span
                                class="text-danger">({{ $reviews->total() }})</span> </h5>
                    </div>
                    <div class="col-12 col-md-6 pb-2">
                        <!-- Search -->
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{ \App\CPU\translate('Search by Product or Customer') }}"
                                    aria-label="Search orders" value="{{ $search }}" required>
                                <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('search') }}</button>
                            </div>
                        </form>
                        <!-- End Search -->
                    </div>

                </div>
                <form action="{{ url()->current() }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:15px">
                                <label for="product">{{ \App\CPU\translate('choose') }}
                                    {{ \App\CPU\translate('product') }}</label>
                                <select class="form-control" name="product_id">
                                    <option value="" selected>
                                        --{{ \App\CPU\translate('select_product') }}--</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ $product_id == $product->id ? 'selected' : '' }}>
                                            {{ Str::limit($product->name, 20) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group" style="margin-bottom:15px">
                                <label for="customer">{{ \App\CPU\translate('choose') }}
                                    {{ \App\CPU\translate('customer') }}</label>
                                <select class="form-control" name="customer_id">
                                    <option value="" selected>
                                        --{{ \App\CPU\translate('select_customer') }}--</option>
                                    @foreach ($customers as $item)
                                        <option value="{{ isset($item->id) ? $item->id : $customer_id }}"
                                            {{ $customer_id != null && $customer_id == $item->id ? 'selected' : '' }}>
                                            {{ Str::limit($item->f_name) }}
                                            {{ Str::limit($item->l_name) }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:15px">

                                <label for="status">{{ \App\CPU\translate('choose') }}
                                    {{ \App\CPU\translate('status') }}</label>
                                <select class="form-control" name="status">
                                    <option value="" selected>
                                        --{{ \App\CPU\translate('select_status') }}--</option>
                                    <option value="1" {{ $status != null && $status == 1 ? 'selected' : '' }}>
                                        {{ \App\CPU\translate('active') }}</option>
                                    <option value="0" {{ $status != null && $status == 0 ? 'selected' : '' }}>
                                        {{ \App\CPU\translate('inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:15px">
                                <label for="from">{{ \App\CPU\translate('from') }}</label>
                                <input type="date" name="from" id="from_date" value="{{ $from }}"
                                    class="form-control"
                                    title="{{ \App\CPU\translate('from') }} {{ \App\CPU\translate('date') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:15px">
                                <label for="to">{{ \App\CPU\translate('to') }}</label>
                                <input type="date" name="to" id="to_date" value="{{ $to }}"
                                    class="form-control"
                                    title="{{ ucfirst(\App\CPU\translate('to')) }} {{ \App\CPU\translate('date') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mt-5">
                                <button id="filter" type="submit" class="btn btn-primary btn-block"><i
                                        class="tio-filter-list nav-icon"></i>{{ \App\CPU\translate('filter') }}</button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mt-5">

                                <a href="{{ route('admin.reviews.export', ['product_id' => $product_id, 'customer_id' => $customer_id, 'status' => $status, 'from' => $from, 'to' => $to]) }}"
                                    class="btn btn-block btn-info text-capitalize font-weight-bold" data-toggle="tooltip"
                                    data-placement="top">{{ \App\CPU\translate('export') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Table -->
                <div class="col-12  card-body" style="padding: 0">
                    <div class="table-responsive datatable-custom">
                        <table style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                            class="table table-borderless table-thead-bordered table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th class="col-1">#{{ \App\CPU\translate('sl') }}</th>
                                    <th class="col-3">{{ \App\CPU\translate('Product') }}</th>
                                    <th class="col-2">{{ \App\CPU\translate('Customer') }}</th>
                                    <th class="col-1">{{ \App\CPU\translate('Rating') }}</th>
                                    <th class="col-5">{{ \App\CPU\translate('Review') }}</th>
                                    <th class="col-5">{{ \App\CPU\translate('date') }}</th>
                                    <th class="col-5">{{ \App\CPU\translate('status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $key => $review)
                                    @if ($review->product)
                                        <tr>
                                            <td class="col-1">{{ $reviews->firstItem() + $key }}</td>
                                            <td class="col-3">
                                                <span class="d-block font-size-sm text-body">
                                                    <a href="{{ route('admin.product.view', [$review['product_id']]) }}">
                                                        {{ $review->product['name'] }}
                                                    </a>
                                                </span>
                                            </td>
                                            <td class="col-2">
                                                @if ($review->customer)
                                                    <a href="{{ route('admin.customer.view', [$review->customer_id]) }}">
                                                        {{ $review->customer->f_name . ' ' . $review->customer->l_name }}
                                                    </a>
                                                @else
                                                    <label
                                                        class="badge badge-danger">{{ \App\CPU\translate('customer_removed') }}</label>
                                                @endif
                                            </td>
                                            <td class="col-1">
                                                <label class="badge badge-soft-info">
                                                    <span style="font-size: .9rem;">{{ $review->rating }} <i
                                                            class="tio-star"></i> </span>
                                                </label>
                                            </td>
                                            <td class="col-5">
                                                <p style=" word-wrap: break-word;">
                                                    {{ $review->comment ? $review->comment : 'No Comment Found' }}
                                                </p>
                                                @foreach (json_decode($review->attachment) as $img)
                                                    <a class="float-left"
                                                        href="{{ asset('storage/app/public/review') }}/{{ $img }}"
                                                        data-lightbox="mygallery">
                                                        <img style="width: 60px;height:60px;padding:10px; "
                                                            src="{{ asset('storage/app/public/review') }}/{{ $img }}"
                                                            alt="">
                                                    </a>
                                                @endforeach
                                            </td>
                                            <td class="col-1">{{ date('d M Y', strtotime($review->created_at)) }}</td>
                                            <td>
                                                <label class="toggle-switch toggle-switch-sm">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                        onclick="location.href='{{ route('admin.reviews.status', [$review['id'], $review->status ? 0 : 1]) }}'"
                                                        class="toggle-switch-input"
                                                        {{ $review->status ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $reviews->links() }}
                </div>
                @if (count($reviews) == 0)
                    <div class="text-center p-4">
                        <img class="mb-3" src="{{ asset('public/assets/back-end') }}/svg/illustrations/sorry.svg"
                            alt="Image Description" style="width: 7rem;">
                        <p class="mb-0">{{ \App\CPU\translate('No_data_to_show') }}</p>
                    </div>
                @endif
                <!-- End Table -->
            </div>
            <!-- End Card -->
        </div>
    </div>
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            // var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
    <script>
        $(document).on('change', '#from_date', function() {
            from_date = $(this).val();
            if (from_date) {
                $("#to_date").prop('required', true);
            }
        });
    </script>
    <script>
        $('#from_date , #to_date').change(function() {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
    </script>
@endpush
