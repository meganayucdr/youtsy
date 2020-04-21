@extends('layouts.app')

@section('content')
    <div class="container" id="table_data">
        @include('holland_tests.data_test')
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            //alert($('input[name=questions_id\\[1\\]\\[0\\]]').val());
            var currentPage = page-1;
            if (currentPage < page) {
                // alert("wew");
                var dataTest = pushIntoArray(currentPage);
            }
            // alert(currentPage);
            if (dataTest != null || page < currentPage) {
                fetch_data(page, dataTest, currentPage);
                scrollTop();
            }
            else {
                scrollTop();
            }
        });

        function scrollTop()    {
            jQuery('.alert-danger').show();
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }

        function pushIntoArray(page) {
            let data = [];
            let question;
            let option;
            var length = 6;
            for (let i = 0; i < length; i++) {
                question = $('input[name=questions_id\\['+ page +'\\]\\['+ i +'\\]]').val();
                option = $('input[name=options_id\\['+ question +'\\]]:checked').val();
                if ( option == null ) {
                    return null;
                }
                data.push({questions_id: question, options_id: option});
                //alert(question);
            }
            // alert(data);
            return data;
        }

        function fetch_data(page, dataTest, currentPage)
        {
            //let test = pushIntoArray(page);
            $.ajax({
                url: '/start_test?page=' + page,
                type: 'GET',
                data: {
                    'data_test': dataTest,
                    'current_page': currentPage
                },
                success:function(data)
                {
                    $('#table_data').html(data);
                }
            });
        }

    });
</script>
@endsection

