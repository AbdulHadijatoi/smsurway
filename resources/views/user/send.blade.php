@extends('layouts.TableHeader')
@section('title','Send SMS Campaign')
@section('content')
    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Send SMS Campaign</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">Send SMS Campaign</li>
                        
                    </ul>
                    <!-- <a href="javascript:void(0);" class="btn btn-sm btn-primary" title="">Create New</a> -->
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2>Compose SMS</h2>
                        </div>                     
                        <div class="body">
                            <span for="Note"><b class="text-danger">Note:</b> Please note that SMS sent after 8pm will be delivered 8am the following day and Fields with sign(*) are required</span>
                            <div class="row mt-3">
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
                                     @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                @foreach ($errors->all() as $error)
                                                    <li>
                                                        {{ $error }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form method="post" action="{{ route('sendSMS') }}" id="sendForm">
                                        {{-- @csrf --}}
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="inputAddress">FROM: *</label>
                                            </div>
                                            <div class="form-group col-md-7">
                                                <select class="form-control" id="from" name="from" required>
                                                    <option disabled selected>Please select approved IDs</option>
                                                    @foreach ($senderIds as $channel)
                                                        @if ($channel['name'] != 'INFOTEKPS ')
                                                            <option value="{{ $channel['id'] }}">{{ $channel['name'] }}</option>    
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="inputAddress">CHOOSE CONTACT: *</label>
                                            </div>
                                            <div class="form-group col-md-7">
                                                {{-- <label class="fancy-radio"><input name="contact" value="contact_type" type="radio" checked required><span><i></i>Type Contact</span></label>
                                                <label class="fancy-radio"><input name="contact" value="contact_group" type="radio"><span><i></i>Groups</span></label>
                                                <label class="fancy-radio"><input name="contact" value="contact_upload" type="radio"><span><i></i>Upload</span></label> --}}
                                                <input type="radio" onchange="hideB(this)" name="contact" value="contact_type" checked required>Type Contact |
                                                <input type="radio" onchange="hideA(this)" name="contact" value="contact_group">Groups
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="exampleFormControlTextarea1">DESTINATION {TYPE CONTACTS}:*</label>
                                            </div>
                                            <div class="form-group col-md-7" id="A">
                                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name='to' placeholder="234803300......,                                                      
234803300......,                                                      
234803300......" @if(old('to')!=null) autocomplete="to" @endif></textarea>
<!--2349039000021,2348094947473,2348132878945-->
<sub for="Note text-primary"><b class="text-danger">Note:</b> Must use comma(,) as separator after every contact.</sub>     
                                            </div>
                                            <div class="form-group col-md-7" id="B" style="display:none">
                                                <select class="form-control text-capitalize" for="FormControlTextarea1" name='group' aria-label="Default select example" required>
                                                    <option selected value="none">--- Select A Group ---</option>
                                                    @if((old('group')!=null))
                                                        <option value="{{old('group')}}" selected>{{old('group')}}</option>
                                                    @endif
                                                    @foreach($address as $u)
                                                        <option value="{{ $u->name }}">{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="exampleFormControlTextarea1">SMS MESSAGE:*</label>
                                            </div>
                                            <div class="form-group col-md-7">
                                                <textarea class="form-control" rows="3" name='msg' id="msg"  onkeyup="countChar(this);loadResults(this);"  placeholder="Compose SMS Messages ..." maxlength="950" required @if(old('msg')!=null) autocomplete="msg" @endif></textarea>
                                                {{-- <textarea class="form-control" rows="3" name='msg' id="msg"  onkeyup="countChar(this);loadResults(this);" placeholder="Compose SMS Messages ..." maxlength="950" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" required @if(old('msg')!=null) autocomplete="msg" @endif></textarea> --}}
                                                <sub for="Note"><b class="text-danger">Note: </b> Maximum Allowed Characters are 950.</sub>
                                                <p class="text-danger" id="msgError"></p>
                                                <p class="count">
                                                    [
                                                    <span id="charNum">
                                                        0   
                                                    </span>
                                                    ] 
                                                    
                                                    Charaters, i.e
                                                    [<span id="sms-page">
                                                        1
                                                    </span>]
                                                    SMS Page
                                                </p>
                                            </div>
                                        </div>
                                        {{-- <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="exampleFormControlTextarea1">SCHEDULE DATE/TIME:*</label>
                                            </div>
                                            <div class="form-group col-md-3">

                                                <input type="datetime-local" class="form-control" id="inputAddress" name="sendtime" min="@php echo  date("Y-m-d H:i:s")."T05:00"; @endphp"  placeholder="Pick a Date">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="exampleFormControlTextarea1">MESSAGE TYPE:*</label>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <select class="form-select" for="FormControlTextarea1" name='type' aria-label="Default select example" required>
                                                    <option value=""  disabled>Select Type</option>
                                                    <option value="0" selected>Normal SMS</option>
                                                    <option value="1">Flash SMS</option>
                                                    <option value="2">Unicode SMS</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="msg_price" value="0">
                                        </div> --}}
                                        <div class="form-row">
                                            <div class="form-group col-7">
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
        function validateForm() {
            stringLength = document.getElementById('from').value.length;
            if (stringLength > 11) {
                // alert('something happened!');
                document.getElementById('fromErr').innerText = "Maximum allowed characters are 11";
            } else {
                document.getElementById('fromErr').innerText = "";
            }
            
        }
        // Filter Keyword
        function loadResults(val){
            var msg=$('#msg').val();
            $("#msgError").html("");
            $.ajax({
                method: "POST",
                url: "{{ route('filterKeyword') }}",
                data: {msg:msg,'_token':"{{ csrf_token() }}"},
                success: function (data) {
                    
                    if(data!='OK'){
                        var json = JSON.parse(JSON.stringify(data)); // here data is your response
                        // alert(json.length);
                        var response='';
                        if(json.length>0){
                            response +='<b>Prohibited Keywords: </b>';
                            for (var key in json) {
                                console.log(json[key].keyword);// other also in the same way.
                                response +=json[key].keyword+", ";
                            }
                            $("#msgError").html(response);
                            $("#sendBtn").attr("disabled", true);
                            $("#sendBtn").css("cursor", "not-allowed");
                        }
                    }
                    else{
                        $("#msgError").html('');
                        $("#sendBtn").removeAttr("disabled");
                        $("#sendBtn").css("cursor", "pointer");
                    }
                },
                error: function (request, status, error){
                    console.log(status);
                    console.log(error);
                }         
            });         
        }
        $("#sendForm").submit(function (e) {
                $("#sendBtn").attr("disabled", true);
                return true;
        });
        function hideA(x) {
            if (x.checked) {
                document.getElementById("A").style.display = "none";
                document.getElementById("B").style.display = "block";
            }
        }

        function hideB(x) {
            if (x.checked) {
                document.getElementById("B").style.display = "none";
                document.getElementById("A").style.display = "block";
            }
        }

        // JS for count char typed in msg.
        function countChar(val) {
            var len = val.value.length;
            // alert(len);
            $('#charNum').text(len);
            if (len > 950) {
                
                alert("You exceed maximum limited");
                $("#sendBtn").attr("disabled", true);
                $("#sendBtn").css("cursor", "not-allowed");
            }
            else if (len >= 626 && len <= 950) {
                $('#sms-page').text(6);
                $("#sendBtn").removeAttr("disabled");
                $("#sendBtn").css("cursor", "pointer");
            }
            else if (len >= 506 && len <= 625) {
                $('#sms-page').text(5);
                $("#sendBtn").removeAttr("disabled");
                $("#sendBtn").css("cursor", "pointer");
            } 
            else if (len >= 401 && len <= 505) {
                $('#sms-page').text(4);
                $("#sendBtn").removeAttr("disabled");
                $("#sendBtn").css("cursor", "pointer");

            } 
            else if (len >= 291 && len <= 400) {
                $('#sms-page').text(3);
                $("#sendBtn").removeAttr("disabled");
                $("#sendBtn").css("cursor", "pointer");
            } 
            else if (len >= 161 && len <= 280) {
                $('#sms-page').text(2);
                $("#sendBtn").removeAttr("disabled");
                $("#sendBtn").css("cursor", "pointer");
            }
            else if (len >= 1 && len <= 160) {
                $('#sms-page').text(1);
                $("#sendBtn").removeAttr("disabled");
                $("#sendBtn").css("cursor", "pointer");
            }
            else{
                $('#sms-page').text(1);
                $('#charNum').text(0);
            }
        };
    </script>
@endsection