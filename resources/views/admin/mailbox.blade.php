@extends('layouts.master')

@section('classes_body')
    hold-transition sidebar-mini
@endsection

@section('navbar')
    @include('admin.navbar')
@endsection

@section('sidebar')
    @include('admin.sidebarAdmin', ['page'=>'mailboxIndex'])
@endsection

@section('contentHeader')
    @include('admin.contentheader' , ['header' => '' ])
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('adminlte_js')
    <script>
        $(document).ready(function(){
            fetch_user_chat_id = "{{Cookie::get('user_chat_id')??-1}}"; chat_timestamp = '';
            fetch_user();
            fetch_user_chat(fetch_user_chat_id);
            setInterval(function(){
                //update_last_activity();
                fetch_user();
                fetch_user_chat(fetch_user_chat_id);
                //update_chat_history_data();
                //fetch_group_chat_history();
            }, 5000);

            function fetch_user()
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ url('admin/mailbox/fetch_user') }}",
                    method:"POST",
                    success:function(data){
                        $('#user_details').html(data);
                        $(".user_chat").on('click',function () {
                            chat_timestamp='';
                            $("#user_details li").removeClass("active");
                            fetch_user_chat_id = this.attributes.rel.value;
                            $('#direct-chat-messages').html('');
                            fetch_user_chat(fetch_user_chat_id);
                            $(this).addClass("active");
                        })
                    }
                })
            }
            function fetch_user_chat(id)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data : { timestamp : chat_timestamp } ,
                    url:"{{ url('admin/mailbox/fetch_user_chat') }}/"+id,
                    method:"POST",
                    success:function(data){
                        console.log(data[0])
                        chat_timestamp = data[0];
                        $('#direct-chat-messages').append(data[1]);
                        $('#direct-chat-messages').scrollTop($('#direct-chat-messages')[0].scrollHeight);
                    }
                })
            }
            $('#chat_form').submit(function(e) {
                e.preventDefault();
                console.log('asdf');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ url('admin/mailbox/send_chat') }} ",
                    data: { timestamp : chat_timestamp , msg : $("#chat_box_message").val()},
                    method:"POST",
                    success:function(data){
                        $("#chat_box_message").val('')
                        chat_timestamp = data[0];
                        $('#direct-chat-messages').append(data[1]);
                        $('#direct-chat-messages').scrollTop($('#direct-chat-messages')[0].scrollHeight);
                    }
                });
            });

        })
    </script>
@endsection

@section('content')
    <div class="content">

        <div class="col-md-12">
            <div class="card direct-chat direct-chat-warning">
                <div class="card-header">
                    <h3 class="card-title">گفتگو </h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">

                    <ul id="user_details" class="col-4 col-sm-3 list-group list-group-flush " style="height: 300px; overflow-y: auto;">

                    </ul>
                    <!-- Conversations are loaded here -->
                    <div id = "direct-chat-messages" class="col direct-chat-messages mr-2">

                    </div>
                    <!--/.direct-chat-messages-->
                    <!-- /.direct-chat-pane -->
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <form action="#" method="post" id="chat_form">
                        @csrf
                        <div class="input-group">
                            <input type="text" id="chat_box_message" name="message" placeholder="نوشتن متن ..." class="form-control">
                            <span class="input-group-append">
                          <button type="submit" class="btn btn-primary">ارسال</button>
                        </span>
                        </div>
                    </form>
                </div>
                <!-- /.card-footer-->
            </div>
        </div>

    </div>

@endsection

