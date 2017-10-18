@extends('welcome')

@section('content')
<div class="container">
    <div class="row">
        <div class="add-btn col-xs-12">
            <a class="btn btn-sm alert-success btn-xs"  href="{{route('league.create')}}">Додати нову лігу</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="league-table table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Країна</th>
                        <th>Рівень</th>
                        <th>Базовий талант</th>
                        <th>Силка</th>
                        <th>Дата парсингу</th>
                        <th>Керування</th>
                    </tr>
                </thead>
                <tbody>
                    <tr @foreach($leagues as $league)>
                        <td class="league-id">{{ $league->id }}</td>
                        <td>{{ $league->name }}</td>
                        <td>{{ $league->country}}</td>
                        <td>{{ $league->tier}}</td>
                        <td>{{ $league->base_talent}}</td>
                        <td><a href="{{ $league->url}}" target="_blank">На трансфермаркеті</a></td>
                        <td>{{ $league->date_of_parsing}}</td>
                        <td>
                            <a class="btn btn-sm btn-success" title="Спарсити" href="{{ URL::to('parseTM/'.$league->id)}}">Спарсити</a>
                            <a class="btn btn-sm btn-warning" href="{{route('league.edit', $league->id)}}">Редагувати</a>
                            {!! Form::open([
                                        'method'=>'delete',
                                        'route'=>['league.destroy',$league->id],
                                        'style'=>'display:inline',
                                        'onsubmit'=>'return confirm("Are you sure you want to delete ?");'
                                        ]) !!}

                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i>Видалити
                            </button>

                            {!!Form::close()!!}
                        </td>
                    </tr @endforeach>
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination-sm">
    {{ $leagues->links() }}
    </div>
</div>
    <style>
        .league-table td, .league-table th{
            padding: 2px 10px;
            font-size: 12px;
        }
        .btn-sm {
            padding: 1px;
            font-size: 12px;
        }
        .pagination-sm{
            padding: 0 500px;
        }
        .pagination-sm li{
            padding: 0 4px;
        }
        .add-btn{
            padding:7px 15px;
        }
    </style>

@endsection
