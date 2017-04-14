<table border="0" cellspacing="1" cellpadding="1" class="logo">
    <tr>
        <td width="1%">
        @if($pedigree['g1']->user->pedigree_logo)
            <img src="{{$directory . 'media/pedigree/' . $pedigree['g1']->user->pedigree_logo}}" height="{{isset($isPublic) ? '100px' : '50px'}}">
        @endif

        </td>
        <td><h1 class="text-center">Hutch Pedigree</h1></td>
    </tr>
</table>


<table border="0" cellspacing="1" cellpadding="2">

    @for($row=1;$row<=15;$row++)
    <tr>
        @for($col=1;$col<=7;$col++)

            <!-- G1 -->
            @if($col==1)
                @if($row==8)
                    <td width="40%">@include('layouts.profile.pdf_main', ['breeder' => $pedigree['g1'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==1)
                    <td style="padding-left: 0px;"><small>{!! nl2br($pedigree['g1']->user->pedigree_rabbitry_information) !!}</small></td>
                @else
                    <td>&nbsp;</td>
                @endif
            @endif

            <!-- G2 -->
            @if($col==2)
                @if($row==4 && isset($pedigree['g2']['f1']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g2']['f1'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==12 && isset($pedigree['g2']['m1']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g2']['m1'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @else
                    <td>&nbsp;</td>
                @endif
            @endif


            <!-- G3 -->
            @if($col==3)
                @if($row==2 && isset($pedigree['g3']['f1']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g3']['f1'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==6  && isset($pedigree['g3']['m1']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g3']['m1'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==10  && isset($pedigree['g3']['f2']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g3']['f2'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==14 && isset($pedigree['g3']['m2']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g3']['m2'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @else
                    <td>&nbsp;</td>
                @endif
            @endif


            <!-- G4 -->
            @if($col==3)
                @if($row==1 && isset($pedigree['g4']['f1']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g4']['f1'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==3 && isset($pedigree['g4']['m1']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g4']['m1'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==5 && isset($pedigree['g4']['f2']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g4']['f2'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==7 && isset($pedigree['g4']['m2']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g4']['m2'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==9 && isset($pedigree['g4']['f3']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g4']['f3'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==11 && isset($pedigree['g4']['m3']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g4']['m3'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==13 && isset($pedigree['g4']['f4']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g4']['f4'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @elseif($row==15 && isset($pedigree['g4']['m4']))
                    <td>@include('layouts.profile.pdf_other', ['breeder' => $pedigree['g4']['m4'], 'directory'=>$directory,'isPublic'=>isset($isPublic) ? $isPublic : false])</td>
                @else
                    <td>&nbsp;</td>
                @endif
            @endif

        @endfor
    </tr>
    @endfor

</table>
@if(!isset($isPublic))
<table border="0" cellspacing="0" cellpadding="3" class="footer">
    <tr>
        <td width="1%">
            <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(100)->generate( url('pedigree/' . $pedigree['g1']->token)  )) }} ">
        </td>
        <td>Verify this pedigree online by scanning the QR code on the left or by visiting {{url('pedigree/' . $pedigree['g1']->token)}}</td>
    </tr>
</table>
@endif

<style>
body{
    font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
    font-size: 12px;
}

.bg-aqua {
    background-color: #00c0ef !important;
    color: white;
}

.bg-aqua-dark{
    background-color: #3c8dbc !important;
    color: white;
}

.bg-maroon {
    background-color: #D81B60 !important;
    color: white;
}

.bg-maroon-dark {
    background-color: #AD164D !important;
    color: white;
}

.img-circle {
    border-radius: 95% !important;
    border: 2px solid;
    width: 35px;
}

table{
    width: 100%;
}

table.bordered{
    border: 1px solid #f4f4f4;
}

.border-gray-right{
    border-right: 1px solid #f4f4f4;
}

.border-gray-bottom{
    border-bottom: 1px solid #f4f4f4;
}

@if(!isset($isPublic))
table.other td{
    font-size: 10px;

}
@endif

.text-center{
    text-align: center;
}

table.logo{
    color: gray;
    border-bottom: .5px solid #a4a4a4;
}

table.footer{
    color: gray;
    border-top: .5px solid #a4a4a4;
}

.bg-aqua td,
.bg-maroon td{
    color: white;
}
</style>