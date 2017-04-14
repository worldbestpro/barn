@extends('layouts.public')

@section('content')

<section class="content">

<table border="0" cellspacing="1" cellpadding="1" class="logo">
    <tr>
        <td width="1%">
        @if($pedigree['g1']->user->pedigree_logo)
            <img src="{{ asset("") . 'media/pedigree/' . $pedigree['g1']->user->pedigree_logo}}" height="100px">
        @endif

        </td>
        <td><h1 class="text-center">Hutch Pedigree</h1></td>
    </tr>
</table>


<br /><br />



          <!-- Your Page Content Here -->

          <div class="row">


  <section class="col-lg-12">
  <div class="box box-solid box-primary " style="">
                <div class="box-header">
                  <i class="fa fa-share-alt"></i>
                  <h3 class="box-title"><!--Pedigree--></h3>

                </div><!-- /.box-header -->
                <div class="box-body">





  <div class="row row-horizon pedigree">

                                <div class="col-xs-13 col-sm-6 col-md-5 col-lg-4">

                                <div style="padding-left: 10px;"><small>{!! nl2br($pedigree['g1']->user->pedigree_rabbitry_information) !!}</small></div>
            <div class="whole"></div><div class="whole"></div><div class="half"></div>
                <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header {{$pedigree['g1']['css']['color']}}">
                  <div class="widget-user-image">
                    <img style="border: 3px solid; width: 25%; margin-right: 20px; margin-top:-10px" src="{{$pedigree['g1']['image']['path']}}" class="img-circle">
                  </div><!-- /.widget-user-image -->
                  <h3 class="widget-user-username"><strong>{{$pedigree['g1']['name']}} </strong><i class="{{$pedigree['g1']['css']['icon']}} pull-right"></i></h3>
                  <h4 class="widget-user-desc">{{$pedigree['g1']['tattoo']}}</h4>

                </div>
                <div class="box-footer">
  <div class="row">
                            <div class="col-xs-6 border-right "><p class="text-center"><strong>DoB:</strong> {{$pedigree['g1']['aquired']}} </p></div>

                            <div class="col-xs-6"><p class="text-center"><strong>Breed:</strong> {{$pedigree['g1']['breed']}}</p></div>

                        </div>
  <hr>
  <div class="row">
                            <div class="col-xs-6 border-right "><p class="text-center"><strong>Color:</strong> {{$pedigree['g1']['color']}}</p></div>

                            <div class="col-xs-6"><p class="text-center"><strong>Weight:</strong> {{$pedigree['g1']['weight_slug']}}</p></div>

                        </div>
  <hr>
  <div class="row">
                            <div class="col-xs-12"><p class="text-center">{{$pedigree['g1']['notes']}}</p></div>



                        </div>
                </div>
              </div>



        </div>

        @if(isset($pedigree['g2']))
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="whole"></div><div class="half"></div>
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g2']['f1']])
            <div class="whole"></div><div class="whole"></div><div class="whole"></div>
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g2']['m1']])
        </div>
        @endif

        @if(isset($pedigree['g3']))
        <div id="3" class="col-xs-12 col-sm-6 col-lg-3">
            <div class="half"></div>
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g3']['f1']])
            <div class="whole"></div>
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g3']['m1']])
            <div class="whole"></div>
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g3']['f2']])
            <div class="whole"></div>
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g3']['m2']])

        </div>
        @endif

        @if(isset($pedigree['g4']))
        <div class="col-xs-12 col-sm-6 col-lg-3">


            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g4']['f1']])
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g4']['m1']])

            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g4']['f2']])
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g4']['m2']])

            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g4']['f3']])
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g4']['m3']])

            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g4']['f4']])
            @include('layouts.profile.public_pedigree',['r'=>$pedigree['g4']['m4']])
        </div>
        @endif




                            </div>
                  <!--The calendar -->

                </div><!-- /.box-body -->


  </div></section>


  </div>







        </section>

{{--
@include('layouts.profile.pdf',['pedigree'=>$pedigree,'directory'=> asset("/"),'isPublic'=>true])
--}}
@endsection