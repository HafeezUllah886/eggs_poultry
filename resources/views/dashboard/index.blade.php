@extends('layout.app')
@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-text">From</div>
            <input type="date" class="form-control" id="from" onchange="filterData()" value="{{ $from }}" >
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-text">To</div>
            <input type="date" class="form-control" id="to" onchange="filterData()" value="{{ $to }}" >
        </div>
    </div> 
</div>
<div class="row">


</div>
@endsection

@section('page-css')

@endsection
@section('page-js')
       <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
       <script src="{{asset('assets/js/pages/dashboard-ecommerce.init.js')}}"></script>
       <script>
        function filterData(){
            var from = $('#from').val();
            var to = $('#to').val();
            
            window.location.href = `{{ route('dashboard', ['from' => '']).'&to=' }}${to}`.replace('from=&', `from=${from}&`);
        }
       </script>
@endsection
