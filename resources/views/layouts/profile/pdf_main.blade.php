<table  border="0" cellspacing="0" cellpadding="0" class="main">
    <thead class="{{$breeder->css['color']}}">
        <tr>
            <th>
                <table  border="0" cellpadding="2">
                    <tr>
                        <td width="10%">

                            @if($breeder->image && isset($breeder->image['name']) && $breeder->image['name'])
                                <img src="{{$breeder->image['path']}}" class="img-circle">
                            @else
                                <img src="{{$directory . 'media/kits/default_small.jpg'}}" class="img-circle">
                            @endif
                        </td>
                        <td>{{$breeder->name}}<br /><span>{{$breeder->tattoo}}</span></td>
                        <td width="1%"><img src="{{$directory .  'img/' . $breeder->css['img']}}" width="20"></td>
                    </tr>

                </table>
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>
                <table class="bordered" cellspacing=5" cellpadding="2" border="0">
                    <tr>
                        <td width="50%" class="border-gray-right border-gray-bottom"><strong>Dob</strong>: {{$breeder->aquired}}</td>
                        <td width="50%" class="border-gray-bottom"><strong>Breed</strong>: {{$breeder->breed}}</td>
                    </tr>
                    <tr>
                        <td class="border-gray-right border-gray-bottom"><strong>Color</strong>: {{$breeder->color}}</td>
                        <td class="border-gray-bottom "><strong>Weight</strong>: {{$breeder->weight_slug}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">{{$breeder->notes}}</td>
                    </tr>

                </table>



            </td>
        </tr>
    </tbody>

</table>