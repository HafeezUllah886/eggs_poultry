@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3> Create Purchase </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse">
                                    <button onclick="window.close()" class="btn btn-danger">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <div class="form-group">
                                <label for="product">Product</label>
                                <select name="product" class="selectize" id="product">
                                    <option value=""></option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="rate">Convertion Rate</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" step="any" oninput="convert()" value="1" id="rate"
                                        required onchange="if(this.value === ''){this.value = 1;}"
                                        onblur="if(!this.value || this.value <= 0){this.value = 1;}">
                                    <select class="input-group-text form-control" onchange="convert()" id="rateType">
                                        <option value="multiply">x</option>
                                        <option value="divide">/</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('purchase.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <th width="20%">Item</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Loose</th>
                                        <th class="text-center">Bonus</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Price PKR</th>
                                        <th class="text-center">Amount PKR</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="products_list"></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end">Total</th>
                                            <th class="text-center" id="totalAmount">0.00</th>
                                            <th class="text-center"></th>
                                            <th class="text-center" id="totalAmountPKR">0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="comp">Purchase Inv No.</label>
                                    <input type="text" name="inv" id="inv" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="supplier">Supplier</label>
                                    <select name="supplierID" id="supplierID" class="selectize1">
                                        @foreach ($supplier as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group vendorName mt-2">
                                    <label for="vendorName">Name</label>
                                    <input type="text" name="vendorName" id="vendorName" class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Create Purchase</button>
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
        var existingProducts = [];

        function getSingleProduct(id) {
            $.ajax({
                url: "{{ url('purchases/getproduct/') }}/" + id,
                method: "GET",
                success: function(product) {
                    let found = $.grep(existingProducts, function(element) {
                        return element === product.id;
                    });
                    if (found.length > 0) {

                    } else {
                        var id = product.id;
                        var html = '<tr id="row_' + id + '">';
                        html += '<td class="no-padding">' + product.name + '</td>';
                        html += '<td class="no-padding"><select name="unit[]" id="unit_' + id +
                            '" onchange="updateChanges(' + id + ')" class="form-control no-padding">';
                        $.each(product.units, function(key, unit) {
                            html += '<option data-unit="'+unit.value+'" value="' + unit.id + '">' + unit.name + '</option>';
                        });
                        html += '</select></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="qty[]" oninput="updateChanges(' +
                            id +
                            ')" step="any" value="1" min="0" class="form-control text-center no-padding" id="qty_' +
                            id +
                            '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="loose[]" oninput="updateChanges(' +
                            id +
                            ')" step="any" value="0" min="0" class="form-control text-center no-padding" id="loose_' +
                            id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="bonus[]" step="any" value="0" min="0" class="form-control text-center no-padding" id="bonus_' +
                            id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="price[]" oninput="updateChanges(' +
                            id + ')" step="any" value="' + product.pprice +
                            '" min="0" class="form-control text-center no-padding" id="price_' +
                            id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="amount[]" step="any" value="0" min="0" readonly required class="form-control text-center no-padding" id="amount_' +
                            id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="price_pkr[]" step="any" value="0" min="0" readonly required class="form-control text-center no-padding" id="price_pkr_' +
                            id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="amount_pkr[]" step="any" value="0" min="0" readonly required class="form-control text-center no-padding" id="amountPKR_' +
                            id + '"></td>';
                        html +=
                            '<td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deleteRow(' +
                            id + ')">X</span> </td>';
                        html += '<input type="hidden" name="id[]" value="' + id + '" id="productID_' + id + '">';
                        html += '</tr>';
                        $("#products_list").prepend(html);
                        existingProducts.push(id);
                        updateChanges(id);
                    }
                }
            });
        }

        function updateChanges(id) {

            var rate = parseFloat($('#rate').val());
            var rateType = $('#rateType').val();

            var qty = parseFloat($('#qty_' + id).val());
            var loose = parseFloat($('#loose_' + id).val());
            var price = parseFloat($('#price_' + id).val());
            var unit = $('#unit_' + id).find(':selected').data('unit');

            var price_pkr = rateType == 'multiply' ? price * rate : price / rate;
            var amount_pkr = ((qty * unit) + loose) * price_pkr;

            var amount = ((qty * unit) + loose) * price;
            $("#amount_" + id).val(amount.toFixed(2));
            $("#amountPKR_" + id).val(amount_pkr.toFixed(2));
            $("#price_pkr_" + id).val(price_pkr.toFixed(2));
            updateTotal();
        }

        function updateTotal() {
            var amount = 0;
            $("input[id^='amount_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                amount += parseFloat(inputValue);
            });

            $("#totalAmount").html(amount.toFixed(2));
            
            var amountPkr = 0;
            $("input[id^='amountPKR_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                amountPkr += parseFloat(inputValue);
            });

            $("#totalAmountPKR").html(amountPkr.toFixed(2));

        }

        function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $('#row_' + id).remove();
            updateTotal();
        }

        function convert() {
            $("input[id^='productID_']").each(function() {
                updateChanges($(this).val());
            });
        }


        function checkAccount() {
            var id = $("#vendorID").find(":selected").val();
            if (id == 3) {
                $(".vendorName").removeClass("d-none");
                $('#status1 option').each(function() {
                    var optionValue = $(this).val();
                    if (optionValue === 'advanced' || optionValue === 'pending' || optionValue === 'partial') {
                        $(this).prop('disabled', true);
                    }
                    if (optionValue === 'paid') {
                        $(this).prop('selected', true);
                    }
                });
            } else {
                $(".vendorName").addClass("d-none");
                $('#status1 option').each(function() {
                    var optionValue = $(this).val();
                    if (optionValue === 'advanced' || optionValue === 'pending' || optionValue === 'partial') {
                        $(this).prop('disabled', false);
                    }
                });
            }
        }

        $("#vendorID").on("change", function() {
            checkAccount();
        });

        function generateCode() {

            $.ajax({
                url: "{{ url('product/generateCode') }}",
                method: "GET",
                success: function(code) {

                    $("#code1").val(code);
                }
            });

        }
        $("#code_form").on("submit", function(e) {
            e.preventDefault();
            var code = $("#code").val();
            $("#code").val('');
            $.ajax({
                url: "{{ url('product/searchByCode/') }}/" + code,
                method: "GET",
                success: function(response) {
                    if (response == "Not Found") {
                        Toastify({
                            text: "Product Not Found",
                            className: "info",
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "center", // `left`, `center` or `right`
                            stopOnFocus: true, // Prevents dismissing of toast on hover
                            style: {
                                background: "linear-gradient(to right, #FF5733, #E70000)",
                            }
                        }).showToast();
                    } else {
                        getSingleProduct(response);
                    }
                }
            });
        });
    </script>
    @foreach ($products as $product)
        @if ($product->isDefault == 'Yes')
            <script>
                getSingleProduct({{ $product->id }});
            </script>
        @endif
    @endforeach
@endsection
