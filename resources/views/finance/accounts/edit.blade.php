@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Edit Account</h3>
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
                    <form action="{{ route('account.update', $account->id) }}" method="post">
                        @csrf
                        @method('put')
                        <input type="hidden" name="accountID" value="{{$account->id}}">
                        <input type="hidden" name="type" value="{{$account->type}}">
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="title">Account Title</label>
                                    <input type="text" name="title" id="title" value="{{ $account->title }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="PKR" {{ $account->currency == 'PKR' ? 'selected' : '' }}>PKR</option>
                                        <option value="AFG" {{ $account->currency == 'AFG' ? 'selected' : '' }}>AFG</option>
                                        <option value="USD" {{ $account->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="TMN" {{ $account->currency == 'TMN' ? 'selected' : '' }}>TMN</option>
                                    </select>
                                </div>
                            </div>
                            @if($account->category == "Business")
                            <div class="col-md-6 mt-2" id="catBox">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="Bank" {{ $account->type == 'Bank' ? 'selected' : '' }}>Bank</option>
                                        <option value="Cash" {{ $account->type == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if($account->category != "Business")
                            <div class="col-md-6 mt-2 customer supplier">
                                <div class="form-group ">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" value="{{ $account->address }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 mt-2 customer s supplier">
                                <div class="form-group">
                                    <label for="contact">Contact #</label>
                                    <input type="text" name="contact" id="contact" value="{{ $account->contact }}"
                                        class="form-control">
                                </div>
                            </div>
                            @endif
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
