@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                                <a href="javascript:window.print()" class="btn btn-success ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                            </div>
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h1>{{projectNameAuth()}}</h1>
                                    </div>
                                    <div class="flex-shrink-0 mt-sm-0 mt-3">
                                        <h3>Oil Purchase Vouchar</h3>
                                    </div>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12 ">

                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Vouchar #</p>
                                        <h5 class="fs-14 mb-0">{{$purchase->id}}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y" ,strtotime($purchase->date))}}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Vendor</p>
                                        <h5 class="fs-14 mb-0">{{$purchase->vendor->title}}</h5>
                                        <h5 class="fs-14 mb-0">{{$purchase->vendorID == 3 ? $purchase->vendorName : ""}}</h5>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                                <thead>
                                                    <tr class="table-active">
                                                        <th scope="col" style="width: 50px;">#</th>
                                                        <th scope="col" class="text-start">Code</th>
                                                        <th scope="col" class="text-start">Product</th>
                                                        <th scope="col" class="text-start">Ltr / Weight</th>
                                                        <th scope="col" class="text-start">Grade</th>
                                                        <th scope="col" class="text-start">Packing</th>
                                                        <th scope="col" class="text-end">Qty</th>
                                                        <th scope="col" class="text-end">Price</th>
                                                        <th scope="col" class="text-end">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="products-list">
                                                   @foreach ($purchase->details as $key => $product)
                                                       <tr>
                                                        <td class="p-1 m-1">{{$key+1}}</td>
                                                        <td class="text-start p-1 m-1">{{$product->product->code}}</td>
                                                        <td class="text-start p-1 m-1">{{$product->product->name}}</td>
                                                        <td class="text-start p-1 m-1">{{$product->product->ltr}}</td>
                                                        <td class="text-start p-1 m-1">{{$product->product->grade}}</td>
                                                        <td class="text-start p-1 m-1">{{$product->product->packing}}</td>
                                                        <td class="text-end p-1 m-1">{{number_format($product->qty)}}</td>
                                                        <td class="text-end p-1 m-1">{{number_format($product->price,2)}}</td>
                                                        <td class="text-end p-1 m-1">{{number_format($product->amount,2)}}</td>
                                                       </tr>
                                                   @endforeach
                                                </tbody>
                                            </table><!--end table-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8"></div>
                                    <div class="col-4">
                                        @php
                                            $amount = $purchase->details->sum('amount');
                                            $tax = $purchase->tax;
                                        @endphp
                                        <table class="table">
                                            <tr>
                                                <th class="text-end p-1 m-1">Total</th>
                                                <th class="text-end p-1 m-1">{{number_format($amount, 2)}}</th>
                                            </tr>
                                            <tr>
                                                <th class="text-end p-1 m-1">Tax - {{$purchase->tax}}%</th>
                                                <th class="text-end p-1 m-1">{{number_format($purchase->tax_value, 2)}}</th>
                                            </tr>
                                            <tr>
                                                <th class="text-end p-1 m-1">Net Bill </th>
                                                <th class="text-end p-1 m-1">{{number_format($purchase->total, 2)}}</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>


                            </div>
                            <div class="card-footer">
                                <p><strong>Notes: </strong>{{$purchase->note}}</p>
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

