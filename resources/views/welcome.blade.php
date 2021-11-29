<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Laravel</title>
    </head>
    <body class="antialiased">
        <div id="app"></div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody id='people'>

            </tbody>
        </table>

        <script src="js/app.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
        <script type="text/javascript">
        
            window.Echo.channel('DemoChannel')
            .listen('WebsocketDemoEvent',(e)=>{
                console.log(e.data)
                let data = JSON.parse(e.data);
                console.log('data',data);
                var html = '';
                for (let index = 0; index < data.length; index++) {
                    let element = data[index];
                    // console.log('element', element);
                    html = html + `
                        <tr>
                        <td>` + element.name + `</td>
                        <td>` + element.email + `</td>
                        </tr>
                    `;
                }
                // console.log('html', html);
                $('#people').html(html);

                // userDataSet(e);
            });

            function userDataSet(e){
                console.log('userDataSet', e)

                $.ajax({
                    type:"POST",
                    dataType: "json",
                    url:"{{url('/api/test-user-list')}}",
                    success:function(data)
                    {
                        // alert('Get Success');
                        // console.log(data);
                        var html = '';
                        for (let index = 0; index < data.data.length; index++) {
                            let element = data.data[index];
                            // console.log('element', element);
                            html = html + `
                             <tr>
                                <td>` + element.name + `</td>
                                <td>` + element.email + `</td>
                             </tr>
                            `;
                        }
                        // console.log('html', html);
                        $('#people').html(html);
                    }
                });
            }

        </script>

    </body>
</html>
