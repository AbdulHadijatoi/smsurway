@extends('layouts.TableHeader')
@section('title','Contact/Support')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Contact/Support</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Contact/Support</li>
                        
                    </ul>
                    <!-- <a href="javascript:void(0);" class="btn btn-sm btn-primary" title="">Create New</a> -->
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header" style="padding-bottom: 0px;">
                            <h2>Contact Us</h2>
                            <hr>
                            <p>
                                {{ @get_setting('contact_address')->value }}
                               {{-- <b>InfoTek</b> No 9, Mike Ejezie Street, Lekki Phase 1, Lagos --}}
                            </p>
                            <p>
                               <b>Contact and Phones:</b>    {{ @get_setting('contact_number')->value }}
                            </p>
                            <p>
                                <b>Email:</b> {{ @get_setting('contact_email')->value }}
                            </p>
                        </div>
                        <div class="body" style="padding-top: 0px;">
                            <div class="row">
                                <div class="col-10">
                                    @if(session()->has('success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('success') }}
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
                                    {{-- <form id="msgFrom" method="post"> --}}
                                    <form method="post" action="{{ route('contactUs') }}">
                                        <sub for="Note"><b>Note:</b> Fields with sign(*) are required.</sub>
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="inputAddress">Name: *</label>
                                            </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" class="form-control" id="inputAddress" placeholder="Name" name='name' value="" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="inputAddress">Email: *</label>
                                            </div>
                                            <div class="form-group col-md-7">
                                                <input type="email" class="form-control" id="inputAddress" placeholder="Email" name='email' value="" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="inputAddress">Company: *</label>
                                            </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" class="form-control" id="inputAddress" placeholder="Company" name='company' value="" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="inputAddress">Contact #: *</label>
                                            </div>
                                            <div class="form-group col-md-7">
                                                <input type="tel" class="form-control" id="inputAddress" placeholder="Contact Number" name='contact' value="" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="inputAddress">Comment: *</label>
                                            </div>
                                            <div class="form-group col-md-7">
                                                    <textarea name="comment" class="form-control" id="" cols="30" rows="5" placeholder="Your Comment" required></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group col-3">
                                            </div>
                                            <div class="form-group col-7">
                                                <input type="reset" class="btn btn-dark btn-lg mr-5" value="Reset">
                                                <button type="submit" class="btn btn-lg btn-primary">Send</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .count{
            background: orange;
            margin: 10px 0px 5px 0px;
            padding: 10px 0px 5px 5px;
            color: ghostwhite;
        }
    </style>
@endsection
@section('JavaScript')
    <script>
        
    </script>
    <!-- END WRAPPER -->
@endsection