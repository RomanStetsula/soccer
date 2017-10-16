@extends('welcome')

@section('content')
    <style>
        .table-bordered td, .table-bordered th {
            font-weight: bold;
        }
        tbody {
            color: #3c3c3c;
        }
        #tbody tr td{
            border: 1px solid #a0a0a0;
        }
        body{
            padding-top: 30px;
        }
        a:not([href]):not([tabindex]){
            color: #ff2d27;
            cursor: pointer;
            padding: 5px 10px;
            background-color: #ffe3b4;
            border-radius: 50%;
            margin-left: 14px;
        }
        a:not([href]):not([tabindex]):hover{
            color: #FFFFFF;
            background-color:#ff2d27;
        }
    </style>
    <table id="players_table" class="table table-bordered table-striped">
        <thead class="table-inverse">
            <tr>
                <td>#</td>
                <td>Tr_NAME</td>
                <td>Sl_NAME</td>
                <td>Tr_T</td>
                <td>Sl_T</td>
                <td>Tr_POS</td>
                <td>Sl_POS</td>
                <td>Skill</td>
                <td>Age</td>
                <td>Nationality</td>
                <td>Team</td>
                <td>tr_VALUE</td>
                <td>Дата</td>
                <td>Трансфер</td>
            </tr>
        </thead>
        <tbody id="tbody">
            <tr @foreach($records as $record) class="rm">
                <td>{{$loop->index+1}}<a class="delete" data-id={{$record['id']}}>x</a></td>
                <td><a href = '{{$record['transfermarket']->url}}' target = '_blank'>{{$record['transfermarket']->firstname}} {{$record['transfermarket']->lastname}}</a></td>
                <td><a href = '{{$record['soccerlife']->url}}' target = '_blank'>{{$record['soccerlife']->firstname}} {{$record['soccerlife']->lastname}}</a></td>
                <td>{{$record['transfermarket']->talent}}</td>
                <td>{{$record['soccerlife']->talent}}</td>
                <td>{{$record['transfermarket']->position}}</td>
                <td>{{$record['soccerlife']->position}}</td>
                <td>{{$record['soccerlife']->skill}}</td>
                <td>{{$record['transfermarket']->age}}</td>
                <td>{{$record['transfermarket']->nationality}}</td>
                <td>{{$record['transfermarket']->team}}</td>
                <td>{{$record['transfermarket']->market_value}}</td>
                <td>{{$record['soccerlife']->transfer_date}}</td>
                <td><a href = '{{$record['soccerlife']->offers}}' target = '_blank'>{{$record['soccerlife']->transfer_value}}</a></td>
            </tr @endforeach>
        </tbody>
    </table>

@stop