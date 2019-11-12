@extends('layouts.app')

@section('content')
    <div class="container" id="table_data">
        @include('holland_tests.data_test')
    </div>
@endsection
@section('script')
{{--    <script type="text/javascript">--}}
{{--        $(window).on('hashchange', function() {--}}
{{--            if (window.location.hash) {--}}
{{--                var page = window.location.hash.replace('#', '');--}}
{{--                if (page == Number.NaN || page <= 0) {--}}
{{--                    return false;--}}
{{--                }   else    {--}}
{{--                    getData(page);--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}

{{--        $(document).ready(function()    {--}}
{{--            $(document).on('click', '.pagination a',function(event) {--}}
{{--                event.preventDefault();--}}
{{--                $('li').removeClass('active');--}}
{{--                $(this).parent('li').addClass('active');--}}
{{--                var myurl = $(this).attr('href');--}}
{{--                var page=$(this).attr('href').split('page=')[1];--}}
{{--                postData(page);--}}
{{--                getData(page);--}}
{{--            });--}}
{{--        });--}}

{{--        function postData(page) {--}}
{{--            var data_test = pushIntoArray();--}}

{{--            $.ajaxSetup({--}}
{{--                header: {--}}
{{--                    'X-CSRF-TOKEN': "{{ csrf_field() }}"--}}
{{--                }--}}
{{--            });--}}

{{--            $.ajax({--}}
{{--                type: 'POST',--}}
{{--                url: '/start_test',--}}
{{--                data: data_test,--}}
{{--                success: function(data) {--}}

{{--                }--}}
{{--            })--}}
{{--        }--}}

{{--        function getData(page) {--}}
{{--            $.ajax(--}}
{{--                {--}}
{{--                    url: '/start_test?page=' + page,--}}
{{--                    type: "GET"--}}
{{--                }).done(function(data){--}}
{{--                $("#tag_container").html(data);--}}
{{--                location.hash = page;--}}
{{--            }).fail(function(jqXHR, ajaxOptions, thrownError)   {--}}
{{--                alert('No response from server');--}}
{{--            });--}}
{{--        }--}}

{{--    </script>--}}
<script>

    function pushIntoArray(page) {
        let data = [];
        let question;
        let option;
        var length = 6;
        for (let i = 0; i < length; i++) {
            question = $('input[name=questions_id\\['+ page +'\\]\\['+ i +'\\]]').val();
            option = $('input[name=options_id\\['+ question +'\\]]:checked').val();
            data.push({questions_id: question, options_id: option});
            alert(question);
        }
        return data;
    }

    $(document).ready(function(){

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            //alert($('input[name=questions_id\\['+ page +'\\]\\['+ 1 +'\\]]').val());
            fetch_data(page);
        });

        function fetch_data(page)
        {
            $.ajax({
                url: '/start_test?page=' + page,
                type: 'GET',
                // data: {
                //   'data_test': pushIntoArray()
                // },
                success:function(data)
                {
                    $('#table_data').html(data);
                }
            });
        }

    });
</script>
@endsection

