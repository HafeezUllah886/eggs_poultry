@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <form>
                <div class="row g-1">
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">From</span>
                            <input type="date" class="form-control" placeholder="Username" name="from"
                                value="{{ $from }}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">To</span>
                            <input type="date" class="form-control" placeholder="Username" name="to"
                                value="{{ $to }}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" value="Filter" class="btn btn-success w-100">
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Productions</h3>
                    <button data-bs-toggle="modal" data-bs-target="#new" class="btn btn-primary">Create New</button>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <table class="table" id="buttons-datatables">
                        <thead>
                            <th>#</th>
                            <th>Date</th>
                            <th>Initail Product</th>
                            <th>Final Product</th>
                            <th>Initial Qty</th>
                            <th>Final Qty</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($productions as $key => $production)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ date('d M Y', strtotime($production->date)) }}</td>
                                    <td>{{ $production->initial_product->name }}</td>
                                    <td>{{ $production->final_product->name }}</td>
                                    <td>{{ number_format($production->initial_qty) }}</td>
                                    <td>{{ number_format($production->final_qty) }}</td>
                                    <td>{{ $production->status }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if($production->status == "Pending")
                                                <li>
                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#completion_{{ $production->id }}">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                        Mark Completed
                                                    </a>
                                                </li>
                                                @endIf

                                                {{--  <li>
                                                    <button class="dropdown-item" onclick="newWindow('{{route('production.show', $production->id)}}')"
                                                        onclick=""><i
                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                        View
                                                    </button>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onclick="newWindow('{{route('production.edit', $production->id)}}')">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                        Edit
                                                    </a>
                                                </li>
                                                
                                                 <li>
                                                    <a class="dropdown-item text-danger" href="{{route('sales.delete', $sale->id)}}">
                                                        <i class="ri-delete-bin-2-fill align-bottom me-2 text-danger"></i>
                                                        Delete
                                                    </a>
                                                </li>  --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <div id="completion_{{ $production->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel"
                                    aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myModalLabel">Complete Production</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"> </button>
                                            </div>
                                            <form action="{{ route('production.complete') }}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{ $production->id }}" name="id">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="initial_product">Initial Product</label>
                                                        <input type="text" class='form-control' disabled value="{{ $production->initial_product->name }}">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="initial_qty">Initial Qty</label>
                                                        <input type="number" disabled name="initial_qty" id="initial_qty"
                                                            class="form-control" value="{{ $production->initial_qty }}">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="final_product">Final Product</label>
                                                        <input type="text" class='form-control' disabled value="{{ $production->final_product->name }}">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="final_product">Produced Qty</label>
                                                        <input type="number" min="0" name="qty" class='form-control' required>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="date">Production Date</label>
                                                        <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                                            id="date" class="form-control">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="notes">Notes</label>
                                                        <textarea name="notes" id="notes" class="form-control" cols="30" rows="5">{{$production->notes}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Default Modals -->

    <div id="new" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Create Production</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('production.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="initial_product">Initial Product</label>
                            <select name="initial_product" id="initial_product" class="selectize1">
                                @foreach ($initial_products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="initial_qty">Initial Qty</label>
                            <input type="number" name="initial_qty" id="initial_qty" class="form-control"
                                min="0">
                        </div>

                        <div class="form-group mt-2">
                            <label for="final_product">Final Product</label>
                            <select name="final_product" id="final_product" class="selectize1">
                                @foreach ($final_products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="date">Date</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date"
                                class="form-control">
                        </div>


                        <div class="form-group mt-2">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" cols="30" rows="5"></textarea>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/responsive.bootstrap.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/jszip.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
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
