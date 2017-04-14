{{-- @if($breeder->name) --}}
<table  border="0" cellspacing="0" cellpadding="2" class="other">
    <tr>
        <td class="{{$breeder->css['color']}}-dark" width="40px">
            @if($breeder->image && isset($breeder->image['name']) && $breeder->image['name'])
            <img src="{{$directory . 'media/pedigree/' . $breeder->image['name']}}" class="img-circle">
            @else
            <img src="{{$directory . 'media/kits/default_small.jpg'}}" class="img-circle">
            @endif
        </td>

        <td class="{{$breeder->css['color']}}">
            <table border="0" cellpadding="{{$isPublic ? 2 : 0}}" cellspacing="0">
                <tr>
                    <td>{{$breeder->name}}: {{$breeder->custom_id}} </td>
                    <td width="1%"><img src="{{$directory .  'img/' . $breeder->css['img']}}" width="13"></td>
                </tr>
                <tr>
                    <td><small>{{$breeder->day_of_birth}}</small></td>
                    <td><small>{{$breeder->breed}}</small></td>
                </tr>
                <tr>
                    <td colspan="2"><small>{{$breeder->notes}}</small></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
{{-- @endif --}}