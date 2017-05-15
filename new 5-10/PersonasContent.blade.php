
@extends('layouts.ProtectedPagesTemplateContent')
@section('content')

<div>
    <div class="users-container" ng-controller="personasController">
        <div class="content-header">
            <div class="pull-left">
                <h4 class="header-title"><strong>Personas</strong></h4>
            </div>
            <div class="pull-right bulletins">
                <a href="#" class="pull-right btn btn-custom">Create New Persona</a>
                <form class="navbar-form navbar-right">
                    <div class="input-group" id="adv-search">
                        <div class="input-group-btn">
                            <form class="form-horizontal" role="form" >
                                <div class="form-group">
                                    <label class="control-label">Keyword:</label>
                                    <input type="text" id="keyword" name="keyword" ng-model="personasName" class="form-control" placeholder="Keyword" ng-change="searchPersonas(personasName, 'name')">
                                </div>  
                                <div class="form-group">
                                <input type="text" id="service" ng-model="serviceId" name="service" placeholder="Service Name" class="form-control" ng-change="searchPersonas(serviceId, 'service_id')">
                            </div>

                            <div class="form-group">
                                    <select class="form-control" id="status" name="status" ng-model="persona_status" ng-change="searchPersonas(persona_status, 'status')">
                                    <option value="" disabled selected>Status</option>
                                        <option value="">All</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                            </div>                            
                            </form>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row" >
            <div class="col-md-3">
                <h2 class="side-header">Personas List</h2>

                <div class="bulletin-list">

                    <ul ng-show="personas.length <= 0">
                        <li ng-show="is_no_search_result == false" style="text-align:center;">Loading personas!</li> 
                        <li ng-show="is_no_search_result == true" style="text-align:center;">No Result!</li>
                    </ul>
                    <ul>
                        <li dir-paginate="persona in personas|filter:search|itemsPerPage:itemsPerPage" total-items="total_count" current-page="currentPage" >
                            <a href="#" ng-click="toggle('edit', persona.id)"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <%persona.name%> </a>
                        </li>
                    </ul>
                    <dir-pagination-controls
                        max-size="3"
                        direction-links="true"
                        boundary-links="true"
                        on-page-change="getData(newPageNumber)" >
                    </dir-pagination-controls>

                </div>

            </div>
            <div class="col-md-9">
                <h2 class="side-header">Persona Information</h2>
                <div class="bulletin-info">
                    <form name="frmPersonas" class="form-horizontal" novalidate="">
                        <fieldset>
                            <legend>Company Profile</legend>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Name</label>
                                <div class="col-md-6">
                                    <input type="text" id="name" name="name" class="form-control" ng-model="persona.name" ng-required="true" value="<%name%>">
                                    <span class="help-inline" 
                                          ng-show="frmPersonas.name.$invalid && frmPersonas.name.$touched">Name field is required</span>
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Service</label>
                                <div class="col-md-6">
                                    <input type="text" id="service_id" name="service_id" class="form-control" ng-model="persona.service_id" ng-required="true" value="<%service_id%>">
                                    <span class="help-inline" 
                                          ng-show="frmPersonas.name.$invalid && frmPersonas.name.$touched">Name field is required</span>
                                </div>
                            </div>

                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Status</label>
                                <div class="col-md-2">
                                    <select id="status" ng-model="persona.status" ng-required="true" name="status" class="form-control">
                                        <option value="active" selected="selected">active</option>
                                        <option value="inactive">inactive</option>
                                        <option value="hidden">delete</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Profile</label>
                                <div class="col-md-6">
                                    <textarea name="profile" id="profile" class="form-control"><%persona.profile%></textarea>
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Additional Information</label>
                                <div class="col-md-6">
                                    <textarea name="additional_info" id="additional_info" class="form-control"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-group margin-bottom5">
                            <button type="submit" ng-click="save(id)" class="btn btn-danger">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div> 

</div>
<!-- Scripts -->
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<script src="<?php echo URL::asset('js/angular/angular.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/jquery-2.1.1.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/toastr.js') ?> "></script>

<!-- AngularJS Application Scripts -->
 <script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>
      <script src="<?php echo URL::asset('js/angular-toastr.tpls.js')?>"></script>
    <link rel="stylesheet" href="<?php echo URL::asset('css/angular-toastr.css')?>" />
    
    <script src="<?php echo URL::asset('js/angular/controllers/personas.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/app/personas.js') ?>"></script>

</html>
@stop