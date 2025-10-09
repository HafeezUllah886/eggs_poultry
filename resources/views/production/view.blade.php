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
                                        <h3>Production Vouchar</h3>
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
                                        <h5 class="fs-14 mb-0">{{$production->id}}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Starting Date</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y" ,strtotime($production->date))}}</h5>
                                    </div>
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Production Date</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y" ,strtotime($production->production_date))}}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Status</p>
                                        <h5 class="fs-14 mb-0">{{$production->status}}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-3 ">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Initial Product</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ $production->initial_product->name }}</span></h5>
                                    </div>
                                    <div class="col-3 ">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Initial Qty</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ $production->initial_qty }}</span></h5>
                                    </div>
                                    <div class="col-3 ">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Final Product</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ $production->final_product->name }}</span></h5>
                                    </div>
                                    <div class="col-3 ">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Production Qty</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ $production->final_qty }}</span></h5>
                                    </div>
                                    <div class="col-12">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Notes</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ $production->notes }}</span></h5>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
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

