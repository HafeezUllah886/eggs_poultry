@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <form>
                <div class="row g-1">
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">From</span>
                            <input type="date" class="form-control" placeholder="Username" name="start" value="{{$start}}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">To</span>
                            <input type="date" class="form-control" placeholder="Username" name="end" value="{{$end}}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Account Type</span>
                            <select name="type" id="type" class="form-control">
                                <option @selected($type == 'All') value="All">All</option>
                                <option @selected($type == 'Business') value="Business">Business</option>
                                <option @selected($type == 'Customer') value="Customer">Customer</option>
                                <option @selected($type == 'Vendor') value="Vendor">Vendor</option>
                                <option @selected($type == 'Unloader') value="Unloader">Unloader</option>
                                <option @selected($type == 'Supply Man') value="Supply Man">Supply Man</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Area</span>
                            <select name="area" id="area" class="form-control">
                                <option @selected($area == 'All') value="All">All</option>
                               @foreach ($areas as $are)
                                   <option @selected($area == $are->id) value="{{$are->id}}">{{$are->name}}</option>
                               @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                       <input type="submit" value="Filter" class="btn btn-success w-100">
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Accounts Adjustments</h3>
                    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#new">Create
                        New</button>
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
                            <th>Ref #</th>
                            <th>Adjusted By</th>
                            <th>Account</th>
                            <th>Area</th>
                            <th>Order Booker</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Notes</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($accountsAdjustments as $key => $tran)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $tran->refID }}</td>
                                    <td>{{ $tran->user->name }}</td>
                                    <td>{{ $tran->account->title }}</td>
                                    <td>{{ $tran->account->type == 'Customer' ? $tran->account->area->name : '-'}}</td>
                                    <td>{{ $tran->account->type == 'Customer' ? $tran->orderbooker() : '-'}}</td>
                                    <td>{{ $tran->type }}</td>
                                    <td>{{ date('d M Y', strtotime($tran->date)) }}</td>
                                    <td>{{ $tran->notes }}</td>
                                    <td>{{ number_format($tran->amount) }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item text-danger" href="{{route('accounts_adjustments.delete', $tran->refID)}}">
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
                    <h5 class="modal-title" id="myModalLabel">Create Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('accounts_adjustments.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-body">
                            <div class="form-group mt-2">
                                <label for="fromID">Account</label>
                                <select name="accountID" id="fromID" required class="selectize">
                                    <option value=""></option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->title }} ({{ $account->type }})</option>
                                    @endforeach
                            </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="type">Type</label>
                                <select name="type" id="type" required class="selectize">
                                    <option value=""></option>
                                    <option value="debit">Debit</option>
                                    <option value="credit">Credit</option>
                                 
                            </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" required id="amount" value="0"
                                    class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="date">Date</label>
                                <input type="date" name="date" required id="date" value="{{ date('Y-m-d') }}"
                                    class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="orderbookerID">Orderbooker</label>
                                <select name="orderbookerID" id="orderbookerID" class="form-control">
                                    @foreach ($orderbookers as $orderbooker)
                                        <option value="{{$orderbooker->id}}">{{$orderbooker->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="notes">Notes</label>
                                <textarea name="notes" required id="notes" cols="30" class="form-control" rows="5"></textarea>
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
        $(".selectize").selectize();

        function getBalance()
        {
            var id = $("#fromID").find(":selected").val();
            $.ajax({
                url: "{{ url('/accountbalance/') }}/" + id,
                method: 'GET',
                success: function(response) {
                    $("#accountBalance").html(response.data);
                    if(response.data > 0)
                    {
                        $("#accountBalance").addClass('text-success');
                        $("#accountBalance").removeClass('text-danger');
                    }
                    else
                    {
                        $("#accountBalance").addClass('text-danger');
                        $("#accountBalance").removeClass('text-success');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>

<script>
  
    function updateTotal() {
        var total = 0;
        $("input[id^='currency_']").each(function() {
            var inputId = $(this).attr('id');
            var inputVal = $(this).val();
            var inputValue = $(this).data('value');
            var value = inputVal * inputValue;
            total += parseFloat(value);
        });
        $("#total").val(total.toFixed(2));
    }
</script>
@endsection
