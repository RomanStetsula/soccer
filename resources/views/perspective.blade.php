<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
<table >
    <tbody>

        <tr @foreach($records as $record)>
            <td>
                <table class="table table-bordered tab-stripped">
                    <tbody>
                        <tr>
                            <td><a href = '{{$record['real']->url}}' target = '_blank'>{{$record['real']->firstname}} {{$record['real']->lastname}}</a></td>
                            <td>{{$record['real']->team}}</td>
                            <td>{{$record['real']->birth_date}}</td>
                            <td>{{$record['real']->position}}</td>
                            <td>{{$record['real']->nationality}}</td>
                            <td>{{$record['real']->leagve_base_talent}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table class="table table-bordered tab-stripped">
                    <tbody>
                        <tr>
                            <td><a href = '{{$record['virtual']->url}}' target = '_blank'>{{$record['virtual']->firstname}} {{$record['virtual']->lastname}}</a></td>
                            <td>{{$record['virtual']->team}}</td>
                            <td>{{$record['virtual']->birth_date}}</td>
                            <td>{{$record['virtual']->position}}</td>
                            <td>{{$record['virtual']->nationality}}</td>
                            <td>{{$record['virtual']->skill}}</td>
                            <td>{{$record['virtual']->talent}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr @endforeach>
    </tbody>
</table>