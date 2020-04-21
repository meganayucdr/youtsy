@extends('layouts.app')

@section('content-title', 'Histori Tes')

@section('content')
    <div class="container">
        <h1 class="text-center pt-5 pb-2">Histori Tes</h1>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tanggal Tes</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach($holland_test as $data)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <th scope="row">{{ $i }}</th>
                            <td>{{ \Carbon\Carbon::parse($data->created_at)->formatLocalized('%d %B %Y') }}</td>
                            <td><a href="{{ route('holland_test.show_report', $data->id) }}" class="btn btn-yellow">Lihat Hasil</a></td>
                        </tr>
                    @endforeach
                </table>
                {{ $holland_test->links() }}
            </div>
        </div>
    </div>
@endsection
