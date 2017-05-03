	<!doctype html>
	<html  ng-app="StatusApp" >
	<head>
		<title>Chat Status</title>
		<link rel="stylesheet" href="{{url('/')}}/bower_components/angular-material-data-table/dist/md-data-table.min.css"/>
		<link rel = "stylesheet" href="{{url('/')}}/bower_components/angular-material/angular-material.min.css" />
		<link rel = "stylesheet" href = "{{url('/')}}/css/chat_status.css" />
		<link rel = "stylesheet" href = "{{url('/')}}/css/angular-resizeable.min.css" />
		<link rel="stylesheet" href="{{url('/')}}/css/font-awesome.min.css">
	</head>
    <script type="text/javascript">
        var base_url = "{{ url('/') }}"
        var user_id = "{{ \Session::get('user.id') }}"
    </script>
	<body ng-controller='ChatStatusController' ng-cloak layout='column'>
	  <md-toolbar class='main-toolbar md-whiteframe-glow-z1' layout='row'>
	  	<div class="md-toolbar-tools">
			<md-button ng-click="leftOpen = !leftOpen" aria-label='toggle-left' class="md-primary md-raised pull-left">
				<i class='fa fa-bars fa-3x'></i>
			</md-button>
			<span flex></span>
			<md-button ng-click="toggleRight()" aria-label='toggle-right' class="md-primary md-raised pull-left">
				<i class='fa fa-cog fa-3x'></i>
			</md-button>
		</div>
	  </md-toolbar>
	  <div layout="row" flex>
	    <md-content id="usersPanel"
			    	ng-hide="!leftOpen"
				    resizable 
				    r-directions="['right']" 
				    r-flex="true">
		  <md-toolbar class='usersToolbar'>
		    <div class="md-toolbar-tools md-whiteframe-3dp">
		      <h3>
		        <span class="md-display-2">Users (<% ids.users.length %>)</span>
		      </h3>
		    </div>
		  </md-toolbar>
			  <md-content layout="column"  class='md-whiteframe-3dp' style="padding-bottom: 0;padding-top: 0;">
			        <md-input-container class='searchContainer'>
			          <label>Search User List Here!</label>
			          <input ng-model="userSearch">
			        </md-input-container>
			      	<ul>
			      		<li ng-repeat="user in users | toArray:true | filter : userSearch | orderBy: tableOptions.users.order | limitTo: tableOptions.users.limit : (tableOptions.users.page -1) * tableOptions.users.limit" 
				      		ng-if="user.id != undefined"
							class="<% user.conversation_ids.length > (settings['max_operator_conversation'].value - 1) ? 'userUnavailable' : 'userAvailable' %>">
			      			<strong><%user.username %></strong> | 
			      			<strong>ID</strong> : <% user.id %> | 
			      			<strong>Convs</strong> : <%user.conversation_ids.length %> | 
			      			<strong>C-IDS</strong> :<% user.conversation_ids %> | 
			      			<strong>S</strong> :<% user.services %> | 
			      			<strong>S-ID</strong> : <%user.socket_id%>
		      				<md-tooltip md-direction="top">
				      			<strong>ID</strong> : <% user.id %> | 
				      			<strong>Convs</strong> : <%user.conversation_ids.length %> | 
				      			<strong>C-IDS</strong> :<% user.conversation_ids %> | 
				      			<strong>S</strong> :<% user.services %> | 
				      			<strong>S-ID</strong> : <%user.socket_id%>
		      				</md-tooltip>
			      		</li>
			      	</ul>
			      <md-table-pagination md-limit="tableOptions.users.limit" md-limit-options="[3, 5, 7, 10, 15, 20]" md-page="tableOptions.users.page" md-total="<% ids.users.length %>" md-page-select></md-table-pagination>
			  </md-content>
	    </md-content>
	    <md-content id="conversationsPanel" flex>
		  <md-toolbar class='green' md-scroll-shrink>
		    <div class="md-toolbar-tools tan md-whiteframe-3dp">
		      <h3>
		        <span class="md-display-2">Conversations (<% count.assigned + count.pending %>)</span>
		      </h3>
		    </div>
		  </md-toolbar>
		  <md-grid-list
		        md-cols="2"
		        md-row-height-gt-md="1:2" md-row-height="fit">
		    <md-grid-tile class="yellow md-whiteframe-4dp" md-rowspan="1" md-colspan="1">
		      <md-grid-tile-header>
		        <h3 class="md-headline">Pending (<% count.pending %>)</h3>
		      </md-grid-tile-header>
		      <md-grid-tile-body>
				  <md-content flex layout="column"  class='md-whiteframe-3dp' style="padding-bottom: 0;padding-top: 0;">
				        <md-input-container class="searchContainer">
				          <label>Search Pending Conversations Here!</label>
				          <input ng-model="pendingSearch">
				        </md-input-container>
				      <md-table-container>
				        <table md-table ng-model="selected_id" md-progress="promise">
				          <thead md-head md-order="tableOptions.pending.order" md-on-reorder="reorderTable">
				            <tr md-row>
				              <th md-column md-order-by="conversation_id"><span>ID</span></th>
				              <th md-column md-order-by="date"><span>Date</span></th>
				              <th md-column md-order-by="wait_start"><span>Wait Start</span></th>
				              <!-- <th md-column><span>Action</span></th> -->
				            </tr>
				          </thead>
				          <tbody md-body>
				            <tr ng-repeat="conversation in conversations | toArray:true | filter: {status: 'queued'} | filter : pendingSearch | orderBy: tableOptions.pending.order | limitTo: tableOptions.pending.limit : (tableOptions.pending.page -1) * tableOptions.pending.limit" md-row md-select="conversation.conversation_id"  md-select-id="conversation_id" >
				              
				              <td md-cell ng-class="{'md-placeholder': !conversation.conversation_id}" >            
				                <%conversation.conversation_id%>
				                <md-tooltip md-direction="top"><%conversation.conversation_id%></md-tooltip>
				              </td>
				              <td md-cell ng-class="{'md-placeholder': !conversation.date}" >
				                <%conversation.date | amDateFormat : 'MMM DD hh:mm:ss a' %>
				                <md-tooltip md-direction="top"><%conversation.date | amDateFormat : 'MMM DD hh:mm:ss a' %></md-tooltip>
				              </td>
				              <td md-cell ng-class="{'md-placeholder': !conversation.wait_start}" >
				                <%conversation.wait_start | amDateFormat : 'MMM DD hh:mm:ss a' %>
				                <md-tooltip md-direction="top"><%conversation.wait_start%></md-tooltip>
				              </td>
				            </tr>
				          </tbody>
				        </table>
				      </md-table-container>

				      <md-table-pagination md-limit="tableOptions.pending.limit" md-limit-options="[3, 5, 7, 10, 15, 20]" md-page="tableOptions.pending.page" md-total="<% count.pending %>" md-page-select></md-table-pagination>
				  </md-content>
		      </md-grid-tile-body>
		    </md-grid-tile>
		    <md-grid-tile class="blue md-whiteframe-4dp" md-rowspan="fit" md-colspan="fit">
		      <md-grid-tile-header>
		        <h3 class="md-headline">Assigned (<% count.assigned %>)</h3>
		      </md-grid-tile-header>
		      <md-grid-tile-body>
				  <md-content layout="column"  class='md-whiteframe-3dp' style="padding-bottom: 0;padding-top: 0;">
				        <md-input-container class='searchContainer'>
				          <label>Search Assigned Conversations Here!</label>
				          <input ng-model="assignedSearch">
				        </md-input-container>
				      <md-table-container>
				        <table md-table ng-model="selected_id" md-progress="promise">
				          <thead md-head md-order="tableOptions.assigned.order" md-on-reorder="reorderTable">
				            <tr md-row>
				              <th md-column md-order-by="conversation_id"><span>ID</span></th>
				              <th md-column md-order-by="assigned_start"><span>Assigned Start</span></th>
				              <th md-column md-order-by="user_id"><span>user ID</span></th>
				              <th md-column md-order-by="message_ids.length"><span>Message Count</span></th>
				              <!-- <th md-column><span>Action</span></th> -->
				            </tr>
				          </thead>
				          <tbody md-body>
				            <tr ng-repeat="conversation in conversations | toArray:true | filter: {status:'assigned'} | filter : assignedSearch | orderBy: tableOptions.assigned.order | limitTo: tableOptions.assigned.limit : (tableOptions.assigned.page -1) * tableOptions.assigned.limit" md-row md-select="conversation.conversation_id"  md-select-id="conversation_id" >
				              
				              <td md-cell ng-class="{'md-placeholder': !conversation.conversation_id}" >            
				                <%conversation.conversation_id%>
				                <md-tooltip md-direction="top"><%conversation.conversation_id%></md-tooltip>
				              </td>
				              <td md-cell ng-class="{'md-placeholder': !conversation.assigned_start}" >
				                <%conversation.assigned_start | amDateFormat : 'MMM DD hh:mm:ss a' %>
				                <md-tooltip md-direction="top"><%conversation.assigned_start%></md-tooltip>
				              </td>
				              <td md-cell ng-class="{'md-placeholder': !conversation.user_id}" >
				                <%conversation.user_id%>
				                <md-tooltip md-direction="top"><%conversation.user_id%></md-tooltip>
				              </td>
				              <td md-cell ng-class="{'md-placeholder': !conversation.message_ids.length}" >
				                <%conversation.message_ids.length%>
				                <md-tooltip md-direction="top"><%conversation.message_ids.length %></md-tooltip>
				              </td>
				            </tr>
				          </tbody>
				        </table>
				      </md-table-container>

				      <md-table-pagination md-limit="tableOptions.assigned.limit" md-limit-options="[3, 5, 7, 10, 15, 20]" md-page="tableOptions.assigned.page" md-total="<% count.assigned %>" md-page-select></md-table-pagination>
				  </md-content>
		      </md-grid-tile-body>
		    </md-grid-tile>
		  </md-grid-list>
	    </md-content>
	    <md-sidenav class="md-sidenav-right"
			        md-component-id="right"
			        md-is-open="false">
	        <md-toolbar class='settings-toolbar'>
	        	<h3><span class="md-display-2">Settings</span></h3>
	        </md-toolbar>
			<md-content>
		      <md-list flex>
		      		<form name="settingsForm">
				        <md-content class="md-1-line" ng-repeat="(name,setting) in settings" layout="row">
						        <md-input-container flex="60">
									<label><% name %></label>
									<input ng-model="setting.value" name="<%name%>" required type="number" ng-disabled="setting.disabled" min=0 > 
									<div ng-messages="settingsForm[name].$error">
									  <div ng-message="required">This setting must not be empty.</div>
									  <div ng-message="min">Minimum value is zero</div>
									</div>
						        </md-input-container>
						        	<div layout="column" flex="40">
						        		<div layout="row">
									        <md-button layout="column" class="md-fab md-raised edit-settings md-raised" aria-label="Edit" ng-if="setting.disabled" ng-click='setting.disabled = !setting.disabled'>
									            <md-icon class="fa fa-edit fa-2x"></md-icon>
									        </md-button>
									        <md-button layout="column" class="md-fab md-primary save-settings md-raised" aria-label="Save" ng-if="!setting.disabled" ng-click="saveSettings(name, setting.value)">
									            <md-icon class="fa fa-check fa-2x"></md-icon>
									        </md-button>
									        <md-button layout="column" class="md-fab md-raised md-warn cancel-settings" aria-label="Edit" ng-if="!setting.disabled" ng-click='setting.disabled = !setting.disabled'>
									            <md-icon class="fa fa-close fa-2x"></md-icon>
									        </md-button>
						        		</div>
						        	</div>
				        </md-content>
		        	</form>
		        <md-divider ></md-divider>
		      </md-list>
	        </md-content>
	    </md-sidenav>
	  </div>
	</body>
		<script src="http://localhost:3000/socket.io/socket.io.js"></script>
		<script src="{{url('/')}}/js/angular/angular.min.js"></script>
		<script src="{{url('/')}}/bower_components/angular-material-data-table/dist/md-data-table.min.js"></script>
		<script src="{{url('/')}}/bower_components/angular-aria/angular-aria.min.js"></script>
		<script src="{{url('/')}}/bower_components/angular-material/angular-material.min.js"></script>
		<script src="{{url('/')}}/bower_components/angular-messages/angular-messages.min.js"></script>
		<script src="{{url('/')}}/bower_components/angular-animate/angular-animate.min.js"></script>
		<script src="{{url('/')}}/bower_components/angular-toArrayFilter/toArrayFilter.js"></script>

		<script src="{{url('/')}}/js/angular/services/angular-socket.js"></script>
		<script src="{{url('/')}}/js/res/moment.min.js"></script>
		<script src="{{url('/')}}/js/angular/directives/angular-moment.min.js"></script>
		<script src="{{url('/')}}/js/angular/directives/angular-resizeable.min.js"></script>
		<script src="{{url('/')}}/js/angular/controllers/chat_status.js"></script>
		<script src="{{url('/')}}/js/angular/app/chat/status.js"></script>
	</html>
