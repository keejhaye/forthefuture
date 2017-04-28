
@extends('layouts.ProtectedPagesTemplateContent')
@section('content')

<link href="{{ URL::asset('css/bootstrap-dialog.min.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">

    #unmapOperToServ tbody tr.odd.selected, 
    #unmapOperToServ tbody tr.even.selected {
        background-color: #acbad4;
    }
    #operatorUnmapServ tbody tr.odd.selected, 
    #operatorUnmapServ tbody tr.even.selected {
        background-color: #acbad4;
    }

    .dataTables_paginate {
        background: #f2f2f2;
        padding: 20px;
        margin-bottom: 20px;
    }

    .paginate_button {
        display: inline-block;
        padding: 0px 9px;
        margin-right: 4px;
        border-radius: 3px;
        border: solid 1px #c0c0c0;
        background: #e9e9e9;
        box-shadow: inset 0px 1px 0px rgba(255,255,255, .8), 0px 1px 3px rgba(0,0,0, .1);
        font-size: .875em;
        font-weight: bold;
        text-decoration: none;
        color: #717171;
        text-shadow: 0px 1px 0px rgba(255,255,255, 1);
    }

    .paginate_button:hover {
        background: #fefefe;
        background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FEFEFE), to(#f0f0f0));
        background: -moz-linear-gradient(0% 0% 270deg,#FEFEFE, #f0f0f0);
    }

    .paginate_button.current {
        border: none;
        background: #616161;
        box-shadow: inset 0px 0px 8px rgba(0,0,0, .5), 0px 1px 0px rgba(255,255,255, .8);
        color: #f0f0f0;
        text-shadow: 0px 0px 3px rgba(0,0,0, .5);
    }

    /********************************************************************/
    /*** PANEL DEFAULT ***/
    .with-nav-tabs.panel-default .nav-tabs > li > a,
    .with-nav-tabs.panel-default .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li > a:focus {
        color: #777;
    }
    .with-nav-tabs.panel-default .nav-tabs > .open > a,
    .with-nav-tabs.panel-default .nav-tabs > .open > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > .open > a:focus,
    .with-nav-tabs.panel-default .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li > a:focus {
        color: #777;
        background-color: #ddd;
        border-color: transparent;
    }
    .with-nav-tabs.panel-default .nav-tabs > li.active > a,
    .with-nav-tabs.panel-default .nav-tabs > li.active > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li.active > a:focus {
        color: #555;
        background-color: #fff;
        border-color: #ddd;
        border-bottom-color: transparent;
    }
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu {
        background-color: #f5f5f5;
        border-color: #ddd;
    }
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a {
        color: #777;   
    }
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
        background-color: #ddd;
    }
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a,
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
        color: #fff;
        background-color: #555;
    }
    .panel-heading{
        border: none !important;
        box-shadow: none !important;
    }
