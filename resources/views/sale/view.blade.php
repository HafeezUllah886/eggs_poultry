@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                                <a href="javascript:window.print()" class="btn btn-primary ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                            </div>
                            <div class="card-header border-bottom-dashed p-4">
                               
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12 ">
                            <div class="row mt-0">
                                <div class="col-4"></div>
                                <div class="col-4 text-center"><h2>SALES INVOICE</h2></div>
                            </div>
                            <div class="card-body p-4 pt-1 pb-1" >
                                <div class="row g-3">
                                    <div class="col-1">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Inv #</p>
                                        <h5 class="fs-14 mb-0">{{$sale->id}}</h5>
                                    </div>
                                    <div class="col-4">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Customer</p>
                                        <h5 class="fs-14 mb-0"> <span class="text-muted">M/S :</span> {{$sale->customer->title}}</h5>
                                        @if ($sale->customerID != 3)
                                        <h5 class="fs-14 mb-0"> <span class="text-muted">Contact :</span> {{$sale->customer->contact ?? "NA"}}</h5>
                                        <h5 class="fs-14 mb-0"> <span class="text-muted">Address :</span> {{$sale->customer->address ?? "NA"}}</h5>
                                        @endif
                                    </div>
                                    <div class="col-2">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y" ,strtotime($sale->date))}}</h5>
                                    </div>
                                    <!--end col-->
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-1">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">#</th>
                                                <th scope="col" class="text-start">Product</th>
                                                <th scope="col" class="text-start">Unit</th>
                                                <th scope="col" class="text-start">Pack Size</th>
                                                <th scope="col" class="text-end">Qty</th>
                                                <th scope="col" class="text-end">Loose</th>
                                                <th scope="col" class="text-end">Bonus</th>
                                                <th scope="col" class="text-end">Price ({{ $sale->customer->currency }})</th>
                                                <th scope="col" class="text-end">Amount ({{ $sale->customer->currency }})</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                            @php
                                                $totalQty = 0;
                                                $totalLoose = 0;
                                                $totalBonus = 0;
                                                $totalAmount = 0;
                                            @endphp
                                           @foreach ($sale->details as $key => $product)
                                           @php
                                           $qty = $product->pc;
                                           $totalQty += $product->qty;
                                           $totalLoose += $product->loose;
                                           $totalBonus += $product->bonus;
                                           $totalAmount += $product->amount;
                                            @endphp
                                               <tr>
                                                <td class="p-1 m-1">{{$key+1}}</td>
                                                <td class="text-start p-1 m-1">{{$product->product->name}}</td>
                                                <td class="text-start m-1 p-1">{{$product->unit_name}}</td>
                                                <td class="text-center m-1 p-1">{{number_format($product->unit_value)}}</td>
                                                <td class="text-center m-1 p-1">{{number_format($product->qty)}}</td>
                                                <td class="text-center m-1 p-1">{{number_format($product->loose)}}</td>
                                                <td class="text-center m-1 p-1">{{number_format($product->bonus)}}</td>
                                                <td class="text-end p-1 m-1">{{number_format($product->price * $product->unit_value)}}</td>
                                                <td class="text-end p-1 m-1">{{number_format($product->amount)}}</td>
                                               </tr>
                                           @endforeach
                                        </tbody>
                                        <tfoot>
                                           <tr>
                                                <th colspan="4" class="text-end">Total</th>
                                                <th class="text-center">{{number_format($totalQty)}}</th>
                                                <th class="text-center">{{number_format($totalLoose)}}</th>
                                                <th class="text-center">{{number_format($totalBonus)}}</th>
                                                <th></th>
                                                <th class="text-end">{{number_format($sale->total, 0)}}</th>
                                            </tr> 
                                          
                                        </tfoot>
                                    </table><!--end table-->
                                </div>
                            </div>
                            <div class="card-footer">
                                @if ($sale->notes != "")
                                <p><strong>Notes: </strong>{{$sale->notes}}</p>
                                @endif
                              
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->

                    </div><!--end row-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->

@endsection

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
<!--datatable responsive css-->
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/responsive.bootstrap.min.css') }}" />

<link rel="stylesheet" href="{{ asset('assets/libs/datatable/buttons.dataTables.min.css') }}">
<link href='https://fonts.googleapis.com/css?family=Noto Nastaliq Urdu' rel='stylesheet'>
<style>
    .urdu {
        font-family: 'Noto Nastaliq Urdu';font-size: 12px;
    }
    </style>
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/vfs_fonts.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/pdfmake.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/jszip.min.js')}}"></script>

    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection

