
@extends('layouts.ProtectedPagesTemplateContent')
@section('content')
    <div class="bulletin-container" ng-controller="bulletinController">
        <div class="content-header">
            <div class="pull-left">
                <h4 class="header-title"><strong>Bulletins</strong></h4>
            </div>
            <div class="pull-right bulletins">
                <a href="#" class="pull-right btn btn-custom" ng-click="resetForm(frmBulletin, true)" >Create New Bulletin</a>
                <form class="navbar-form navbar-right" role="form" name="frmBulletinSearch" novalidate="" ng-submit="searchBulletin()">
                    <div class="input-group" id="adv-search">
                        <div class="input-group-btn">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label">Keyword:</label>
                                    <input type="text" id="bulletin" name="bulletin" class="form-control" placeholder="Keyword" ng-model="keyword.keywords">
                                </div>
                                <div class="form-group">
                                    <label for="service_id">Service:</label>
                                        <select class="form-control" id="services" name="servicesKeyword" ng-model="keyword.services" ng-value="">
                                            <option value="" selected="selected">All</option>
                                            <option ng-repeat="service in services" ng-value="service.id"><%service.name%></option>
                                        </select>
                                    </div>
                                <input type="submit" class="btn btn-primary" value="Search">
                            </form>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <h2 class="side-header">Bulletin List</h2>
                <div class="bulletin-list">
                    <ul>
                        <li dir-paginate="bulletin in bulletins|itemsPerPage:10" pagination-id="bulletin">
                            <a href="#" ng-click="toggle('edit', bulletin.id)"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <%bulletin.title%> (<%bulletin.date_created%>)</a>
                        </li>

                    </ul>
                    <dir-pagination-controls
                        pagination-id="bulletin"
                        max-size="3"
                        direction-links="true"
                        boundary-links="true" >
                    </dir-pagination-controls>
                </div>
            </div>
            <div class="col-md-9">
                <h2 class="side-header">Bulletin Information</h2>
                <div class="bulletin-info">
                    <form name="frmBulletin" class="form-horizontal" ng-submit="save(bulletinInfo.id)">
                    <%bulletinInfo.id%>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Title</label>
                            <div class="col-md-6">
                                    <input type="text" id="b-info-title" name="b-info-title" class="form-control" value="" ng-model="bulletinInfo.title">
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Expires</label>
                            <div class="col-md-6">
                                    <div class="col-xs-3 pull-right">
                                        <a href="" class="btn btn-default" ng-click="bulletinInfo.expires = '0000-00-00 00:00:00';">
                                                <i class="glyphicon glyphicon-remove"></i>
                                        </a>
                                    </div>
                                    <div class='input-group col-xs-9'>
                                        <input type='text' id="b-info-expires" name="b-info-expires" class="form-control" ng-model="bulletinInfo.expires" date-time-picker="{ format: 'YYYY-MM-DD HH:mm:ss'}"/>
                                        <label class="input-group-addon" for="b-info-expires">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                    <small class="text-muted">set to “0000-00-00 00:00:00” to disable expiration</small>
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label" for="comment">Message</label>
                            <div class="col-md-6">
                                <textarea class="form-control" rows="5" id="comment" ng-model="bulletinInfo.message"></textarea>
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Recipients:</label>
                             {{-- multi-select-option="{includeSelectAllOption: true,enableFiltering: true } --}}
                            <div class="col-md-6">
                                <select id="recipients" multiple="multiple" ng-multiple="true" class="form-control" ng-model="bulletinInfo.recipients" name="recipients[]" ng-options="user.name group by user.role for user in userlist track by user.id" ng-selected="bulletinInfo.recipients">
                                </select>
                                <%bulletinInfo.recipients%>
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Service Group:</label>
                            <div class="col-md-6">
                                <select id="service-group" multiple="multiple" ng-multiple="true" ng-model="bulletinInfo.services" name="services[]" 
                                ng-options="service.name for service in services track by service.id" ng-selected="bulletinInfo.services">
                                </select>
                                <%bulletinInfo.services%>
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Select File</label>
                            <div class="col-md-6">
                                <input id="input-1" type="file" class="file">
                            </div>
                            <div class="uploaded_img">
                                    
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                        <button type="submit" class="btn btn-danger">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!--Script-->
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
    <script src="{{ URL::asset('js/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/moment.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-multiselect.js') }}"></script>
    
    <script src="{{ URL::asset('js/toastr.js') }}"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <script src="{{ URL::asset('js/color-picker.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/color-picker.min.css') }}" />
    <script src="{{ URL::asset('js/angular-toastr.tpls.js')}}"></script>
    <link rel="stylesheet" href="{{ URL::asset('css/angular-toastr.css')}}" />
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-multiselect.css')}}" />
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-datetimepicker.min.css')}}" />

<!-- AngularJS Application Scripts -->
    <script src="{{ URL::asset('js/angular/angular.min.js')}}"></script>
    <script src="{{ URL::asset('bower_components/angular-sanitize/angular-sanitize.min.js')}}"></script>
    <script src="{{ URL::asset('bower_components/angular-ui-select/dist/select.js')}}"></script>
    <script src="{{ URL::asset('js/angular/app/app.js') }}"></script>
    <script src="{{ URL::asset('js/angular/directives/dirPagination.js') }}"></script>
    <script src="{{ URL::asset('js/angular/directives/multiSelect.js') }}"></script>
    <script src="{{ URL::asset('js/angular/directives/dateTimePicker.js') }}"></script>
    <script src="{{ URL::asset('js/angular/factory/transformRequestAsFormPost.js') }}"></script>

    <script src="{{ URL::asset('js/angular/controllers/bulletin.js') }}"></script>
    <script src="{{ URL::asset('js/angular/app/bulletin.js') }}"></script>

</html>
@stop