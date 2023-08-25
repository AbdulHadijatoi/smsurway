@extends('layouts.TableHeader')
@section('content')
@section('title','Purchase Credit')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Purchase Credit</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Purchase Credit</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>Purchase Credit 
                            </h2>
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class='alert alert-danger'>
                                @foreach ($errors->all() as $error)
                                    {{$error}}
                                @endforeach
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session()->get('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if ($transactionSuccessful === false && request()->has('TransID'))
                                <div class="alert alert-danger">
                                    We were unable to proccess your transaction, please contact our support team with the code <strong>{{ $transactionCode }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if ($transactionSuccessful === true && request()->has('TransID'))
                                <div class="alert alert-success">
                                    Your transaction was successful and your credit has been topped up.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <p>
                                You are about to add funds to your account. <br><br>
                                <a href="#manual" data-toggle="modal" data-target="#manual" title="click here for add new Keyword">Click here </a> Manual Payment without Payment Gateway
                                OR Chat on <a href="https://api.whatsapp.com/send?phone=2348132878945&text=Welcome To SMS UR Way" target="_blank">Whatsapp</a>.
                            </p>
                            <p class="pt-3">
                                Please enter the amount to fund and click the <b>PAY NOW</b>  button.
                            </p>
                            <form method="POST" action="{{ route('pay_dpo') }}">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="Amount">Enter Amount</label>
                                    </div>
                                    <div class="form-group col-md-2">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">₦</span>
                                            </div>
                                            <input type="number" class="form-control" name="amount" placeholder="500.00" min="500" step="0.01" aria-label="Amount (to the nearest Naira)">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <button type="submit" id="start-payment-button">Pay Now</button>
                                    </div>
                                </div>
                            </form>
                            <p class="pt-3">
                                <span style="color:#0e70cb">NOTE: Every successful payment will be subject to a transaction fee, in addition to a <b>{{ $vatValue ? $vatValue*100 : '0' }}%</b> VAT deduction, before crediting.</span>
                            </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
        
        /* Firefox */
        input[type=number] {
          -moz-appearance: textfield;
        }
    </style>
    <!-- Add Manual Payment Modal -->
    <div class="modal fade" id="manual" tabindex="-1" role="dialog" aria-labelledby="gsmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="gsmModalLabel">Add Manual Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('manual') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" value="{{auth()->user()->username}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Bank Detail</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="bank" id="bank" placeholder="Enter bank" value="0003938903 GTB, InfoTek Perspective Services" readonly required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Enter Amount</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter amount" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Payment Proof</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" name="image" id="image" placeholder="Enter proof" accept="image/jpg, image/jpeg, image/png" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 align-right" >
                                <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @section('JavaScript')
        <script>
            $(document).ready(function() {
                // show the alert
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 30000);
            });
        </script>
    @endsection
@endsection