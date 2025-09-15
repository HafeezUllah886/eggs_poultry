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
                                        <h1>{{projectNameHeader()}}</h1>
                                    </div>
                                    <div class="flex-shrink-0 mt-sm-0 mt-3">
                                        <h3>Engine Oil Report</h3>
                                    </div>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">From</p>
                                        <h5 class="fs-14 mb-0">{{ date("d M Y", strtotime($from)) }}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">To</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y", strtotime($to))}}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Printed On</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ date("d M Y") }}</span></h5>
                                        {{-- <h5 class="fs-14 mb-0"><span id="total-amount">{{ \Carbon\Carbon::now()->format('h:i A') }}</span></h5> --}}
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col">#</th>
                                                <th scope="col" class="text-start">Product</th>
                                                <th scope="col" class="text-start">Packing</th>
                                                <th scope="col">Purchase Qty</th>
                                                <th scope="col">Purchase Amount</th>
                                                <th scope="col">Export Qty</th>
                                                <th scope="col">Export Amount</th>
                                                <th scope="col">Stock</th>
                                                <th scope="col">Stock Value</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                           
                                        @foreach ($products as $key => $product)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td class="text-start p-1 m-0"> {{$product->code}} - {{$product->name}} - {{$product->ltr}} - {{$product->grade}}</td>
                                                <td class="text-start p-1 m-0">{{$product->packing}}</td>
                                                <td class="text-end p-1 m-0">{{ number_format($product->purchase_qty) }}</td>
                                                <td class="text-end p-1 m-0">{{ number_format($product->purchase_amount) }}</td>
                                                <td class="text-end p-1 m-0">{{ number_format($product->export_qty) }}</td>
                                                <td class="text-end p-1 m-0">{{ number_format($product->export_amount) }}</td>
                                                <td class="text-end p-1 m-0">{{ number_format($product->stock) }}</td>
                                                <td class="text-end p-1 m-0">{{ number_format($product->stock_value) }}</td>                                             
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end p-1 m-0">Total</th>
                                                <th class="text-end p-1 m-0">{{number_format($products->sum('purchase_qty'))}}</th>
                                                <th class="text-end p-1 m-0">{{number_format($products->sum('purchase_amount'))}}</th>
                                                <th class="text-end p-1 m-0">{{number_format($products->sum('export_qty'))}}</th>
                                                <th class="text-end p-1 m-0">{{number_format($products->sum('export_amount'))}}</th>
                                                <th class="text-end p-1 m-0">{{number_format($products->sum('stock'))}}</th>
                                                <th class="text-end p-1 m-0">{{number_format($products->sum('stock_value'))}}</th>
                                            </tr>
                                        </tfoot>
                                    </table><!--end table-->
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <!--end col-->
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Total Purchase</p>
                                        <h5 class="fs-14 mb-0">{{number_format($products->sum('purchase_amount'))}}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Total Export</p>
                                        <h5 class="fs-14 mb-0">{{number_format($products->sum('export_amount'))}}</h5>
                                    </div>
                                    <!--end col-->
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Total TT Paid</p>
                                        <h5 class="fs-14 mb-0">{{number_format($tt)}}</h5>
                                    </div>
                                    <div class="col-3">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Total Stock Value</p>
                                        <h5 class="fs-14 mb-0">{{number_format($products->sum('stock_value'))}}</h5>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
@endsection
