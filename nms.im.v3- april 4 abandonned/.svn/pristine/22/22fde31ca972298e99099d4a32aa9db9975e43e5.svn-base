@extends('layouts.mockTemplate')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/permissions.css') }}">
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"><h4>Permissions</h4></div>
        <div class="panel-body">
            <div id="overview-wrapper" style="width: 40%;">
                <table id="overview" class="table">
                    @foreach ($permissions as $permission_group_name => $permission_group)
                        <tr>
                            <th></th>
                            <th colspan="{{ count($groups) }}">{{ $permission_group_name }}</th>
                        </tr>

                        @foreach ($permission_group as $key => $permission_item)
                            @if($key % 5 == 0)
                            <tr>
                                <td class="group-header"></td>
                                @foreach($groups as $id => $group_name)
                                <td class="group-header">{{ $group_name }}</td>
                                @endforeach
                            </tr>
                            @endif

                            <tr>
                                <td>{{ str_replace("_", " ", $permission_item) }}</td>
                                @foreach($groups as $id => $group_name)
                                <td class="changeable modifier {{ \Redis::sismember('role_permissions:'.$id, $permission_item)?"green":"red" }}">
                                    <input type="hidden" name="group_id" value="{{ $id }}" />
                                    <input type="hidden" name="permission" value="{{ $permission_item }}" />
                                    <input type="hidden" name="set_flag" value="{{ \Redis::sismember('role_permissions:'.$id, $permission_item)?"red":"green" }}" />
                                </td>
                                @endforeach
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="{{ count($groups) + 1 }}" style="border-bottom: 2px solid black;"></td>
                        </tr>
                    @endforeach

                </table>
            </div>
         </div>

         <div id="test_result">
            <textarea id="test_result_area" readonly="readonly" rows="15" cols="45"></textarea>
        </div>
    </div>
@stop

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#test_result").css("top","32px");
    $("#test_result").css("left",$("#overview-wrapper").width() + 45 + "px");
    $("#test_result").css("position","absolute");

    $(document).bind("scroll", function(){
        $("#test_result").animate({
          top       : $(document).scrollTop() + 32 + "px"
        },{ 
          duration  : 100
        });

    });

    $("td.changeable.modifier").click(function(){
        button_click_anim($(this));
    });

    function button_click_anim(button){
        $(button).animate({
          borderWidth:"5px"
        }, "100", function(){
            var group_id = $(button).children("[name=group_id]");
            var permission = $(button).children("[name=permission]");
            var set_flag = $(button).children("[name=set_flag]");

            var values = {
                "group_id" : group_id.val(),
                "permission" : permission.val(),
                "set_flag" : set_flag.val()
            };

            $.ajax(
            {
                type    : 'POST',
                url     : '{{ asset("mock/dev/permissions/process_overview") }}',
                data    : values,
                // dataType: 'text',
                success : function(data)
                {
                  $("#test_result_area").val($("#test_result_area").val() + data + "\n");

                  var textArea = document.getElementById('test_result_area');
                  textArea.scrollTop = textArea.scrollHeight;
                }
            });

            toggle_modifier($(button));

            $(button).animate({ borderWidth:"1px" }, "5000");
        });
    }

    function toggle_modifier(modifier){
        var set_flag = modifier.children("[name=set_flag]");

        modifier.toggleClass("green red");
        if(set_flag.val() == "red")
          set_flag.val("green");
        else
          set_flag.val("red");
    }
});
</script>
 
@stop