@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="align-center pt-5 pb-2">
            <h1 class="text-center">Ini hasil tes kamu!</h1>
            <p class="text-center">Berdasarkan tes yang sudah kamu lakukan, seperti ini lah hasilnya!</p>
        </div>

        <div class="row">
            <div class="col-md-6 offset-md-3 ">
                <canvas id="myChart"></canvas>
            </div>
            <div class="col-md-6 offset-md-3 text-justify">
                Grafik di atas menunjukkan skor untuk masing-masing kode Holland. Tiga kode Holland dengan skor
                tertinggi adalah kode hollandmu, yaitu:
                @php
                $i = 0;
                @endphp
                @foreach($holland_code_information as $data)
                    @php
                        $i++;
                    @endphp
                    {{$data->name}} ({{$data->code}}){{ $i == 3 ? '.' : ',' }}
                @endforeach
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-7">
                <div class="row">
                    @php
                        $i = 0;
                    @endphp
                    @foreach($holland_code_information as $data)
                        @php
                            $i++;
                        @endphp
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ $data->name }} ({{$data->code}})</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $data->explanation }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3 text-md-center text-center">
                <h5 class="font-weight-bold">Rekomendasi karir untuk Anda:</h5>
                <ul class="list-inline">
                @foreach($careers as $career)
                    <li class="list-inline-item"> {{ $career }} </li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <script>
        var data_set = [];
        var codes = [];
        var url = "{{ url('get_result/' . $id ) }}";

        $(document).ready(function () {
            $.get(url, function (response) {
                response[0].forEach(function (res) {
                    this.codes.push(res.name);
                });
                response[1].forEach(function (res) {
                    this.data_set.push(res.percentage);
                });
            var ctx = document.getElementById("myChart");
            var myChart = new Chart(ctx, {
                type: "horizontalBar",
                data: {
                    labels: codes,
                    datasets: [
                        {
                            label: 'Hasil',
                            data: data_set,
                            backgroundColor: [
                                "rgba(255, 99, 132, 1)",
                                "rgba(54, 162, 235, 1)",
                                "rgba(255, 206, 86, 1)",
                                "rgba(75, 192, 192, 1)",
                                "rgba(153, 102, 255, 1)",
                                "rgba(54, 102, 143, 1)"
                            ],
                            borderColor: [
                                "rgba(255, 99, 132, 1)",
                                "rgba(54, 162, 235, 1)",
                                "rgba(255, 206, 86, 1)",
                                "rgba(75, 192, 192, 1)",
                                "rgba(153, 102, 255, 1)",
                                "rgba(54, 102, 143, 1)"
                            ],
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            });
        });
    </script>
@endsection
