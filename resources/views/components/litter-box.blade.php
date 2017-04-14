<template id="litter-box-template">


    <div class="col-lg-8 col-md-12 col-xs-12">

        <div class="col-lg-6 col-md-12 col-sm-6 col-xs-12" v-for="kit in kits">
            <a role="button" href="#" @click.prevent="editKitModal(kit)">
                <div class="info-box" v-bind:class="getGenderClass(kit)">
                                <span class="info-box-icon">
                                    <img style="max-width: 80%; margin:10px auto; border: 3px solid"
                                         src="media/breeders/default.jpg"
                                         v-bind:src="kit.image.path"
                                         class="img-responsive img-circle">
                                </span>
                    <div class="info-box-content">
                        <span class="info-box-number">@{{ kit.given_id }}</span>
                            <span class="info-box-text">
                                <strong>@{{ kit.color }} <br>
                                    <i class="fa fa-balance-scale"></i>
                                    @{{ showWeights(kit) }} @{{ litter.weight_unit_short }}
                                </strong>
                        </span>
                    </div>

                    <div v-if="isButchered(kit)" class="kit-butchered-icon"><i class="fa fa-cutlery"></i></div>
                    <div v-if="kit.improved == 1" class="kit-breeder-icon"><i class="fa fa-venus-mars"></i></div>
                </div>
            </a>
        </div>

    </div>

    <div class="col-lg-4 col-md-12 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-olive"><i class="fa fa-calendar-check-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">age</span>
                <span class="info-box-number">@{{ age_weeks }}</span>
                <span class="info-box-number">@{{ age_days }}</span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->

        <div class="info-box bg-olive">
                                                    <span class="info-box-icon"><i
                                                                class="fa fa-balance-scale"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">total weight</span>
                <span class="info-box-number">@{{ litter.total_weight_slug.total }} <!--<span v-if="litter.average_weight">@{{ litter.weight_unit_short }}</span>--></span>

{{--
                <div class="progress">
                    <div class="progress-bar" style="width: 10%"></div>
                </div>
                <span class="progress-description">                     10% Increase since last litter                   </span>
--}}

            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->

        <div class="info-box ">
                                                    <span class="info-box-icon bg-olive"><i
                                                                class="fa fa-star"></i></span>
            <div class="info-box-content ">
                <span class="info-box-text">AVERAGE WEIGHT</span>
                <span class="info-box-number">@{{ litter.total_weight_slug.average }} <!--<span v-if="litter.average_weight">@{{ litter.weight_unit_short }}</span>--></span>
            </div>

            {{--            <div class="progress">
                <div style="width: 25%" class="progress-bar bg-olive"></div>
            </div>

            <span class="margin">                     25% Increase                   </span>
--}}
            <!-- /.info-box-content -->
        </div><!-- /.info-box -->

        <!-- small box -->
        <div class="small-box bg-olive">
            <div class="inner">
                <h3>@{{ litter.survival_rate }}<sup style="font-size: 20px">%</sup></h3>
                <p>Survival Rate</p>
            </div>
            <div class="icon">
                <i class="fa fa-line-chart"></i>
            </div>
        </div>

        <!-- small box -->
        <div class="small-box bg-gray" v-if="litter.kits_died">
            <div class="inner text-muted">
                <h3><i class="fa fa-heart-o pull-right"></i> @{{ litter.kits_died }} died</h3>

            </div>
        </div>
    </div>


</template>