
@extends('layouts.ProtectedPagesTemplateContent')
@section('content')

 <div ng-controller="batchController as service">
<div class="users-tabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#operator_service" aria-expanded="true">Operator Service</a></li>
                        <li class=""><a data-toggle="tab" href="#menu1" aria-expanded="false">Second Tab</a></li>
                        <li><a data-toggle="tab" href="#menu2">Third Tab</a></li>
                    </ul>

                        <div id="operator_service" class="tab-pane fade">
                          <form name="frmAssignOps" role="form" novalidate="" ng-submit="service.saveServiceUsers()">
                                        <fieldset class="col-md-5">

                                                <div class="form-group margin-bottom5">
                                                    <label class="col-md-5 control-label">Search Service</label>
                                                    <div class="col-md-7">
                                                        <div>
                                                            <ui-select multiple ng-model="service.operators.selected" ng-disabled="false" theme="bootstrap" close-on-select="false" title="Assign operators">
                                                                <ui-select-match placeholder="Select service"><%$item.username%> (<%$item.role%>)</ui-select-match>
                                                                <ui-select-choices 
                                                                    repeat="operator.id as operator in service.searchServiceOptions | filter: $select.search"
                                                                    refresh="service.searchService($select.search)"
                                                                    refresh-delay="3"
                                                                    position="auto">

                                                                    <div ng-bind-html="operator.name | highlight: $select.search"></div>
                                                                    <small>
                                                                        <%operator.username%> (<span ng-bind-html="''+operator.role | highlight: $select.search"></span>)
                                                                    </small>
                                                                </ui-select-choices>
                                                            </ui-select>
                                                        </div>
                                                        <span class="text-red" ng-show="service.operators.res.hasError && (service.operators.res.errors.users != null)"><% service.operators.res.errors.users[0] %></span>
                                                    </div>
                                                </div>
                                                <br>
                                                <br>
                                                <!-- <div class="form-group margin-bottom5">
                                                    <label class="col-md-5 control-label">Multipler</label>
                                                    <div class="col-md-7">
                                                        <input type="text" id="multiplier" name="multiplier" class="form-control" value="" ng-model="service.operators.multiplier">
                                                        <span class="text-red" ng-show="service.operators.res.hasError && (service.operators.res.errors.multiplier != null)">[[ service.operators.res.errors.multiplier[0] ]]</span>
                                                    </div>
                                                </div> -->
                                       
                                        </form>

                          

                                                <div class="form-group margin-bottom5">
                                                    <label class="col-md-5 control-label">Assign Operator/s</label>
                                                    <div class="col-md-7">
                                                        <div>
                                                            <ui-select multiple ng-model="service.operators.selected" ng-disabled="false" theme="bootstrap" close-on-select="false" title="Assign operators">
                                                                <ui-select-match placeholder="Select operators..."><%$item.username%> (<%$item.role%>)</ui-select-match>
                                                                <ui-select-choices 
                                                                    repeat="operator.id as operator in service.searchUsersOptions | filter: $select.search"
                                                                    refresh="service.searchUsers($select.search)"
                                                                    refresh-delay="3"
                                                                    position="auto">

                                                                    <div ng-bind-html="operator.name | highlight: $select.search"></div>
                                                                    <small>
                                                                        <%operator.username%> (<span ng-bind-html="''+operator.role | highlight: $select.search"></span>)
                                                                    </small>
                                                                </ui-select-choices>
                                                            </ui-select>
                                                        </div>
                                                        <span class="text-red" ng-show="service.operators.res.hasError && (service.operators.res.errors.users != null)"><% service.operators.res.errors.users[0] %></span>
                                                    </div>
                                                </div>
                                                <br>
                                                <br>
                                                <!-- <div class="form-group margin-bottom5">
                                                    <label class="col-md-5 control-label">Multipler</label>
                                                    <div class="col-md-7">
                                                        <input type="text" id="multiplier" name="multiplier" class="form-control" value="" ng-model="service.operators.multiplier">
                                                        <span class="text-red" ng-show="service.operators.res.hasError && (service.operators.res.errors.multiplier != null)">[[ service.operators.res.errors.multiplier[0] ]]</span>
                                                    </div>
                                                </div> -->

                                                <div class="form-group margin-bottom5">
                                                    <button type="submit" class="btn btn-danger">Save</button>
                                                </div>
                                            </fieldset>
                                        </form>
              <div class="row service-users-list">

                                            <table class="table table-bordered table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Fullname</th>
                                                        <th>Role</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-show="service.assignedUsers.total==0">
                                                        <td colspan="4">No assigned user.</td>
                                                    </tr>
                                              
                                                    <tr>
                      
                                                    </tr>
                                                </tbody>
                                            </table>
                                    </div>
    
                                    
                               
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <h3>Second Tab</h3>
                            <p>Some content in menu 1.</p>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <h3>Third Tab</h3>
                            <p>Some content in menu 2.</p>
                        </div>
               
                </div>
</div>

<!-- Scripts -->
<!-- AngularJS Application Scripts -->
<script src="<?php echo URL::asset('js/jquery-2.1.1.min.js') ?>"></script>
<script src="<?php echo URL::asset('js/bootstrap.min.js') ?>"></script>
<script src="<?php echo URL::asset('js/toastr.js') ?>"></script>
 <script src="<?php echo URL::asset('js/angular/angular.min.js')?>"></script>
<script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>


<script src="<?php echo URL::asset('bower_components/angular-sanitize/angular-sanitize.min.js')?>"></script>
<script src="<?php echo URL::asset('bower_components/angular-ui-select/dist/select.js')?>"></script>
<script src="<?php echo URL::asset('js/angular/controllers/batch.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/app/batch.js')?>"></script>

</html>
@stop

