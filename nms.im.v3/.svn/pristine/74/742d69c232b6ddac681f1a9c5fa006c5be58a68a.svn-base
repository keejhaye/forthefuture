@extends('layouts.ProtectedPagesTemplateContent')

@section('css')
<link rel="stylesheet" href="<?php echo URL::asset('css/dataTables.bootstrap.min.css')?>">
<link rel="stylesheet" href="<?php echo URL::asset('css/bootstrap-datetimepicker.min.css')?>">
<link rel="stylesheet" href="<?php echo URL::asset('css/angucomplete-alt.css')?>">
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

@section('content')
<div>
    <div class="reports-container" ng-controller="reportsController">
        <div class="loading" ng-if="generating == true">
          <img src="../img/Preloader_11.gif" class="loading-img">
        </div>
        <div class="content-header">
            <div class="pull-left">
                <h4 class="header-title"><strong>Reports</strong></h4>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="page-search-form">
            <h5 class="search-header">Filter</h5>
            <div ng-if="enableAlert" class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-<%alert.type%> alert-dismissible text-center" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <%alert.message%>
                    </div>
                </div>
            </div>
            <form class="form-horizontal" role="form">
                <div class="col-md-4">
                    <div class="form-group margin-bottom5">
                        <label class="col-md-2 control-label">Display</label>
                        <div class="col-md-10">
                            <select ng-model="search.display" id="entity" name="entity" class="form-control">
                                <optgroup ng-repeat="(key, group) in display" label="<%key%>">
                                    <option ng-repeat="(key, name) in group" value="<%key%>"><%name%></option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="form-group margin-bottom5">
                        <label class="col-md-2 control-label">Filter</label>
                        <div class="col-md-10">
                            <select ng-change="enableFilter(search.filter)" ng-model="search.filter" id="filter" name="filter" class="form-control">
                                <option ng-repeat="(key, name) in filter" value="<%key%>"><%name%></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group margin-bottom5">
                        <!-- <label class="col-md-2 control-label">User</label> -->
                        <div class="col-md-12">
                            <!-- <input ng-model="search.filter_search" placeholder="Filter" id="filter_search" name="filter_search" class="form-control" value="" type="text" typeahead="state for state in states | filter:$viewValue | limitTo:8"> -->
                            <angucomplete-alt id="filter_search"
                              placeholder="Filter"
                              pause="100"
                              selected-object="fltrSelectedObj"
                              local-data="fltr"
                              minlength="3"
                              input-name="filter_search"
                              input-class="form-control form-control-small"
                              input-changed="inputChangedFn"
                              remote-url="reports/filter/<%fltrEntity%>?search="
                              remote-url-data-field="results"
                              title-field="name"
                              match-class="highlight" 
                              disable-input="disableFltrInput"/>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group margin-bottom5">
                        <label class="col-md-3 control-label">Preset</label>
                        <div class="col-md-9">
                            <select ng-model="search.preset" id="preset" name="preset" class="form-control" ng-change="setDatePreset(search.preset)">
                                <option ng-repeat="(key, name) in preset" value="<%key%>"><%name%></option>
                            </select>
                        </div>
                    </div>
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
                    <div class="form-group margin-bottom5">
                        <label class="col-md-3 control-label">User Type</label>
                        <div class="col-md-9">
                            <select ng-model="search.utype" name="utype" class="form-control" ng-disabled="disableUtypeInput">
                                <option ng-repeat="(key, name) in uTypes" value="<%key%>"><%name%></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group margin-bottom5">
                        <label class="col-md-2 control-label">Timezone</label>
                        <div class="col-md-10">
                            <select ng-model="search.timezone" id="timezone" name="timezone" class="form-control" ng-change="setTimezone(search.timezone)">
                                <option ng-repeat="(key, name) in timezones" value="<%key%>" ng-selected="key == search.timezone"><%name%></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button ng-click="generateReport()" class="btn btn-primary pull-right btn-block"> <span>Search</span> <i class="fa fa-search"></i> </button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
        <div id="reports-table-container">
            <!-- <table ng-if="generatedReport.result.length > 0" id="reports-datatable" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%"> -->
            <table id="reports-datatable" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" ng-repeat="col in generatedReport.headers"><%col%></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 0 RESULT -->
                    <tr class="info" ng-if="generatedReport.result.length == 0">
                        <td class="text-center" colspan="<%generatedReport.headers.length%>"><strong>NO DATA</strong></td>
                    </tr>

                    <!-- AVERAGE RESPONSE TIME PER HOUR -->
                    <tr ng-if="generatedReport.display == 'average_response_time_per_hour'" ng-repeat="row in generatedReport.result">
                        <td><%row.per_hour%></td>
                        <td><%row.inbound%></td>
                        <td><%row.operators%></td>
                        <td><%row.ave%></td>
                    </tr>

                    <!-- TOTAL MESSAGES PER SUBSCRIBER/USER/PERSONA/SERVICE-->
                    <tr ng-if="generatedReport.display == 'total_messages_per_subscriber' || 
                    generatedReport.display == 'total_messages_per_service' ||
                    generatedReport.display == 'total_messages_per_persona'" 
                    ng-repeat="row in generatedReport.result">
                        <td ng-if="generatedReport.display == 'total_messages_per_subscriber'"><%row.subscriber_name_in%></td>
                        <td ng-if="generatedReport.display == 'total_messages_per_service'"><%row.service_name_in%></td>
                        <td ng-if="generatedReport.display == 'total_messages_per_persona'"><%row.persona_name_in%></td>
                        <td ng-if="row.inbound != undefined"><%row.inbound == null ? 0 : row.inbound%></td>
                        <td><%row.outbound == null ? 0 : row.outbound%></td>
                        <td><%row.free%></td>
                        <td><%row.billed%></td>
                        <td class="text-center" ng-if="generatedReport.display == 'total_messages_per_subscriber'">
                            <a href="#" title="Logs" ng-click="getLogs(row.subscriber_id, row.subscriber_name_in)"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                        </td>
                        <td class="text-center" ng-if="generatedReport.display == 'total_messages_per_persona'">
                            <a href="#" title="Logs" ng-click="getLogs(row.persona_id, row.persona_name_in)"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                        </td>
                    </tr>

                    <!-- TOTAL MESSAGES PER USER -->
                    <tr ng-if="generatedReport.display == 'total_messages_per_user'" ng-repeat="row in generatedReport.result">
                        <td><%row.fullname_out%></td>
                        <td><%row.outbound == null ? 0 : row.outbound%></td>
                        <td><%row.free%></td>
                        <td><%row.billed%></td>
                        <td class="text-center">
                            <a href="#" title="Logs" ng-click="getLogs(row.user_id, row.fullname_out)"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                        </td>
                    </tr>

                    <!-- OPERATOR MESSAGES PER HOUR -->
                    <tr ng-if="generatedReport.display == 'operator_messages_per_hour'" ng-repeat="row in generatedReport.result">
                        <td><%row.hour_in%></td>
                        <td><%row.fullname%></td>
                        <td><%row.replies == null ? 0 : row.replies%></td>
                        <td><%row.subscribers == null ? 0 : row.subscribers%></td>
                        <td class="text-center">
                            <a href="#" title="Logs"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                        </td>
                    </tr>

                    <!-- HOURLY REPORT -->
                    <tr ng-if="generatedReport.display == 'hourly_report'" ng-repeat="row in generatedReport.result">
                        <td><%row.hour_in%></td>
                        <td><%row.count_in == null ? 0 : row.count_in%></td>
                        <td><%row.count_out == null ? 0 : row.count_out%></td>
                        <td><%row.free%></td>
                    </tr>

                    <!-- OPERATOR_MESSAGES_AND_PAYOUT -->
                    <tr ng-if="generatedReport.display == 'operator_messages_and_payout'" ng-repeat="row in generatedReport.result">
                        <td><%row.username%></td>
                        <td><%row.count%></td>
                        <td ng-repeat="m in generatedReport.multipliers"><%row['x'+m.multiplier]%></td>
                        <td><%row.deductions == null ? 0 : row.deductions%></td>
                        <td><%row.rewards == null ? 0 : row.rewards%></td>
                        <td>total adjustment</td>
                        <td class="text-center">
                            <a href="#" title="Message Log"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                        </td>
                    </tr>

                    <!-- KEY PERFORMANCE INDICATOR -->
                    <tr ng-if="generatedReport.display == 'kpi'" ng-repeat="row in generatedReport.result">
                        <td><%row.service%></td>
                        <td><%row.inbound%></td>
                        <td><%row.discarded%></td>
                        <td><%row.subscribers%></td>
                        <td><%row.kpi%></td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <button ng-if="generatedReport.result.length > 0" ng-click="exportCSV()" type="button" class="btn btn-primary btn-block center-block" style="margin-bottom: 40px;">Export CSV</button>
                </div>
            </div>
        </div>

        <div ng-if="logsData.result != undefined" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Message Logs</h4>
                </div>
                <div class="modal-body">
                    <table id="modal-logs-table" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" ng-repeat="col in logsData.headers"><%col%></th>
                            </tr>
                        </thead>
                        <tbody id="modal-logs-tbody">
                            <tr ng-repeat="log in logsData.result">
                                <td><%log.fullname%></td>
                                <td><%log.persona_name%></td>
                                <td><%log.subscriber_name%></td>
                                <td><%log.service_name%></td>
                                <td><%log.message%></td>
                                <td><%log.bound_time%></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button ng-click="exportLogs()" type="button" class="btn btn-primary">Export Logs CSV</button>
                </div>
            </div>
          </div>
        </div>
    </div>    
