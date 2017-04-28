
    @extends('layouts.ProtectedPagesTemplateContent')
    @section('content')

    <div ng-controller="blacklistController">
        <div class="users-container" >
            <div class="content-header">
                <div class="pull-left">
                    <h4 class="header-title"><strong>Blacklist</strong></h4>
                </div>
                <div class="pull-right bulletins">
                    <form class="navbar-form navbar-right">
                        <div class="input-group" id="adv-search">
                            <div class="input-group-btn">
                                <form class="form-horizontal" role="form" >
                                    <div class="form-group">
                                        <label class="control-label">Keyword:</label>
                                        <input type="text" id="service" name="service" ng-model="search" class="form-control" placeholder="Keyword">
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
                    <h2 class="side-header">Blocked</h2>
                    <div class="bulletin-list">
                         <ul ng-show="subscribers == 0">
                            <li>
                                <i class="fa fa-dot-circle-o" aria-hidden="true">No data</i>
                            </li>
                        </ul>
                        <ul>
                            <li dir-paginate="subscriber in subscribers|filter:search|itemsPerPage:10" >

                                <a href="#" ng-click="toggle('edit', subscriber.id)"><i class="fa fa-dot-circle-o" aria-hidden="true"></i><%subscriber.subscriber%></a>
                            </li>
                        </ul>
                        <dir-pagination-controls
                        max-size="3"
                        direction-links="true"
                        boundary-links="true" >
                    </dir-pagination-controls>
                    </div>
                </div>
                <div class="col-md-9">
                    <h2 class="side-header">Blacklist Information</h2>
                    <div class="bulletin-info">
                        <form class="form-horizontal" novalidate="">
                            <fieldset>
                                <legend>Company Profile</legend>
                                <div class="form-group margin-bottom5">
                                    <label class="col-md-2 control-label">Subscriber</label>
                                    <div class="col-md-6">
                                       
                                        <label class="col-md-2 control-label"><%subscriber.subscriber%></label>
             
                                    </div>
                                </div>
                                   <div class="form-group margin-bottom5">
                                    <label class="col-md-2 control-label">Service</label>
                                    <div class="col-md-6">
                                        <label class="col-md-2 control-label"><%subscriber.service%></label>
             
                                    </div>
                              
                                </div>
                                   <div class="form-group margin-bottom5">
                                    <label class="col-md-2 control-label">Blocked by</label>
                                    <div class="col-md-6">
                                       <label class="col-md-2 control-label"><%subscriber.operator%></label>
             
                                    </div>
                              
                                </div>
                                   <div class="form-group margin-bottom5">
                                    <label class="col-md-2 control-label">Date blocked</label>
                                    <div class="col-md-6">
                                        <label class="col-md-2 control-label"><%subscriber.date_created%></label>
             
                                    </div>
                              
                                </div>
                           
                            </fieldset>
                            <div class="form-group margin-bottom5" ng-show="subscriber.id != NULL">
                                <button type="submit" ng-click="confirmDelete(subscriber.id)" class="btn btn-danger">Remove from blacklist</button>
                            </div>
                        </form>
                </div>
            </div>
        </div> 

    </div>
    </div>
    <!-- Scripts -->  

 {{-- <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script> --}}
    <script src="<?php echo URL::asset('js/angular/angular.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/jquery-2.1.1.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/toastr.js') ?> "></script>
    <script src="<?php echo URL::asset('js/angular-toastr.tpls.js')?>"></script>
    <link rel="stylesheet" href="<?php echo URL::asset('css/angular-toastr.css')?>" />
    <!-- AngularJS Application Scripts -->
    <script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>


     <script src="<?php echo URL::asset('js/angular/controllers/blacklist.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/app/blacklist.js') ?>"></script>
<!--     <script src="<?php echo URL::asset('js/angular-toastr.tpls.js')?>"></script>
    <link rel="stylesheet" href="<?php echo URL::asset('css/angular-toastr.css')?>" /> -->
</html>
@stop