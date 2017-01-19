<!DOCTYPE html>
<html>
<head>
    <title>Laravel 5 - Twitter API</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Laravel 5.3 - Twitter </h2>
    <h4>Вывод твитов из аккаунта <a href="https://twitter.com/stas7271" target="_blank">stas7271</a></h4>

    <form method="POST" action="{{ url('tweet') }}" enctype="multipart/form-data">

        {{ csrf_field() }}

        @if(count($errors))
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <br/>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label>Текст:</label>
            <textarea class="form-control" name="tweet"></textarea>
        </div>
        <div class="form-group">
            <label>Добавить изображение:</label>
            <input type="file" name="images[]" multiple class="form-control">
        </div>
        <div class="form-group">
            <button class="btn btn-success">Добавить твит</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th width="50px">No</th>
            <th>Twitter Id</th>
            <th>Message</th>
            <th>Images</th>
            <th>Favorite</th>
            <th>Retweet</th>
        </tr>
        </thead>
        <tbody id="test">
        @if(!empty($data))
            @foreach($data as $key => $value)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $value['id'] }}</td>
                    <td>{{ $value['text'] }}</td>
                    <td>
                        @if(!empty($value['extended_entities']['media']))
                            @foreach($value['extended_entities']['media'] as $v)
                                <img src="{{ $v['media_url_https'] }}" style="width:100px;">
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $value['favorite_count'] }}</td>
                    <td>{{ $value['retweet_count'] }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">Connect...</td>
            </tr>
@endif
        </tbody>
    </table>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>

    $(document).ready(function () {
        var timerId = setInterval(function() {
            token = $("input[name=_token]").val()
           $.ajax({
               url:'tweet/get',
               type:'POST',
               data: { _token: token},
               success: function (data) {
                    var str = '';
                    index = 0;
                   $.each(JSON.parse(data), function(){
                       str += '<tr>';
                       str += '<td>' + index +'</td>';
                       str += '<td>' + this.id + '</td>';
                       str += '<td>' + this.text + '</td>';


                       if(typeof(this.extended_entities) !== "undefined"){
                           $.each(this.extended_entities.media, function(){
                               str += '<td>' + "<img src='" + this.media_url_https + "' style='width:100px;'>" + '</td>';
                           });
                       }else{
                           str += "<td></td>";
                       }

                       str += '<td>' + this.favorite_count + '</td>';
                       str += '<td>' + this.retweet_count + '</td>';
                       str += '</tr>';
                       index++;
                   })
                   $('#test').html(str);
               }
               
           })
        }, 2000);
    })
</script>
</body>
</html>