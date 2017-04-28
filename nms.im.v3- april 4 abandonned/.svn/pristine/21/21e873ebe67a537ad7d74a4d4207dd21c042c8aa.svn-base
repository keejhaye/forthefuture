  <!doctype html>
  <html  ng-app="Outbound" >
  <head>
    <title>Outbound Mock</title>
    <link rel = "stylesheet" href="{{secure_asset('/')}}/bower_components/angular-material/angular-material.min.css" />
    <link rel = "stylesheet" href = "{{secure_asset('/')}}/css/chat_status.css" />
    <script type="text/javascript">
      var user_id = "{{ \Session::get('user.id') }}"
    </script>
  </head>
  <body ng-controller='OutboundController' ng-cloak layout='column'>
    <md-toolbar class='main-toolbar md-whiteframe-glow-z1' layout='row'>
    </md-toolbar>
    <div>
      <p>This is the receiver of outbound messages</p>
      <div>
        <strong>Batch</strong>
        <table class="obj-table">
          <tr>
            <td>BATCH</td>
            <td>REPLY LIMIT</td>
            <td>CONVO COUNT</td>
          </tr>
          <tr ng-repeat="batch in conversations.batch">
            <td><%batch.id%></td>
            <td><%batch.reply_limit%></td>
            <td><%batch.convo_count%></td
          </tr>
        </table>
      </div><br><br>
      <div>
        <strong>Conversations</strong>
        <table class="obj-table">
          <tr>
            <td>CONVERSATION ID</td>
            <td>SERVICE</td>
            <td>BATCH ID</td>
            <td>REPLY SENT</td>
            <td>STATUS</td>
          </tr>
          <tr ng-repeat="conversation in conversations.list">
            <td><%conversation.conversation%></td>
            <td><%conversation.service%></td>
            <td><%conversation.batch_id%></td>
            <!-- <td><%conversation.status == 'limit reached' ? conversation.counter - 1 : conversation.counter%></td> -->
            <td><%conversation.counter%></td>
            <td><%conversation.status%></td>
          </tr>
        </table>
      </div>
    </div>
  </body>
    <script type="text/javascript" src="http://localhost:3000/socket.io/socket.io.js"></script>
    <script type="text/javascript" src="{{secure_asset('/')}}/js/angular/angular.min.js"></script>
    <script type="text/javascript" src="{{secure_asset('/')}}/bower_components/angular-aria/angular-aria.min.js"></script>
    <script type="text/javascript" src="{{secure_asset('/')}}/bower_components/angular-material/angular-material.min.js"></script>
    <script type="text/javascript" src="{{secure_asset('/')}}/bower_components/angular-messages/angular-messages.min.js"></script>
    <script type="text/javascript" src="{{secure_asset('/')}}/bower_components/angular-animate/angular-animate.min.js"></script>
    <script type="text/javascript" src="{{secure_asset('/')}}/js/angular/services/angular-socket.js"></script>

    <script type="text/javascript" src="{{secure_asset('/')}}/js/res/moment.min.js"></script>
    <script type="text/javascript" src="{{secure_asset('/')}}/js/angular/directives/angular-moment.min.js"></script>

    <script type="text/javascript" src="{{secure_asset('/')}}/js/angular/mock/outbound-mock.js"></script>
  </html>
