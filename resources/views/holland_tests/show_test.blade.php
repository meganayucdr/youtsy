@extends('layouts.app')

@section('content')
    <div class="container" id="tag_container">
        @include('holland_tests.data_test')
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                }   else    {
                    getData(page);
                }
            }
        });

        $(document).ready(function()    {
            $(document).on('click', '.pagination a',function(event) {
                event.preventDefault();
                $('li').removeClass('active');
                $(this).parent('li').addClass('active');
                var myurl = $(this).attr('href');
                var page=$(this).attr('href').split('page=')[1];

                getData(page);
            });
        });

        function postData(page) {
            var questions = $("input[name = questions_id]").val();
            var options = $("input[name  = options_id]").val();

            var data_test = [];
            data_test.push({questions_id: questions}, {options_id: options});
            $.ajax({
                type: 'post',
                url: '/start_test?page' + page,
                data: {data_test: data_test}
            });
        }

        function getData(page) {
            $.ajax(
                {
                    url: '/start_test?page=' + page,
                    type: "get",
                    datatype: "html"
                }).done(function(data){
                $("#tag_container").empty().html(data);
                location.hash = page;
            }).fail(function(jqXHR, ajaxOptions, thrownError)   {
                alert('No response from server');
            });
        }

    </script>
@endsection

