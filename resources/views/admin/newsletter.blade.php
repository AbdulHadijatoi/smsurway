@extends('layouts.TableHeader')
@section('title','News Letter')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>News Letter</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">News Letter</li>
                    </ul>
                    {{-- <a href="#addPrefix" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addPrefix" title="click here for add new GSM Network">
                        Add New
                    </a> --}}
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>News Letter 
                            </h2>
                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session()->get('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    {{ session()->get('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            
                        </div>
                        <div class="body">
                            <form method="POST" action="{{ url('newsletter.store') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="inputAddress">Subject: *</label>
                                    </div>
                                    <div class="form-group col-md-7">
                                        <input type="text" class="form-control" id="subject" placeholder="Enter Subject" name='subject' required>
                                    </div>
                                </div>
                                {{-- Temp Hide --}}
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="inputAddress">CHOOSE TYPE: *</label>
                                    </div>
                                    <div class="form-group col-md-7">
                                        <input type="radio" onchange="hideB(this)" name="contact" value="type" checked required> Type Contact |
                                        <input type="radio" onchange="hideA(this)" name="contact" value="all"> All Users
                                    </div>
                                </div>
                                <div class="form-row" id="destination">
                                    <div class="form-group col-md-3">
                                        <label for="exampleFormControlTextarea1">DESTINATION {TYPE Email}:*</label>
                                    </div>
                                    <div class="form-group col-md-7" id="A">
                                            <textarea class="form-control" id="to" rows="3" name='to' placeholder="Enter Email."></textarea>
                                            <sub for="Note text-primary"><b class="text-danger">Note:</b> Must use comma(,) as separator after every email.</sub>
                                    </div>
                                    <div class="form-group col-md-7" id="B" style="display:none">
                                        <select class="form-control text-capitalize" for="FormControlTextarea1" name='group' aria-label="Default select example" >
                                            <option selected value="none">--- Select A Group ---</option>
                                            @if((old('group')!=null))
                                                <option value="{{old('group')}}" selected>{{old('group')}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-md-3">
                                        <label for="inputAddress">Message: *</label>
                                    </div>
                                    <div class="form-group col-md-7">
                                        <textarea id="ckeditor" name="msg" class="form-control" placeholder="Type Message" required>
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-8">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="reset" class="btn btn-dark mr-5" value="Reset">
                                        <button type="submit" id="sendBtn" class="btn btn-bg" data-toggle="cardloading" data-loading-effect="pulse" style="background: #F1B815;color: #fff;">Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('JavaScript')
<script>
    function hideA(x) {
        if (x.checked) {
            document.getElementById("A").style.display = "none";
            document.getElementById("B").style.display = "block";
            document.getElementById("destination").style.display = "none";
        }
    }
    function hideB(x) {
        if (x.checked) {
            
            document.getElementById("B").style.display = "none";
            document.getElementById("A").style.display = "block"; 
            document.getElementById("destination").style.display = "flex";
        }
    }
</script>
    <script src="{{ asset('assets/vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/pages/forms/editors.js')}}"></script>
@endsection