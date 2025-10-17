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
                                    <h3> Edit Transport </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse">
                                    <a href='{{route('transport.index')}}' class="btn btn-danger">Close</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('transport.update', $transport->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="transporter">Transporter</label>
                                    <select name="transporter_id" id="transporter" class="selectize1">
                                        @foreach ($transporters as $transporter)
                                            <option value="{{ $transporter->id }}" {{ $transport->transporter_id == $transporter->id ? 'selected' : '' }}>{{ $transporter->title }} - {{ $transporter->currency }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="customer_name">Customer Name</label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ $transport->customer_name }}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="driver_name">Driver Name</label>
                                    <input type="text" name="driver_name" id="driver_name" class="form-control" value="{{ $transport->driver_name }}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="vehicle_no">Vehicle No</label>
                                    <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" value="{{ $transport->vehicle_no }}">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="from">From</label>
                                    <input type="text" name="from" id="from" class="form-control" value="{{ $transport->from }}">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="to">To</label>
                                    <input type="text" name="to" id="to" class="form-control" value="{{ $transport->to }}">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="fare">Fare</label>
                                    <input type="number" name="fare" id="fare" oninput="calculateProfit()" value="{{ $transport->fare }}" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="expense">Expense</label>
                                    <input type="number" name="expense" id="expense" oninput="calculateProfit()" value="{{ $transport->expense }}" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="profit">Profit</label>
                                    <input type="number" name="profit" readonly id="profit" value="{{ $transport->profit }}" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d', strtotime($transport->date)) }}" id="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-6 mt-2">
                                <div class="form-group mt-2">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5">{{ $transport->notes }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Update Transport</button>
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
        
        function calculateProfit() {
            var fare = parseFloat(document.getElementById('fare').value) || 0;
            var expense = parseFloat(document.getElementById('expense').value) || 0;
            var profit = fare - expense;
            document.getElementById('profit').value = profit;
        }
    
    </script>
   
@endsection
