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
            <td>NAME</td>
            <td>Talent</td>
            <td>Pos</td>
            <td>Skill</td>
            <td>Age</td>
            <td>Team</td>
            <td>Date_of_transfer</td>
            <td>Offer</td>
        </tr>
        </thead>
        <tbody id="tbody">
        <tr @foreach($players as $player) class="rm">
            <td>{{$loop->index+1}}</td>
            <td><a href = '{{$player->url}}' target = '_blank'>{{$player->firstname}} {{$player->lastname}}</a></td>
            <td>{{$player->talent}}</td>
            <td>{{$player->position}}</td>
            <td>{{$player->skill}}</td>
            <td>{{$player->age}}</td>
            <td>{{$player->team}}</td>
            <td>{{$player->transfer_date}}</td>
            <td><a href = '{{$player->offers}}' target = '_blank'>{{$player->transfer_value}}</a></td>
        </tr @endforeach>
        </tbody>
    </table>

@stop