</div>
@stop

@section('script')  
<script type="text/javascript" src="<?php echo URL::asset('js/jquery-2.1.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/moment.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/bootstrap.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/bootstrap-datetimepicker.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/jquery.dataTables.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/dataTables.bootstrap.min.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/dataTables.responsive.min.js')?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>  

<!-- AngularJS Application Scripts -->
<script type="text/javascript" src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/angular/directives/angucomplete-alt.js') ?>"></script>

<script type="text/javascript" src="<?php echo URL::asset('js/angular/controllers/reports.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::asset('js/angular/app/reports.js') ?>"></script>

<script type="text/javascript">
    // $(function () {
    //     $('#datetimepicker6').datetimepicker({
    //         format: 'YYYY-MM-DD HH:mm:ss'
    //     });
    //     $('#datetimepicker7').datetimepicker({
    //         format: 'YYYY-MM-DD HH:mm:ss',
    //         useCurrent: false //Important! See issue #1075
    //     });

    //     $("#datetimepicker6").on("dp.change", function (e) {
    //         $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    //         angular.element(this).scope().search.sdate = moment(e.date).format('YYYY-MM-DD HH:mm:ss')
    //     });

        // $("#datetimepicker7").on("dp.change", function (e) {
        //     $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        //     angular.element(this).scope().search.edate = moment(e.date).format('YYYY-MM-DD HH:mm:ss')
        // });
    // });
</script>
@stop
