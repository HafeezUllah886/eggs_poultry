@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Create Product</h3>
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
                    <form action="{{ route('products.store') }}" method="post">
                        @csrf
                        <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" required id="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Purchase Price</label>
                                        <input type="number" step="any" name="pprice" required id="pprice" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Sale Price</label>
                                        <input type="number" step="any" name="sprice" required id="sprice" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">For Production</label>
                                        <select name="for_production" id="for_production" class="form-control">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                            <div class="col-12">
                                                <div class="card-header d-flex justify-content-between">
                                                    <h5>Units - Pack Sizes</h5>
                                                </div>
                                                <div class="row">
                                                    <div class="col-10 ">
                                                       
                                                    </div>
                                                    <div class="col-2">
                                                        <button class="w-100 btn btn-success" type="button" onclick="addUnit()">+</button>
                                                    </div>
                                                </div>
                                                <table  class="w-100 table">
                                                    <thead>
                                                        <th>Unit</th>
                                                        <th class="text-center">Pack Size</th>
                                                        <th></th>
                                                    </thead>
                                                    <tbody id="units">
                                                        <tr class="p-0" id="row_0">
                                                            <td width="70%" class="p-0"><input type="text" class="form-control form-control-sm" name="unit_names[]" value="Piece"></td>
                                                            <td class="p-0"><input type="number" step="any" class="form-control form-control-sm text-center" name="unit_values[]" value="1"></td>
                                                            <td class="p-0"> </td>
                                                        </tr>
    
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
    
                                   </div>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-secondary w-100">Create</button>
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
            unitCount++;
            var html = '<tr class="p-0" id="row_' + unitCount + '">';
            html += '<td width="70%" class="p-0"><input type="text" class="form-control form-control-sm" name="unit_names[]" value=""></td>';
            html += '<td class="p-0"><input type="number" step="any" class="form-control form-control-sm text-center" name="unit_values[]" value=""></td>';
            html += '<td class="p-0"> <span class="btn btn-sm btn-danger" onclick="deleteRow(' + unitCount + ')">X</span></td>';
            html += '</tr>';

            $("#units").append(html);
        }
        function deleteRow(optionCount) {
            $('#row_' + optionCount).remove();
        }

    </script>
@endsection
