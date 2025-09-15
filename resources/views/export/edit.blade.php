@extends('layout.popups')

@section('content')
<script>
    var existingProducts = [];

    @foreach ($export->export_cars as $car)
        @php
            $productID = $car->purchase_id;
        @endphp
        existingProducts.push({{$productID}});
    @endforeach

    var existingParts = [];

    var partRowId = 0;
    @foreach ($export->export_parts as $part)
        partRowId++;
        existingParts.push(partRowId);
    @endforeach

    var existingOils = [];
    @foreach ($export->export_oils as $oil)
        existingOils.push({{$oil->product_id}});
    @endforeach

</script>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3> Edit Export </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse"><button onclick="window.close()"
                                        class="btn btn-danger">Close</button></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('export.update', $export->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="product">Cars</label>
                                        <select name="product" class="selectize" id="product">
                                            <option value="0"></option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->model }} -
                                                    {{ $product->chassis }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th width="">Chassis</th>
                                            <th class="text-start">Model</th>
                                            <th width="" class="text-center">Price</th>
                                            <th class="text-center">Remarks</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="products_list">
                                            @foreach ($export->export_cars as $car)
                                            @php
                                                $row_id = $car->purchase_id;
                                            @endphp
                                            <tr id="row_{{ $row_id }}">
                                                <td class="no-padding text-start">{{ $car->purchase->chassis }}</td>
                                                <td class="no-padding text-start">{{ $car->purchase->model }}</td>
                                                <td class="no-padding"><input type="number" name="car_price[]" oninput="updateTotal()" required step="any" value="{{ $car->price }}" min="0" class="form-control text-center" id="price_{{ $row_id }}"></td>
                                                <td class="no-padding">
                                                   <select name="car_remarks[]" class="form-control text-center" id="remarks_{{ $row_id }}">
                                                    <option value="Complete" @selected($car->remarks == 'Complete')>Complete</option>
                                                    <option value="Roof Cut" @selected($car->remarks == 'Roof Cut')>Roof Cut</option>
                                                   </select>
                                                </td>
                                                <td> <span class="btn btn-sm btn-danger" onclick="deleteRow({{ $row_id }})">X</span> </td>
                                                <input type="hidden" name="car_id[]" value="{{ $row_id }}">
                                            </tr>
                                           
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-end">Total</th>
                                                <th class="text-end" id="totalPrice">{{$export->export_cars->sum('price')
                                                    }}</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="part">Parts</label>
                                        <select name="part" class="selectize2" id="part">
                                            <option value="0"></option>
                                            @foreach ($parts as $part)
                                                <option value="{{ $part->name }}">{{ $part->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th width="">Part</th>
                                            <th width="" class="text-center">Qty</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="parts_list">
                                            @foreach ($export->export_parts as $key => $part)
                                            @php
                                                $row_id = $key+1;
                                            @endphp
                                            <tr id="rowpart_{{ $row_id }}">
                                                <td class="no-padding text-start">{{ $part->part_name }}</td>
                                                <td class="no-padding"><input type="number" name="part_qty[]" required step="any" value="{{ $part->qty }}" min="0" class="form-control text-center" id="qty_{{ $row_id }}"></td>
                                                <td> <span class="btn btn-sm btn-danger" onclick="deletePart({{ $row_id }})">X</span> </td>
                                                <input type="hidden" name="part_name[]" value="{{ $row_id }}">
                                            </tr>
                                            
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                               
                            </div>
                           
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="row">
                                    <div class="col-6">
                                        <h5> Engines </h5>
                                    </div>
                                    <div class="col-6 d-flex flex-row-reverse"><button type="button" onclick="addEngine()"
                                            class="btn btn-primary">Add</button></div>
                                </div>
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th>Series</th>
                                            <th class="text-center">Model</th>
                                            <th class="text-center">Price</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="engines_list">
                                           @foreach ($export->export_engines as $key => $engine)
                                           @php
                                               $row_id = $key+1;
                                           @endphp
                                           <tr id="rowengine_{{ $row_id }}">
                                                <td class="no-padding text-start">{{ $engine->series }}</td>
                                                <td class="no-padding text-start">{{ $engine->model }}</td>
                                                <td class="no-padding"><input type="number" name="engine_price[]" oninput="updateEngineTotal({{ $row_id }})" required step="any" value="{{ $engine->price }}" min="0" class="form-control text-center" id="engine_price_{{ $row_id }}"></td>
                                                <td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deleteRowEngine({{ $row_id }})">X</span> </td>
                                                <input type="hidden" name="engine_id[]" value="{{ $row_id }}">
                                            </tr>
                                           @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-end">Total:</td>
                                                <td id="engineTotalPrice" class="text-center">0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="row">
                                    <div class="col-6">
                                        <h5> Misc </h5>
                                    </div>
                                    <div class="col-6 d-flex flex-row-reverse"><button type="button" onclick="addMisc()"
                                            class="btn btn-primary">Add</button></div>
                                </div>
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th>Description</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Price</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="misc_list">
                                            @foreach ($export->export_misc as $key => $misc)
                                            <tr id="rowmisc_{{ $key+1 }}">
                                                <td class="no-padding text-start"><input type="text" class="form-control" name="misc_description[]" value="{{ $misc->description }}"></td>
                                                <td class="no-padding"><input type="text" class="form-control" name="misc_qty[]" id="misc_qty_{{ $key+1 }}" oninput="updateMiscTotal({{ $key+1 }})" value="{{ $misc->qty }}"></td>
                                                <td class="no-padding"><input type="number" class="form-control" name="misc_price[]" id="misc_price_{{ $key+1 }}" oninput="updateMiscTotal({{ $key+1 }})" value="{{ $misc->price }}"></td>
                                                <td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deleteRowMisc({{ $key+1 }})">X</span> </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-end">Total:</td>
                                                <td id="miscTotalPrice" class="text-center">0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="product">Engine Oil</label>
                                        <select name="product" class="selectize4" id="product">
                                            <option value="0"></option>
                                            @foreach ($oils as $oil)
                                                <option value="{{ $oil->id }}"> {{$oil->code}} - {{ $oil->name }} - {{ $oil->ltr }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th width="">Description</th>
                                            <th class="text-center">Price</th>
                                            <th width="" class="text-center">Qty</th>
                                            <th class="text-center">Amount</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="oil_list">
                                            @foreach ($export->export_oils as $key => $oil)
                                            @php
                                                $productRowID = $oil->product_id;
                                            @endphp
                                            <tr id="rowOil_{{ $productRowID }}">
                                            <td class="">{{ $oil->product->code}} - {{ $oil->product->name}} - {{ $oil->product->ltr}}</td>
                                            <td class=""><input type="number" name="priceOil[]" oninput="updateChangesOil({{ $productRowID }})" step="any" value="{{ $oil->price }}" min="1" class="form-control text-center" id="priceOil_{{ $productRowID }}"></td>
                                            <td class=""><div class="input-group"><input type="number" name="qtyOil[]" oninput="updateChangesOil({{ $productRowID }})" min="0" step="any" value="{{ $oil->qty }}" class="form-control text-center" id="qtyOil_{{ $productRowID }}"><div class="input-group-append"><span class="input-group-text">{{ $oil->product->packing }}</span></div></div></td>
                                            <td class=""><input type="number" name="amountOil[]" min="0.1" readonly required step="any" value="{{ $oil->price * $oil->qty }}" class="form-control text-center" id="amountOil_{{ $productRowID }}"></td>
                                            <td class=""> <span class="btn btn-sm btn-danger" onclick="deleteRowOil({{ $productRowID }})">X</span> </td>
                                            <input type="hidden" name="idOil[]" value="{{ $productRowID }}">
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Total</th>
                                                <th class="text-end" id="oilTotalAmount">0.00</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="inv_no">Inv #</label>
                                <input type="text" class="form-control"  required name="inv_no" value="{{ $export->inv_no }}">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="c_no">C/No</label>
                                <input type="text" class="form-control" required name="c_no" value="{{ $export->c_no }}">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="weight">Weight</label>
                                <input type="number" class="form-control" required name="weight" value="{{ $export->weight }}">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" required name="date" value="{{ date('Y-m-d', strtotime($export->date)) }}">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mt-2">
                                <label for="consignee">Consignee</label>
                                <select name="consignee" required class="selectize1" id="consignee">
                                    <option value=""></option>
                                    @foreach ($consignees as $consignee)
                                        <option value="{{ $consignee->id }}" @selected($consignee->id == $export->consignee_id)>{{ $consignee->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mt-2">
                                <label for="info_party">Info Party</label>
                                <select name="info_party" required class="selectize1" id="info_party">
                                    <option value=""></option>
                                    @foreach ($consignees as $consignee)
                                        <option value="{{ $consignee->id }}" @selected($consignee->id == $export->info_party_id)>{{ $consignee->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary w-100">Update Export</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
    <style>
        .no-padding {
            padding: 5px 5px !important;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize({
            onChange: function(value) {
                if (!value.length) return;
                if (value != 0) {
                    getSingleProduct(value);
                    this.clear();
                    this.focus();
                }
            },
        });

        function getSingleProduct(id) {
            $.ajax({
                url: "{{ url('export/getproduct/') }}/" + id,
                method: "GET",
                success: function(product) {
                    let found = $.grep(existingProducts, function(element) {
                        return element === product.id;
                    });
                    if (found.length > 0) {

                    } else {
                        var id = product.id;
                        var html = '<tr id="row_' + id + '">';
                        html += '<td class="no-padding text-start">' + product.chassis + '</td>';
                        html += '<td class="no-padding text-start">' + product.model + '</td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="car_price[]" oninput="updateTotal()" required step="any" value="'+product.total+'" min="0" class="form-control text-center" id="price_' +
                            id + '"></td>';
                        html += `<td class="no-padding">
                           <select name="car_remarks[]" class="form-control text-center" id="remarks_${id}">
                            <option value="Complete">Complete</option>
                            <option value="Roof Cut">Roof Cut</option>
                           </select>
                        </td>`;
                        html += '<td> <span class="btn btn-sm btn-danger" onclick="deleteRow(' + id +
                            ')">X</span> </td>';
                        html += '<input type="hidden" name="car_id[]" value="' + id + '">';
                        html += '</tr>';
                        $("#products_list").append(html);
                        updateTotal();
                        existingProducts.push(id);
                    }
                }
            });
        }

        function updateTotal() {

            var totalPrice = 0;
            $("input[id^='price_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                totalPrice += parseFloat(inputValue);
            });

            $("#totalPrice").html(totalPrice.toFixed(2));

        }

        function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $('#row_' + id).remove();
            updateTotal();
        }

        $(".selectize2").selectize({
            onChange: function(value) {
                if (!value.length) return;
                if (value != 0) {
                    addPart(value);
                    this.clear();
                    this.focus();
                }

            },
        });

    
        function addPart(value) {
            let found = $.grep(existingParts, function(element) {
                return element === value;
            });
            if (found.length > 0) {
            } else {
                partRowId++;
                var html = '<tr id="rowpart_' + partRowId + '">';
                html += '<td class="no-padding text-start">' + value + '</td>';
                html += '<td class="no-padding"><input type="number" name="part_qty[]" required step="any" value="1" min="0" class="form-control text-center" id="qty_' +
                    partRowId + '"></td>';
                html += '<td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deletePart(' + partRowId +
                    ')">X</span> </td>';
                html += '<input type="hidden" name="part_name[]" value="' + value + '">';
                html += '</tr>';
                $("#parts_list").append(html);
                existingParts.push(value);
            }
        }

        function deletePart(id) {
            var partName = $('#rowpart_' + id + ' input[name="part_name[]"]').val();
            existingParts = $.grep(existingParts, function(value) {
                return value !== partName;
            });
            $('#rowpart_' + id).remove();
            updateTotal();
        }


        var engineRowId = {{ $export->export_engines->count() }};
        function addEngine() {
            engineRowId++;
            var html = '<tr id="rowengine_' + engineRowId + '">';
            html += '<td class="no-padding text-start"><input type="text" class="form-control" name="engine_series[]" value=""></td>';
            html += '<td class="no-padding"><input type="text" class="form-control" name="engine_model[]" value=""></td>';
            html += '<td class="no-padding"><input type="number" class="form-control" name="engine_price[]" id="engine_price_' + engineRowId + '" oninput="updateEngineTotal(' + engineRowId +')" value="0"></td>';
            html += '<td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deleteRowEngine(' + engineRowId +')">X</span> </td>';
            html += '</tr>';
            $("#engines_list").append(html);
        }
        
        function deleteRowEngine(rowId) {
            $('#rowengine_' + rowId).remove();
            updateEngineTotal();
        }

        function updateEngineTotal(rowId) {
            var totalEnginePrice = 0;
            $("input[id^='engine_price_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                totalEnginePrice += parseFloat(inputValue);
            });
            $("#engineTotalPrice").html(totalEnginePrice.toFixed(2));

        }

        var miscRowId = 0;

        function addMisc() {
            miscRowId++;
            var html = '<tr id="rowmisc_' + miscRowId + '">';
            html += '<td class="no-padding text-start"><input type="text" class="form-control" name="misc_description[]" value=""></td>';
            html += '<td class="no-padding"><input type="text" class="form-control" name="misc_qty[]" id="misc_qty_' + miscRowId + '" oninput="updateMiscTotal(' + miscRowId +')" value=""></td>';
            html += '<td class="no-padding"><input type="number" class="form-control" name="misc_price[]" id="misc_price_' + miscRowId + '" oninput="updateMiscTotal(' + miscRowId +')" value=""></td>';
            html += '<td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deleteRowMisc(' + miscRowId +')">X</span> </td>';
            html += '</tr>';
            $("#misc_list").append(html);
        }
        
        function deleteRowMisc(rowId) {
            $('#rowmisc_' + rowId).remove();
            updateMiscTotal();
        }

        function updateMiscTotal(rowId) {
            var totalMiscPrice = 0;
            $("input[id^='misc_price_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                totalMiscPrice += parseFloat(inputValue);
            });
            $("#miscTotalPrice").html(totalMiscPrice.toFixed(2));
          
        }

        $(".selectize1").selectize();


        $(".selectize4").selectize({
            onChange: function(value) {
                if (!value.length) return;
                if (value != 0) {
                    getSingleOil(value);
                    this.clear();
                    this.focus();
                }

            },
        });

function getSingleOil(id) {
    $.ajax({
        url: "{{ url('oilpurchase/getproduct/') }}/" + id,
        method: "GET",
        success: function(product) {
            let found = $.grep(existingOils, function(element) {
                return element === product.id;
            });
            if (found.length > 0) {

            } else {

                var id = product.id;
                var html = '<tr id="rowOil_' + id + '">';
                html += '<td class="">' + product.code + ' - ' + product.name +  ' - ' + product.ltr +'</td>';
                html += '<td class=""><input type="number" name="priceOil[]" oninput="updateChangesOil(' + id + ')" step="any" value="'+product.pprice+'" min="1" class="form-control text-center" id="priceOil_' + id + '"></td>';
                html += '<td class=""><div class="input-group"><input type="number" name="qtyOil[]" oninput="updateChangesOil(' + id + ')" min="0" step="any" value="1" class="form-control text-center" id="qtyOil_' + id + '"><div class="input-group-append"><span class="input-group-text">'+product.packing+'</span></div></div></td>';
                html += '<td class=""><input type="number" name="amountOil[]" min="0.1" readonly required step="any" value="1" class="form-control text-center" id="amountOil_' + id + '"></td>';
                html += '<td class=""> <span class="btn btn-sm btn-danger" onclick="deleteRowOil('+id+')">X</span> </td>';
                html += '<input type="hidden" name="idOil[]" value="' + id + '">';
                html += '</tr>';
                $("#oil_list").prepend(html);
                existingOils.push(id);
                updateChanges(id);
            }
        }
    });
}

function updateChangesOil(id) {
    var qty = parseFloat($('#qtyOil_' + id).val());
    var price = parseFloat($('#priceOil_' + id).val());

    var amount = qty * price;
    $("#amountOil_"+id).val(amount.toFixed(2));
    updateTotalOil();
}

function updateTotalOil() {
    var total = 0;
    $("input[id^='amountOil_']").each(function() {
        var inputId = $(this).attr('id');
        var inputValue = $(this).val();
        total += parseFloat(inputValue);
    });

    $("#oilTotalAmount").html(total.toFixed(2));

}

function deleteRowOil(id) {
    existingOils = $.grep(existingOils, function(value) {
        return value !== id;
    });
    $('#rowOil_'+id).remove();
    updateTotalOil();

    
}

updateEngineTotal();
updateMiscTotal();
updateTotalOil();
    </script>
@endsection
