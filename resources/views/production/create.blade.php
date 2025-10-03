@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3> Create Production </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse">
                                    <a href='{{route('production.index')}}' class="btn btn-danger">Close</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('production.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="initial_product">Initial Product</label>
                                    <select name="initial_product" id="initial_product" class="selectize1">
                                        @foreach ($initial_products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="initial_qty">Initial Qty</label>
                                    <input type="number" name="initial_qty" id="initial_qty" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="final_product">Final Product</label>
                                    <select name="final_product" id="final_product" class="selectize1">
                                        @foreach ($final_products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="final_qty">Final Qty</label>
                                    <input type="number" name="final_qty" id="final_qty" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="expense">Expense</label>
                                    <input type="number" name="expense" id="expense" value="0" class="form-control" min="0">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="expense">Account</label>
                                    <select name="account" id="account" class="selectize1">
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mt-2">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Create Production</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
    <style>
        .no-padding {
            padding: 5px 5px !important;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize1").selectize();
        $(".selectize").selectize({
            onChange: function(value) {
                if (!value.length) return;
                if (value != null) {
                    getSingleProduct(value);
                    this.clear();
                    this.focus();
                }

            },
        });
    
    </script>
   
@endsection
