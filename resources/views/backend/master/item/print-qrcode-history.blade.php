<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print QR Code Item Untuk History</title>
    {{-- <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/app.css')}}" />
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/all.css')}}" /> --}}
    <style>
    body { margin: 40px; } .wrapper { display: grid; grid-template-columns: 250px 250px 250px; grid-gap: 10px; background-color:
    #fff; color: #444; } .box { background-color: #fff; text-align: center; color: #000; border-radius: 5px; padding: 10px; font-size: 150%; border:1px solid #000; }
    </style>
</head>
<body onload="window.print()">
    <div class="wrapper">
        @foreach ($items as $item)
        <div class="box">
            
            <?php
                $url = url('history/report/item/'.$item->id);
                $logo = '\logo.png';
            ?>
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge($logo)->size(200)->generate($url)) !!} ">
            <?php 
                $name = $item->name;
                if (strlen($name) > 16) $name = substr($name, 0, 16);
            ?>
            <p>{{$name}}</p>
        </div>
        @endforeach
    </div>
</body>
</html>