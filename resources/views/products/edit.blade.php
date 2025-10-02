@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Edit Product</h3>
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
                    <form action="{{ route('products.update', $product->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" value="{{ $product->name }}" required
                                        id="name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pprice">Purchase Price</label>
                                    <input type="text" name="pprice" value="{{ $product->pprice }}" required
                                        id="pprice" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sprice">Sale Price</label>
                                    <input type="text" name="sprice" value="{{ $product->sprice }}" required
                                        id="sprice" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="for_production">For Production</label>
                                    <select name="for_production" id="for_production" class="selectize">
                                        <option value="No" @selected($product->for_production == 'No')>No</option>
                                        <option value="Yes" @selected($product->for_production == 'Yes')>Yes</option>
                                        <option value="Produced" @selected($product->for_production == 'Produced')>Produced</option>
                                    </select>
                                </div>
                            </div>
                          
                            <div class="col-md-4">
                                <div class="form-group mt-2">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="selectize">
                                        <option value="Active" @selected($product->status == 'Active')>Active</option>
                                        <option value="In-active" @selected($product->status == 'In-active')>In-active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-secondary w-100">Update</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Default Modals -->


@endsection
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection

@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize();

        var unitCount = 1;

        function addUnit() {

            var unit = $("#unit").find(':selected').val();

            $.ajax({
                url: "{{ url('getUnit/') }}/" + unit,
                method: "GET",
                success: function(response) {
                    var html = '<tr class="p-0" id="row_' + response.unit_id + '">';
                    html +=
                        '<td width="70%" class="p-0"><input type="text" class="form-control form-control-sm" name="unit_names[]" value="' +
                        response.unit_name + '"></td>';
                    html +=
                        '<td class="p-0"><input type="number" step="any" class="form-control form-control-sm text-center" name="unit_values[]" value="' +
                        response.unit_value + '"></td>';
                    html += '<td class="p-0"> <span class="btn btn-sm btn-danger" onclick="deleteRow(' +
                        response.unit_id + ')">X</span></td>';
                    html += '</tr>';

                    $("#units").append(html);
                }
            });


        }

        function deleteRow(optionCount) {
            $('#row_' + optionCount).remove();
        }
    </script>
@endsection
