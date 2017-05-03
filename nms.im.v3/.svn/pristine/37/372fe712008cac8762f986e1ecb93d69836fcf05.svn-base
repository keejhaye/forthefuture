    @section('css')
    <link rel="stylesheet" href="<?php echo URL::asset('css/dataTables.bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?php echo URL::asset('css/bootstrap-datetimepicker.min.css')?>">
    <style type="text/css">
        .loading {
            background-color: #ffffff;
            bottom: 0;
            left: 0;
            opacity: 0.82;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 999;
        }
        .loading-img {
            left: 50%;
            position: fixed;
            top: 50%;
        }
    </style>
    @stop

    @extends('layouts.ProtectedPagesTemplateContent')
    @section('content')

    <div>
        <div class="users-container" ng-controller="profileController">
            <div class="content-header">
                <div class="pull-left">
                    <h4 class="header-title"><strong>Profile</strong></h4>
                </div>
               
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">Information</div>
                        <div class="panel-body">
                            <label>Username: <%user.username%></label> <br/>
                            <label>Rank: <%user.description%></label>
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">Services</div>
                        <div class="panel-body">
                            <div ng-show="services.total == 0">
                                <p>No Assigned Service</p>
                            </div>
                            <div class="tagcloud2" ng-repeat="service in services">
                                <p id="item"><%service.name%></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-xs-12" ng-init="tab=1">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#user-info" ng-click="" aria-controls="user-info" role="tab" data-toggle="tab">Update Information</a></li>
                                <li role="presentation" class=""><a href="#user-message-history" ng-click="getMessageHistory(selectedOption)" aria-controls="user-message-history" role="tab" data-toggle="tab">Message History</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="user-info" ng-show="tab == 1">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="bulletin-info">
                                                <form name="frmUsers" class="form-horizontal" novalidate="">
                                                    <fieldset>
                                                        <legend>Company Profile</legend>
                                                        <div class="form-group margin-bottom5">
                                                            <label class="col-md-2 control-label">Username</label>
                                                            <div class="col-md-6">
                                                                <input type="text" ng-disabled="user.role_id == 7 || user.role_id == 5" id="username" name="username" class="form-control" ng-model="user.username" ng-required="true" value="<%username%>">
                                                                 <span class="help-inline" 
                                                                ng-show="frmUsers.username.$invalid && frmUsers.username.$touched">Username field is required</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group margin-bottom5">
                                                            <label class="col-md-2 control-label">Password</label>
                                                            <div class="col-md-6">
                                                                <input ng-disabled="user.role_id == 7 || user.role_id == 5" type="password" id="password" name="password" ng-model="user.password" class="form-control" ng-required="true" >
                                                            </div>
                                                        </div>
                                                       <div class="form-group margin-bottom5">
                                                            <label class="col-md-2 control-label"> Confirm Password</label>
                                                            <div class="col-md-6">
                                                         <input ng-disabled="user.role_id == 7 || user.role_id == 5" type="password" id="password_confirm" name="password_confirm" ng-model='user.password_confirm' class="form-control" value="<%password%>">
    
                                                         </div>   
                                                        </div>
                                            
                                                    
                                                    </fieldset>

                                                    <fieldset>
                                                        <legend>Personal Infomation</legend>
                                                        <div class="form-group margin-bottom5">
                                                            <label class="col-md-2 control-label">First Name</label>
                                                            <div class="col-md-6">
                                                                <input type="text" id="firstname" name="firstname" class="form-control" ng-model="user.firstname" ng-required ="true" value="<%firstname%>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group margin-bottom5">
                                                            <label class="col-md-2 control-label">Last Name</label>
                                                            <div class="col-md-6">
                                                                <input type="text" id="lastname" name="lastname" class="form-control" ng-model="user.lastname" ng-required ="true" value="<%lastname%>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group margin-bottom5">
                                                            <label class="col-md-2 control-label"> Email Address:</label>
                                                            <div class="col-md-6">
                                                                <input type="text" id="email" name="email" class="form-control" ng-model="user.email" ng-required ="true" value="<%email%>">
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <div class="form-group margin-bottom5">
                                                        <button type="submit" ng-click="save()" class="btn btn-danger">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="user-message-history">
                                    <div class="loading" ng-if="fetchingHstry == true">
                                        <img src="../img/Preloader_11.gif" class="loading-img">
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="bulletin-info">
                                                <div class="row filter-opts form-horizontal">
                                                    <div class="col-md-4">
                                                        <div class="form-group margin-bottom5">
                                                            <label class="col-md-3 control-label">Preset</label>
                                                            <div class="col-md-9">
                                                                <select ng-options="opt.name for opt in presetOptions track by opt.val" ng-model="selectedOption" ng-change="getMessageHistory(selectedOption)" id="preset" name="preset" class="form-control">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4"><!--just for spacing --></div>
                                                    <div class="col-md-4" ng-if="hstry.total > 0">
                                                        <button ng-click="exportUserMH()" type="button" class="btn btn-primary btn-block">Export CSV</button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <table id="prof-msg-hstry" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                            <thead>
                                                                <th>CDID</th>
                                                                <th>Service</th>
                                                                <th>Persona</th>
                                                                <th>Subscriber</th>
                                                                <th>Inbound</th>
                                                                <th>Outbound</th>
                                                            </thead>
                                                            <tbody>
                                                            <tr class="info" ng-if="hstry.total == 0">
                                                                <td class="text-center" colspan="6"><strong>no data found</strong></td>
                                                            </tr>
                                                            <tr ng-if="hstry.total > 0" ng-repeat="row in hstry.record">
                                                                <td><%row.conversation_duration_id%></td>
                                                                <td><%row.service_name%></td>
                                                                <td><%row.persona_name%></td>
                                                                <td><%row.subscriber_name%></td>
                                                                <td><span class="tbl-date">(<%row.last_inbound_time | amDateFormat: 'MMM-DD-YYYY hh:mm a'%>)</span><br><%row.last_inbound_message%></td>
                                                                <td><span class="tbl-date">(<%row.last_outbound_time | amDateFormat: 'MMM-DD-YYYY hh:mm a'%>)</span><br><%row.last_outbound_message%></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

    </div>
@stop

@section('script')
    <!-- Scripts -->
    <script src="<?php echo URL::asset('js/jquery-2.1.1.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/toastr.js') ?>"></script>
     <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <script src="<?php echo URL::asset('js/color-picker.min.js') ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo URL::asset('css/color-picker.min.css') ?>" />
    <script src="<?php echo URL::asset('js/angular-toastr.tpls.js')?>"></script>
    <link rel="stylesheet" href="<?php echo URL::asset('css/angular-toastr.css')?>" />

    <!-- AngularJS Application Scripts -->
    <script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>
    <script type="text/javascript" src="{{url('/')}}/js/res/moment.min.js"></script>
    <script type="text/javascript" src="{{url('/')}}/js/angular/directives/angular-moment.min.js"></script>
    
    <script src="<?php echo URL::asset('js/angular/controllers/profile.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/app/profile.js') ?>"></script> 
@stop