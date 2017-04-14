{{-- @if($r['name']) --}}
<div>
                <div class="info-box {{$r['css']['color']}} {{$r['sex']}}">
                                <span class="info-box-icon">
                                    <img style="max-width: 80%; margin:10px auto; border: 3px solid" src="{{$r['image']['path']}}" class="img-responsive img-circle">
                                </span>
                    <div class="info-box-content">
                        <span class="info-box-number">{{$r['name']}}: {{$r['custom_id']}} <i class="{{$r['css']['icon']}} pull-right"></i></span>
                            <span class="info-box-text"><span class="pull-left">{{$r['day_of_birth']}}</span><span class="pull-right">{{$r['breed']}}</span><br>
  <span class="notes">{{$r['notes']}}</span>

                        </span>
                    </div>



                </div>
</div>
{{--
@else
<div style="height: 90px">&nbsp;</div>
@endif
--}}