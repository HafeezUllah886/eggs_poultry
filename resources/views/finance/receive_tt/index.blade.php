@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <form>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">From</span>
                            <input type="date" class="form-control" placeholder="Username" name="start" value="{{$start}}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">To</span>
                            <input type="date" class="form-control" placeholder="Username" name="end" value="{{$end}}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-2">
                       <input type="submit" value="Filter" class="btn btn-success w-100">
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>TT Receiving</h3>
                    <div>
                        <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#new">Create
                            New</button>
                    </div>
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
                            <th>From</th>
                            <th>Date</th>
                            <th>Bank</th>
                            <th>Total Dirham</th>
                            <th>Dirham Received</th>
                            <th>Total Yen</th>
                          
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($tts as $key => $tt)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $tt->received_from }}</td>
                                    <td>{{date('d-m-Y', strtotime($tt->date)) }}</td>
                                    <td>{{ $tt->bank->title }}</td>
                                    <td>{{ number_format($tt->total_dirham) }}</td>
                                    <td>{{ number_format($tt->dirham_received) }}</td>
                                    <td>{{ number_format($tt->total_yen) }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item text-info" href="{{route('receive_t_t_s.show', $tt->id)}}">
                                                        <i class="ri-eye-fill align-bottom me-2 text-info"></i>
                                                        View
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="{{route('receive_t_t_s.delete', $tt->id)}}">
                                                        <i class="ri-delete-bin-2-fill align-bottom me-2 text-danger"></i>
                                                        Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end">Total:</td>
                                <td>{{ number_format($tts->sum('total_yen')) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Default Modals -->

    <div id="new" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Create TT Receiving</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('receive_t_t_s.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mt-2">
                                    <label for="received_from">Received From</label>
                                    <input type="text" name="received_from" required id="received_from"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mt-2">
                                    <label for="category">Bank</label>
                                    <select name="bank_id" id="category" required class="selectize">
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mt-2">
                                    <label for="total_dirham">Total Dirham</label>
                                    <input type="number" step="any" oninput="calculateAmount()" name="total_dirham" required id="total_dirham"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mt-2">
                                    <label for="bank_charges">Bank Charges</label>
                                    <input type="number" step="any" oninput="calculateAmount()" value="0" name="bank_charges" required id="bank_charges"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mt-2">
                                    <label for="dirham_received">Dirham Received</label>
                                    <input type="number" step="any" oninput="calculateAmount()" name="dirham_received" required id="dirham_received"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mt-2">
                                    <label for="rate">Rate</label>
                                    <input type="number" step="any" oninput="calculateAmount()" value="1" name="rate" required id="rate"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mt-2">
                                    <label for="total_yen">Total Yen Received</label>
                                    <input type="number" step="any" readonly name="total_yen" required id="total_yen"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mt-2">
                                    <label for="date">Date</label>
                                    <input type="date" step="any" value="{{date('Y-m-d')}}" name="date" required id="date"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mt-2">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" cols="30" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
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
        $(document).ready(function() {
            $('.selectize').selectize();
        });
    </script>

    <script>
        function calculateAmount() {
            var dirham = parseFloat(document.getElementById('total_dirham').value);
            var bank_charges = parseFloat(document.getElementById('bank_charges').value);
            var rate = parseFloat(document.getElementById('rate').value);
         
            var total_dirham = dirham - bank_charges;
            var yen = total_dirham / rate;
           
            document.getElementById('dirham_received').value = total_dirham;

            document.getElementById('total_yen').value = yen;
        }
    </script>

@endsection
