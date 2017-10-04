@extends('base')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="content-title col-xs-6">
            @if (isset($league->id))
                <h1>Редагування ліги</h1>
            @else
                <h1>Стверення ліги</h1>
            @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class = "col-xs-12 errors">
            {{  Html::ul($errors->all()) }}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
           @if (isset($league->id))
             {{ Form::model($league, ['route'=>['league.update', $league->id], 'method'=>'PUT']) }}
           @else
             {{ Form::open(['url' => 'league']) }}
           @endif
           <div class="form-group">
               {{ Form::label('name', 'Назва ліги', ['class' => 'control-label']) }}
               {{ Form::text('name', old('name'), ['class'=>"form-control"]) }}
           </div>
       </div>
       <div class="col-xs-6">
           <div class="form-group">
               {{ Form::label('url', 'Силка', ['class' => 'control-label']) }}
               {{ Form::text('url', old('url'), ['class'=>"form-control"]) }}
           </div>
       </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                {{ Form::label('country', 'Країна', ['class' => 'control-label']) }}
                {{ Form::text('country', old('country'), ['class'=>"form-control"]) }}
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
               {{ Form::label('tier', 'Ранг ліги', ['class' => 'control-label']) }}
               {{ Form::number('tier', old('tier'), ['class'=>"form-control"]) }}
            </div>
        </div>
          <div class="col-xs-3">
           <div class="form-group league-checkbox">
           {{ Form::label('base_talent', 'Базовий талант', ['class' => 'control-label']) }}
           {{ Form::number('base_talent',old('base_talent'), ['class'=>"form-control"]) }}
           </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-1">
            <div class="cancel">
                {{ Form::reset('Відміна', ['class'=>"btn btn-xs btn-warning"]) }}
            </div>
        </div>
        <div class="col-xs-1">
            <div class="save">
                {{ Form::submit('Зберегти', ['class'=>"btn btn-small alert-success btn-xs"]) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