</style>
<meta http-equiv="Pragma" content="no-cache">
<div style="width: 80%; margin-left : auto; margin-right : auto;">
    <div class="container">

        <div class="content-header">
            <div class="pull-left">
                <h4 class="header-title"><strong>Batch Assign</strong></h4>
            </div>
            <div class="clearfix"></div>
        </div>

            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab">Map Operators to Services</a></li>
                            <li><a href="#tab2default" data-toggle="tab">Service Mapping</a></li>
                            <li><a href="#tab3default" data-toggle="tab">Operator Mapping</a></li>
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                                
                    <form id="frm-operatorToservicesMap" method="POST" action="{{ URL::to('/panel/saveUserService') }}">
                        <div class="row">
                            <div class="col-md-5">
                                <h2 class="side-header">Operator List</h2>
                                    <table id="mapOperToServ" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                       <thead>
                                          <tr>
                                             <th><input type="checkbox" name="select_all" value="1" id="mapOperToServ-select-all"></th>
                                             <th>Name</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                            @foreach($users as $user)
                                           <tr>
                                               <td><input type="checkbox" name="mapOperToServId[]" value="{{ $user->id }}"></td>
                                               <td>{{ $user->username }}</td>
                                           </tr>
                                           @endforeach
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                             <th></th>
                                             <th>Name</th>
                                          </tr>
                                       </tfoot>
                                    </table>
                            </div>
                            <div class="col-md-1">
                                   <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left">Map<span class="glyphicon glyphicon-chevron-right"></button>
                            </div>
                            <div class="col-md-5">
                                <h2 class="side-header">Service List</h2>
                                <table id="mapServToOper" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                       <thead>
                                          <tr>
                                             <th><input type="checkbox" name="select_all" value="1" id="mapServToOper-select-all"></th>
                                             <th>Name</th>
                                             <th>Code</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                            @foreach($services as $service)
                                              <tr>
                                                 <td><input type="checkbox" name="mapServToOperId[]" value="{{ $service->id }}"></td>
                                                 <td>{{ $service->name }}</td>
                                                 <td>{{ $service->code }}</td>
                                              </tr>
                                           @endforeach
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                             <th></th>
                                             <th>Name</th>
                                             <th>Code</th>
                                          </tr>
                                       </tfoot>
                                    </table>
                            </div>
                        </div>
                    </form>

                        </div>
                        <div class="tab-pane fade" id="tab2default">

                    <form id="frm-serviceMappingPanel" method="POST" action="{{ URL::to('/panel/deleteUserService/0') }}">
                        <div class="row">
                            <div class="col-md-5">
                                <h2 class="side-header">Service List</h2>
                                    <table id="unmapOperToServ" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                       <thead>
                                          <tr>
                                             <th>Name</th>
                                             <th>Code</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                            @foreach($services as $service)
                                           <tr id-data="{{ $service->id }}">
                                                 <td><input type="checkbox" name="unmapServId[]" id="unmapServId{{ $service->id }}" value="{{ $service->id }}" style="display: none;">{{ $service->name }}</td>
                                                 <td>{{ $service->code }}</td>
                                           </tr>
                                           @endforeach
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                             <th>Name</th>
                                             <th>Code</th>
                                          </tr>
                                       </tfoot>
                                    </table>
                            </div>
                            <div class="col-md-1">
                                   <button type="submit" class="btn btn-primary">Unmap<span class="glyphicon glyphicon-chevron-right"></button>
                            </div>
                            <div class="col-md-5">
                                <h2 class="side-header">Operator List</h2>
                                    <table id="unmapServToOper" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                       <thead>
                                          <tr>
                                            <th width="10%"></th>
                                             <th width="90%">Name</th>
                                          </tr>
                                       </thead>
                                       <tbody></tbody>
                                       <tfoot>
                                          <tr>
                                            <th></th>
                                             <th>Name</th>
                                          </tr>
                                       </tfoot>
                                    </table>
                                    <div id="appendListOfOperator"></div>
                            </div>
                        </div>
                    </form>
                        </div>
                        <div class="tab-pane fade" id="tab3default">
                            
                    <form id="frm-operatorMappingPanel" method="POST" action="{{ URL::to('/panel/deleteUserService/1') }}">
                        <div class="row">
                            <div class="col-md-5">
                                <h2 class="side-header">Operator List</h2>
                                    <table id="operatorUnmapServ" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                       <thead>
                                          <tr>
                                             <th>Name</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                            @foreach($users as $user)
                                           <tr id-data="{{ $user->id }}">
                                               <td><input type="checkbox" name="unmapServId[]" id="unmapServId{{ $user->id }}" value="{{ $user->id }}" style="display: none;">{{ $user->username }}</td>
                                           </tr>
                                           @endforeach
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                             <th>Name</th>
                                          </tr>
                                       </tfoot>
                                    </table>
                            </div>
                            <div class="col-md-1">
                                   <button type="submit" class="btn btn-primary">Unmap<span class="glyphicon glyphicon-chevron-right"></button>
                            </div>
                            <div class="col-md-5">
                                <h2 class="side-header">Service List</h2>
                                    <table id="tableForService" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                       <thead>
                                          <tr>
                                            <th width="10%"></th>
                                             <th width="50%">Name</th>
                                             <th width="40%">Code</th>
                                          </tr>
                                       </thead>
                                       <tbody></tbody>
                                       <tfoot>
                                          <tr>
                                            <th></th>
                                             <th>Name</th>
                                             <th>Code</th>
                                          </tr>
                                       </tfoot>
                                    </table>
                                    <div id="appendListOfOperator"></div>
                            </div>
                        </div>
                    </form>

                        </div>
                    </div>
                </div>
            </div>



    </div>
