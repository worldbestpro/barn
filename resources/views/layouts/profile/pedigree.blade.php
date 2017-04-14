<template id="pedigree-template">

    <section class="content">

              <!-- Your Page Content Here -->

              <div class="row">


      <section class="col-lg-12">
      <div class="box box-solid box-primary " style="">
                    <div class="box-header">
                      <i class="fa fa-share-alt"></i>
                      <h3 class="box-title">Pedigree</h3>
                      <!-- tools box -->
      <div class="box-tools pull-right">
                        <button class="btn btn-sm btn-default" onclick="window.history.back();"><i class="fa fa-arrow-left"></i> Back to Breeder</button>
                        <a class="btn btn-info" href="{{url("admin/breeders")}}/@{{id}}/pdf"><i class="fa fa-file-pdf-o"></i> <strong>PDF</strong></a>

                      </div>
                      <!-- /. tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">





      <div class="row row-horizon pedigree">

            <!-- START GENERATION 1 -->
            <div class="col-xs-13 col-sm-6 col-md-5 col-lg-4">
                <div class="whole"></div><div class="whole"></div><div class="half"></div>
                    <div class="box box-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header @{{ generations.g1.css.color }}">
                      <div class="widget-user-image">
                        <img style="border: 3px solid; width: 25%; margin-right: 20px; margin-top:-10px" v-if="!generations.g1.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-circle">
                        <img style="border: 3px solid; width: 25%; margin-right: 20px; margin-top:-10px" v-if="generations.g1.image.path" v-bind:src="generations.g1.image.path" class="img-circle">


                      </div><!-- /.widget-user-image -->
                      <h3 class="widget-user-username"><strong>@{{ generations.g1.name }}  </strong><i class="@{{ generations.g1.css.icon }} pull-right"></i></h3>
                      <h4 class="widget-user-desc">@{{ generations.g1.tattoo }}</h4>

                    </div>
                    <div class="box-footer">
      <div class="row">
                                <div class="col-xs-6 border-right "><p class="text-center"><strong>DoB:</strong> @{{ generations.g1.aquired }} </p></div>

                                <div class="col-xs-6"><p class="text-center"><strong>Breed:</strong> @{{ generations.g1.breed }}</p></div>

                            </div>
      <hr>
      <div class="row">
                                <div class="col-xs-6 border-right "><p class="text-center"><strong>Color:</strong> @{{ generations.g1.color }}</p></div>

                                <div class="col-xs-6"><p class="text-center"><strong>Weight:</strong> @{{ generations.g1.weight_slug }}</p></div>

                            </div>
      <hr>
      <div class="row">
                                <div class="col-xs-12"><p class="text-center">@{{ generations.g1.notes }}</p></div>



                            </div>
                    </div>
                  </div>



            </div>
            <!-- END GENERATION 1 -->


            <!-- START GENERATION 2-->
            <div class="col-xs-12 col-sm-6 col-lg-3" v-if="generations.g2">
                <!-- FIRST PARENT -->
                <div class="whole"></div>
                <div class="half"></div>
                <a @click="edit(generations.g2.f1.id)" role="button" href="javascript: void(0);" v-if="generations.g2.f1">
                    <div class="info-box @{{ generations.g2.f1.css.color }} @{{ generations.g2.f1.sex }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g2.f1.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g2.f1.image.path" v-bind:src="generations.g2.f1.image.path" class="img-responsive img-circle">


                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g2.f1.name }}: @{{ generations.g2.f1.custom_id }}  <i class="@{{ generations.g2.f1.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g2.f1.day_of_birth }}</span><span class="pull-right">@{{ generations.g2.f1.breed }}</span><br>
      <span class="notes">@{{ generations.g2.f1.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>


                <!-- SECOND PARENT -->
                <div class="whole"></div>
                <div class="whole"></div>
                <div class="whole"></div>
                <a @click="edit(generations.g2.m1.id)" role="button" href="javascript: void(0);" v-if="generations.g2.m1">
                    <div class="info-box @{{ generations.g2.m1.css.color }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g2.m1.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g2.m1.image.path" v-bind:src="generations.g2.m1.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g2.m1.name }}: @{{ generations.g2.m1.custom_id }} <i class="@{{ generations.g2.m1.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g2.m1.day_of_birth }}</span><span class="pull-right">@{{ generations.g2.m1.breed }}</span><br>
      <span class="notes">@{{ generations.g2.m1.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>


            </div>
            <!-- END GENERATION 2 -->


            <!-- START GENERATION 3 -->
            <div id="3" class="col-xs-12 col-sm-6 col-lg-3" v-if="generations.g3">
                <div class="half"></div>
                <a @click="edit(generations.g3.f1.id)" role="button" href="javascript: void(0);" v-if="generations.g3.f1">
                    <div class="info-box @{{ generations.g3.f1.css.color }} @{{ generations.g3.f1.sex }}">
                                    <span class="info-box-icon">

                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g3.f1.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g3.f1.image.path" v-bind:src="generations.g3.f1.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g3.f1.name }}: @{{ generations.g3.f1.custom_id }} <i class="@{{ generations.g3.f1.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g3.f1.day_of_birth }}</span><span class="pull-right">@{{ generations.g3.f1.breed }}</span><br>
      <span class="notes">@{{ generations.g3.f1.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>

                <div v-if="!generations.g3.f1" style="height: 200px;"></div>

                <div class="whole"></div>
                <a @click="edit(generations.g3.m1.id)" role="button" href="javascript: void(0);" v-if="generations.g3.m1">
                    <div class="info-box @{{ generations.g3.m1.css.color }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g3.m1.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g3.m1.image.path" v-bind:src="generations.g3.m1.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g3.m1.name }}: @{{ generations.g3.m1.custom_id }} <i class="@{{ generations.g3.m1.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g3.m1.day_of_birth }}</span><span class="pull-right">@{{ generations.g3.m1.breed }}</span><br>
      <span class="notes">@{{ generations.g3.m1.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>

                <div class="whole"></div>
      <a @click="edit(generations.g3.f2.id)" role="button" href="javascript: void(0);" v-if="generations.g3.f2">
                    <div class="info-box @{{ generations.g3.f2.css.color }} @{{ generations.g3.f2.sex }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g3.f2.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g3.f2.image.path" v-bind:src="generations.g3.f2.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g3.f2.name }}: @{{ generations.g3.f2.custom_id }} <i class="@{{ generations.g3.f2.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g3.f2.day_of_birth }}</span><span class="pull-right">@{{ generations.g3.f2.breed }}</span><br>
      <span class="notes">@{{ generations.g3.f2.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>

                <div v-if="!generations.g3.f2" style="height: 200px;"></div>

                <div class="whole"></div>
                <a @click="edit(generations.g3.m2.id)" role="button" href="javascript: void(0);" v-if="generations.g3.m2">
                    <div class="info-box @{{ generations.g3.m2.css.color }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g3.m2.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g3.m2.image.path" v-bind:src="generations.g3.m2.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g3.m2.name }}: @{{ generations.g3.m2.custom_idd }} <i class="@{{ generations.g3.m2.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g3.m2.day_of_birth }}</span><span class="pull-right">@{{ generations.g3.m2.breed }}</span><br>
      <span class="notes">@{{ generations.g3.m2.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>

            </div>
            <!-- END GENERATION 3 -->


            <!-- START GENERATION 4 -->
            <div class="col-xs-12 col-sm-6 col-lg-3" v-if="generations.g4">
                <a @click="edit(generations.g4.f1.id)" role="button" href="javascript: void(0);"  v-if="generations.g4.f1">
                    <div class="info-box @{{ generations.g4.f1.css.color }} @{{ generations.g4.f1.sex }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g4.f1.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g4.f1.image.path" v-bind:src="generations.g4.f1.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g4.f1.name }}: @{{ generations.g4.f1.custom_id }} <i class="@{{ generations.g4.f1.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g4.f1.day_of_birth }}</span><span class="pull-right">@{{ generations.g4.f1.breed }}</span><br>
      <span class="notes">@{{ generations.g4.f1.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>
                <a @click="edit(generations.g4.m1.id)" role="button" href="javascript: void(0);" v-if="generations.g4.m1">
                    <div class="info-box @{{ generations.g4.m1.css.color }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g4.m1.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g4.m1.image.path" v-bind:src="generations.g4.m1.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g4.m1.name }}: @{{ generations.g4.m1.custom_id }} <i class="@{{ generations.g4.m1.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g4.m1.day_of_birth }}</span><span class="pull-right">@{{ generations.g4.m1.breed }}</span><br>
      <span class="notes">@{{ generations.g4.m1.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>


                <div v-if="!generations.g4.f1" style="height: 200px;"></div>

                <a @click="edit(generations.g4.f2.id)" role="button" href="javascript: void(0);" v-if="generations.g4.f2">
                    <div class="info-box @{{ generations.g4.f2.css.color }} @{{ generations.g4.f2.sex }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g4.f2.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g4.f2.image.path" v-bind:src="generations.g4.f2.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g4.f2.name }}: @{{ generations.g4.f2.custom_id }} <i class="@{{ generations.g4.f2.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g4.f2.day_of_birth }}</span><span class="pull-right">@{{ generations.g4.f2.breed }}</span><br>
      <span class="notes">@{{ generations.g4.f2.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>

                <a @click="edit(generations.g4.m2.id)" role="button" href="javascript: void(0);" v-if="generations.g4.m2">
                    <div class="info-box @{{ generations.g4.m2.css.color }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g4.m2.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g4.m2.image.path" v-bind:src="generations.g4.m2.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g4.m2.name }}: @{{ generations.g4.m2.custom_id }} <i class="@{{ generations.g4.m2.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g4.m2.day_of_birth }}</span><span class="pull-right">@{{ generations.g4.m2.breed }}</span><br>
      <span class="notes">@{{ generations.g4.m2.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>


                <div v-if="!generations.g4.f2" style="height: 200px;"></div>

      <a @click="edit(generations.g4.f3.id)" role="button" href="javascript: void(0);" v-if="generations.g4.f3">
                    <div class="info-box @{{ generations.g4.f3.css.color }} @{{ generations.g4.f3.sex }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g4.f3.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g4.f3.image.path" v-bind:src="generations.g4.f3.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g4.f3.name }}: @{{ generations.g4.f3.custom_id }} <i class="@{{ generations.g4.f3.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g4.f3.day_of_birth }}</span><span class="pull-right">@{{ generations.g4.f3.breed }}</span><br>
      <span class="notes">@{{ generations.g4.f3.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>

                <a @click="edit(generations.g4.m3.id)" role="button" href="javascript: void(0);" v-if="generations.g4.m3">
                    <div class="info-box @{{ generations.g4.m3.css.color }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g4.m3.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g4.m3.image.path" v-bind:src="generations.g4.m3.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g4.m3.name }}: @{{ generations.g4.m3.custom_id }} <i class="@{{ generations.g4.m3.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g4.m3.day_of_birth }}</span><span class="pull-right">@{{ generations.g4.m3.breed }}</span><br>
      <span class="notes">@{{ generations.g4.m3.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>


                <div v-if="!generations.g4.f3" style="height: 200px;"></div>

      <a @click="edit(generations.g4.f4.id)" role="button" href="javascript: void(0);" v-if="generations.g4.f4">
                    <div class="info-box @{{ generations.g4.f4.css.color }} @{{ generations.g4.f4.sex }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g4.f4.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g4.f4.image.path" v-bind:src="generations.g4.f4.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g4.f4.name }}: @{{ generations.g4.f4.custom_id }} <i class="@{{ generations.g4.f4.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g4.f4.day_of_birth }}</span><span class="pull-right">@{{ generations.g4.f4.breed }}</span><br>
      <span class="notes">@{{ generations.g4.f4.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a><a @click="edit(generations.g4.m4.id)" role="button" href="javascript: void(0);" v-if="generations.g4.m4">
                    <div class="info-box @{{ generations.g4.m4.css.color }}">
                                    <span class="info-box-icon">
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="!generations.g4.m4.image" src="{{asset('')}}media/pedigree/default.jpg" class="img-responsive img-circle" >
                                        <img style="max-width: 80%; margin:10px auto; border: 3px solid" v-if="generations.g4.m4.image.path" v-bind:src="generations.g4.m4.image.path" class="img-responsive img-circle">

                                    </span>
                        <div class="info-box-content">
                            <span class="info-box-number">@{{ generations.g4.m4.name }}: @{{ generations.g4.m4.custom_id }} <i class="@{{ generations.g4.m4.css.icon }} pull-right"></i></span>
                                <span class="info-box-text"><span class="pull-left">@{{ generations.g4.m4.day_of_birth }}</span><span class="pull-right">@{{ generations.g4.m4.breed }}</span><br>
      <span class="notes">@{{ generations.g4.m4.notes }}</span>

                            </span>
                        </div>



                    </div>
                </a>

                <div v-if="!generations.g4.f4" style="height: 200px;"></div>
            </div>
            <!-- END GENERATION 4 -->


                                </div>
                      <!--The calendar -->

                    </div><!-- /.box-body -->
       <div class="box-footer">
                        <button class="btn btn-default" onclick="window.history.back();"><i class="fa fa-arrow-left"></i> Back to Breeder</button>
                        <a class="btn btn-info pull-right" href="{{url("admin/breeders")}}/@{{id}}/pdf" ><i class="fa fa-file-pdf-o"></i> <strong>Generate PDF</strong></a>

                      </div>

      </div></section>


      </div>


        @include('layouts.breeders.partials.pedigree')



            </section>
</template>