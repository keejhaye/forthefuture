	<!doctype html>
	<html  ng-app="StatusApp" >
	<head>
		<title>Chat Status</title>
		<link rel = "stylesheet" href = "{{asset('/')}}/css/chat_status.css" />
		<link rel = "stylesheet" href="{{asset('/')}}/bower_components/angular-material/angular-material.min.css" />
		<script type="text/javascript">
      var user_id = "{{ \Session::get('user.id') }}"
    </script>
	</head>
	<body ng-controller='ChatStatusController' ng-cloak>
	<md-grid-list
        md-cols="1" md-cols-sm="2" md-cols-md="3" md-cols-gt-md="6"
        md-row-height-gt-md="1:1" md-row-height="4:3"
        md-gutter="8px" md-gutter-gt-sm="4px" >

	    <md-grid-tile class="purple" md-rowspan="" md-colspan="3" md-colspan-sm="1" md-colspan-xs="1">
	      	<div class="obj-layout-container" layout="column" layout-fill>
	      		<div flex="none" class="obj-layout-header">
	      			<h3>users</h3>
	      		</div>
	      		<div flex="none" class="obj-table-container">
			      	<table class="obj-table">
				    	<tr>
				    		<th>user</th>
				    		<th>socket id</th>
				    		<th>services</th>
				    		<th>conversations</th>
				    	</tr>
				    	<tr ng-repeat="user in users">
				    		<td><%user.username%>(<%user.id%>)</td>
				    		<td><%user.socket_id%></td>
				    		<td><%user.services%></td>
				    		<td><%user.conversation_ids%></td>
				    </table>
	      	 	</div>
	      	</div>
	    </md-grid-tile>

	    <md-grid-tile class="green" md-rowspan="1" md-colspan="1" md-colspan-sm="1" md-colspan-xs="1">
	      	<md-grid-tile-header>
	        	<h3>on chat</h3>
	      	</md-grid-tile-header>
	      	<h1 class="counter"><%count.onchat%></h1>
	    </md-grid-tile>

	    <md-grid-tile class="red" md-rowspan="1" md-colspan="1" md-colspan-sm="1" md-colspan-xs="1">
	      	<md-grid-tile-header>
	        	<h3>assigned</h3>
	      	</md-grid-tile-header>
	      	<h1 class="counter"><%count.assigned%></h1>
	    </md-grid-tile>

	    <md-grid-tile class="lightblue" md-rowspan="1" md-colspan="1" md-colspan-sm="1" md-colspan-xs="1">
	      	<md-grid-tile-header>
	        	<h3>pending</h3>
	      	</md-grid-tile-header>
	      	<h1 class="counter"><%count.pending%></h1>
	    </md-grid-tile>

	    <md-grid-tile class="aqua"
	        md-rowspan="2" md-colspan="3" md-colspan-sm="1" md-colspan-xs="1">
	      	<div class="obj-layout-container" layout="column" layout-fill>
	      		<div flex="none" class="obj-layout-header">
	      			<h3>conversations</h3>
	      		</div>
	      		<div flex="none" class="obj-table-container">
		      		<table class="obj-table">
				    	<tr>
				    		<th>ID</th>
				    		<th>Status</th>
				    		<th>Service ID</th>
				    		<th>Date</th>
				    		<th>User</th>
				    		<th>Last Message ID</th>
				    		<th>Latest Message IDs</th>
				    		<th>CDID</th>
				    		<th>Caller</th>
				    	</tr>
				    	<tr ng-repeat="convo in conversations | toArray | orderBy:'wait_start'">
				    		<td><%convo.conversation_id%></td>
				    		<td><%convo.status%></td>
				    		<td><%convo.service_id%></td>
				    		<td><%convo.wait_start%></td>
				    		<td><%convo.user_id%></td>
				    		<td><%convo.last_message_id%></td>
				    		<td><%convo.latest_message_ids%></td>
				    		<td><%convo.cdid%></td>
				    		<td><%convo.caller%></td>
				    	</tr>
				    </table>
	      	 	</div>
	      	</div>
	    </md-grid-tile>

	    <md-grid-tile class="deep-blue-grey" md-rowspan="2" md-colspan="3" md-colspan-sm="1" md-colspan-xs="1">
	      	<div class="obj-layout-container" layout="column" layout-fill>
	      		<div flex="none" class="obj-layout-header">
	      			<h3>services</h3>
	      		</div>
	      		<div flex="none" class="obj-table-container">
			      	<table class="obj-table">
				    	<tr>
				    		<th>ID</th>
				    		<th>Name</th>
				    		<th>Pending</th>
				    		<th>Total Queue</th>
				    		<th>Queue</th>
				    		<th>Users</th>
				    	</tr>
				    	<tr ng-repeat="service in services" ng-if="!isEmpty(service.queue)">
				    		<td><%service.id%></td>
				    		<td><%service.name%></td>
				    		<td><%service.pending%></td>
				    		<td><%keys(service.queue).length%></td>
				    		<td>
				    			<div ng-repeat="queue in service.queue">
				    				<pre><%queue | json%></pre>
				    			</div>
				    		</td>
				    		<td>
				    			<div ng-repeat="user in service.users"><pre><%user | json%></pre></div>
				    		</td>
				    	</tr>
				    </table>
	      		</div>
	      	</div>
	    </md-grid-tile>
  	</md-grid-list>
	</body>
		<script src="http://localhost:3000/socket.io/socket.io.js"></script>
		<script src="{{asset('/')}}/js/angular/angular.min.js"></script>
		<script src="{{asset('/')}}/bower_components/angular-aria/angular-aria.min.js"></script>
		<script src="{{asset('/')}}/bower_components/angular-material/angular-material.min.js"></script>
		<script src="{{asset('/')}}/bower_components/angular-messages/angular-messages.min.js"></script>
		<script src="{{asset('/')}}/bower_components/angular-animate/angular-animate.min.js"></script>
		<script src="{{asset('/')}}/bower_components/angular-toArrayFilter/toArrayFilter.js"></script>
		<script src="{{asset('/')}}/js/angular/services/angular-socket.js"></script>

		<script src="{{asset('/')}}/js/res/moment.min.js"></script>
		<script src="{{asset('/')}}/js/angular/directives/angular-moment.min.js"></script>

		<script src="{{asset('/')}}/js/angular/controllers/chat_status.js"></script>
		<script src="{{asset('/')}}/js/angular/app/chat/status2.js"></script>
	</html>