</div>















               
                    <!--Script-->
                    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
                    <script src="{{ URL::asset('js/jquery-2.1.1.min.js') }}"></script>
                    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
                    <script src="{{ URL::asset('js/toastr.js') }}"></script>
                    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
              
                    <script src="{{ URL::asset('js/color-picker.min.js') }}"></script>
                    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/color-picker.min.css') }}" />
                    <script src="{{ URL::asset('js/angular-toastr.tpls.js')}}"></script>
                    <link rel="stylesheet" href="{{ URL::asset('css/angular-toastr.css')}}" />
                    <!-- AngularJS Application Scripts -->
                    <script src="{{ URL::asset('js/angular/angular.min.js')}}"></script>
                    <script src="{{ URL::asset('bower_components/angular-sanitize/angular-sanitize.min.js')}}"></script>
                    <script src="{{ URL::asset('bower_components/angular-ui-select/dist/select.js')}}"></script>
                    <script src="{{ URL::asset('js/angular/app/app.js') }}"></script>
                    <script src="{{ URL::asset('js/angular/directives/dirPagination.js') }}"></script>
                    <script src="{{ URL::asset('js/angular/factory/transformRequestAsFormPost.js') }}"></script>

                    <script src="{{ URL::asset('js/angular/controllers/services.js') }}"></script>
                    <script src="{{ URL::asset('js/angular/app/services.js') }}"></script>
                    <script src="{{ URL::asset('js/jquery.dataTables.min.js') }}"></script>
                    <script src="{{ URL::asset('js/bootstrap-dialog.min.js') }}"></script>
                    
                    @if (Session::has('successBatchAssign'))
                        <script type="text/javascript">
                            BootstrapDialog.show({
                                title: 'Success',
                                type: 'type-success',
                                message: '{{Session::get("successBatchAssign")}}',
                                buttons: [{
                                    label: 'Ok',
                                    action: function(dialog) {
                                        dialog.close();
                                    }
                                }]
                            });
                        </script>
                    @endif        
                    @if (Session::has('deletedBatchAssign'))
                        <script type="text/javascript">
                            BootstrapDialog.show({
                                title: 'Delete',
                                type: 'type-danger',
                                message: '{{Session::get("deletedBatchAssign")}}',
                                buttons: [{
                                    label: 'Ok',
                                    action: function(dialog) {
                                        dialog.close();
                                    }
                                }]
                            });
                        </script>
                    @endif

                    <script type="text/javascript">




                        $(document).on('click', '#umapThisOperator', function(e) {
                            if($(this).is(":not(:checked)")){
                                $("#thisOperator"+$(this).val()+"").prop("checked", true);
                            }else if($(this).is(":checked")){
                                $("#thisOperator"+$(this).val()+"").prop("checked", false);
                            }
                        });

                        $(document).ready(function (){

                            var table1 = $('#mapOperToServ').DataTable();
                            var table2 = $('#mapServToOper').DataTable();
                            var table3 = $('#unmapOperToServ').DataTable();
                            var table4 = $('#operatorUnmapServ').DataTable();

                            //----------------------THIS IS TO FIX DATATABLE DOM CHECKBOX HIDDEN ---------------------
                            $("#frm-operatorToservicesMap").on('submit', function(e){
                               var $form = $(this);

                               // Iterate over all checkboxes in the table
                               table1.$('input[type="checkbox"]').each(function(){
                                  // If checkbox doesn't exist in DOM
                                  if(!$.contains(document, this)){
                                     // If checkbox is checked
                                     if(this.checked){
                                        // Create a hidden element 
                                        $form.append(
                                           $('<input>')
                                              .attr('type', 'hidden')
                                              .attr('name', this.name)
                                              .val(this.value)
                                        );
                                     }
                                  } 
                               }); 

                               // Iterate over all checkboxes in the table
                               table2.$('input[type="checkbox"]').each(function(){
                                  // If checkbox doesn't exist in DOM
                                  if(!$.contains(document, this)){
                                     // If checkbox is checked
                                     if(this.checked){
                                        // Create a hidden element 
                                        $form.append(
                                           $('<input>')
                                              .attr('type', 'hidden')
                                              .attr('name', this.name)
                                              .val(this.value)
                                        );
                                     }
                                  } 
                               }); 

                            });


                            $("#frm-serviceMappingPanel").on('submit', function(e){
                               var $form = $(this);

                                var table5 = $('#unmapServToOper').DataTable();
                               // Iterate over all checkboxes in the table
                               table5.$('input[type="checkbox"]').each(function(){
                                  // If checkbox doesn't exist in DOM
                                  if(!$.contains(document, this)){
                                     // If checkbox is checked
                                     if(this.checked){
                                        // Create a hidden element 
                                        $form.append(
                                           $('<input>')
                                              .attr('type', 'hidden')
                                              .attr('name', this.name)
                                              .val(this.value)
                                        );
                                     }
                                  } 
                               }); 

                            });

                            $("#frm-operatorMappingPanel").on('submit', function(e){
                               var $form = $(this);

                                var table6 = $('#tableForService').DataTable();
                               // Iterate over all checkboxes in the table
                               table6.$('input[type="checkbox"]').each(function(){
                                  // If checkbox doesn't exist in DOM
                                  if(!$.contains(document, this)){
                                     // If checkbox is checked
                                     if(this.checked){
                                        // Create a hidden element 
                                        $form.append(
                                           $('<input>')
                                              .attr('type', 'hidden')
                                              .attr('name', this.name)
                                              .val(this.value)
                                        );
                                     }
                                  } 
                               }); 

                            });
                            //----------------------END THIS IS TO FIX DATATABLE DOM CHECKBOX HIDDEN ---------------------


                            $('#operatorUnmapServ tbody').on( 'click', 'tr', function () {
                                selectTrBodyFromTable($(this), table4);
                                $.ajax({
                                    type: "GET",
                                    url: "{{ url('/panel/getAllServById') }}/"+$(this).attr('id-data'),
                                    dataType: "json",
                                    success: function(res){
                                        $("#tableForService tbody").html("");
                                        for(var i=0; i<res.length; i++){
                                         $("#tableForService tbody").append(" \
                                            <tr> \
                                                <td> \
                                                    <input type='checkbox' id='thisOperator"+res[i].id+"' name='unmapServToOperId[]' value="+res[i].service_id+"  > \
                                                </td> \
                                                <td>"+res[i].name+"</td> \
                                                <td>"+res[i].code+"</td> \
                                            </tr> \
                                            ");
                                        }

                                        $('#tableForService').DataTable();
                                    }
                                });
                            });


                            $('#unmapOperToServ tbody').on( 'click', 'tr', function () {
                                selectTrBodyFromTable($(this), table3);      
                                $.ajax({
                                    type: "GET",
                                    url: "{{ url('/panel/getAllOperatorById') }}/"+$(this).attr('id-data'),
                                    dataType: "json",
                                    success: function(res){
                                        $("#unmapServToOper tbody").html("");
                                        // console.log(res.length);
                                        // console.log(JSON.stringify({ 'data': res}));
                                        for(var i=0; i<res.length; i++){
                                         $("#unmapServToOper tbody").append(" \
                                            <tr> \
                                                <td> \
                                                    <input type='checkbox' id='thisOperator"+res[i].id+"' name='unmapServToOperId[]' value="+res[i].user_id+"  > \
                                                </td> \
                                                <td>"+res[i].username+"</td> \
                                            </tr> \
                                            ");
                                        }

                                        $('#unmapServToOper').DataTable();
                                    }
                                });
                            });

                            function selectTrBodyFromTable(thisTableElem, thisTableData){
                                if ( $(thisTableElem).hasClass('selected') ) {
                                    // $(this).removeClass('selected');
                                }
                                else {
                                    thisTableData.$('tr.selected').removeClass('selected');
                                    thisTableData.$('input:checkbox').removeAttr('checked');
                                    $("#unmapServId"+$(thisTableElem).attr('id-data')+"");
                                    $(thisTableElem).addClass('selected');
                                    $("#unmapServId"+$(thisTableElem).attr('id-data')+"").prop("checked", true);
                                }     
                            }
                             


                            $('#mapOperToServ-select-all').on('click', function(){
                                var rows = table1.rows({ 'search': 'applied' }).nodes();
                                $('input[type="checkbox"]', rows).prop('checked', this.checked);
                            });

                            $('#mapOperToServ tbody').on('change', 'input[type="checkbox"]', function(){
                                if(!this.checked){
                                    var el = $('#mapOperToServ-select-all').get(0);
                                    if(el && el.checked && ('indeterminate' in el)){
                                        el.indeterminate = true;
                                    }
                                }
                            });



                            $('#mapServToOper-select-all').on('click', function(){
                                var rows = table2.rows({ 'search': 'applied' }).nodes();
                                $('input[type="checkbox"]', rows).prop('checked', this.checked);
                            });

                            $('#mapServToOper tbody').on('change', 'input[type="checkbox"]', function(){
                                if(!this.checked){
                                    var el = $('#mapServToOper-select-all').get(0);
                                    if(el && el.checked && ('indeterminate' in el)){
                                        el.indeterminate = true;
                                    }
                                }
                            });


                        });

                    </script>
                    </html>
@stop