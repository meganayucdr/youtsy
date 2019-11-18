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
            var currentPage = '{{ $questions->currentPage() }}';
            var dataTest = pushIntoArray(currentPage);
            fetch_data(page, dataTest);
        });

        function pushIntoArray(page) {
            let data = [];
            let question;
            let option;
            var length = 6;
            for (let i = 0; i < length; i++) {
                question = $('input[name=questions_id\\['+ page +'\\]\\['+ i +'\\]]').val();
                option = $('input[name=options_id\\['+ question +'\\]]:checked').val();
                data.push({questions_id: question, options_id: option});
                //alert(question);
            }
            return data;
        }

        function fetch_data(page, dataTest)
        {
            //let test = pushIntoArray(page);
            $.ajax({
                url: '/start_test?page=' + page,
                type: 'GET',
                data: {
                  'data_test': dataTest
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

