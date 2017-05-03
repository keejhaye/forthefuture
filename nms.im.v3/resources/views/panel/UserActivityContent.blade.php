
@extends('layouts.ProtectedPagesTemplateContent')
@section('content')

@section('css')
<link rel="stylesheet" href="<?php echo URL::asset('css/dataTables.bootstrap.min.css')?>">
<link rel="stylesheet" href="<?php echo URL::asset('css/bootstrap-datetimepicker.min.css')?>">
<link href="http://demo.expertphp.in/css/jquery.ui.autocomplete.css" rel="stylesheet">
@stop
<div>
<div class="conversation-history-container" ng-controller="userActivityController">
	<div class="content-header">
		<div class="pull-left">
			<h4 class="header-title"><strong>Activity Logs</strong></h4>
		</div>
	
		<div class="clearfix"></div>
	</div>
	<div class="page-search-form">
		<h5 class="search-header">Search Filter</h5>
		<form class="form-horizontal" role="form">
			<div class="col-md-4">
			
			     <div class="form-group margin-bottom5">
                    <label class="col-md-2 control-label">User</label>
                    <div class="col-md-10">
                            <input type="text" ng-model="search.username" id="user" name="user" placeholder="User" class="form-control">
                    </div>
                </div>
                <div class="form-group margin-bottom5">
                    <label class="col-md-2 control-label">Section</label>
                    <div class="col-md-10">
                                    <select class="form-control" id="section" name="section" ng-model="search.section">
                                        <option value="">All</option>
                                        <option value="services">Services</option>
                                        <option value="users">Users</option>
                                        <option value="settings">Settings</option>
                                    </select>
                    </div>
                </div>
		
	
			</div>
			
			               <div class="col-md-4">
                   
                    <div class="form-group margin-bottom5">
                        <label class="col-md-3 control-label">Start Date</label>
                        <div class="col-md-9">
                            <div class="input-group date" id="datetimepicker6">
                                <input ng-model="start_date" id="sDate" class="form-control" type="text">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group margin-bottom5">
                        <label class="col-md-3 control-label">End Date</label>
                        <div class="col-md-9">
                            <div class="input-group date" id="datetimepicker7">
                                <input ng-model="end_date" id="eDate" class="form-control" type="text">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
			
			<div class="col-md-4">
				<div class="form-group">
					<div class="col-md-12">
						<button class="btn btn-primary pull-right"> <span>Search</span> <i class="fa fa-search"></i> </button>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</form>
	</div>
	<table id="conversation-history-datatable" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th class="text-center">User</th>
				<th class="text-center">Section</th>
				<th class="text-center">Field</th>
				<th class="text-center">Record Changed</th>
				<th class="text-center">Date</th>

			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="log in logs|filter:search|itemsPerPage:10" >
				<td class="text-center"><%log.username%></td>
				<td class="text-center"><%log.section%></td>
				<td class="text-center"><%log.changed_fields%></td>
				<td class="text-center"><%log.record_changed%></td>
				<td class="text-center"><%log.date_created%></td>
				
			</tr>
			
		</tbody>
	</table>
</div> 
</div>

<!-- AngularJS Application Scripts -->
 


@section('script')  
<script src="//cdnjs.cloudflare.com/ajax/libs/angular-moment/0.9.0/angular-moment.min.js"></script>

<script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>
    
    <script src="<?php echo URL::asset('js/angular/controllers/useractivity.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/app/useractivity.js') ?>"></script>
<script src="<?php echo URL::asset('js/jquery-2.1.1.min.js')?>"></script>
<script src="<?php echo URL::asset('js/moment.min.js')?>"></script>
<script src="<?php echo URL::asset('js/bootstrap.min.js')?>"></script>
<script src="<?php echo URL::asset('js/bootstrap-datetimepicker.min.js')?>"></script>
<script src="<?php echo URL::asset('js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo URL::asset('js/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo URL::asset('js/dataTables.responsive.min.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>
<script src="http://demo.expertphp.in/js/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
@stop