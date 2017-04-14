<template id="dashboard-template">

    <section class="content">


        <div class="row quick-tasks">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua" @click="breedModal">

                    <div class="inner">
                        <h3>Breed</h3>
                        <p>Add new breeding</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-venus-mars"></i>
                    </div>
                    <a href="#" class="small-box-footer">Start <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6" id="birthModal" @click="birthModal">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>Birth</h3>
                        <p>Record a birth</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-birthday-cake"></i>
                    </div>
                    <a href="#" class="small-box-footer">Record  <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div id="weightModal" class="col-lg-3 col-xs-6" @click="weightModal">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>Weigh</h3>
                        <p>Litter performance</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-balance-scale"></i>
                    </div>
                    <a href="#" class="small-box-footer">Enter Data <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6" @click="butcherModal">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>Butcher</h3>
                        <p>Dispatch litters</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cutlery"></i>
                    </div>
                    <a href="#" class="small-box-footer">Record event <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
        </div>

        <div class="row">
            <upcomming-tasks></upcomming-tasks>
            <schedule-calendar></schedule-calendar>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6"><a href="#" role="button" @click.prevent="newBreeder">
                    <div class="info-box">
                        <span class="info-box-icon bg-gray text-muted"><i class="fa fa-plus"></i></span>
                        <div class="info-box-content text-muted"><h1>New Breeder</h1>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </a>
            </div><div class="col-lg-4 col-md-6 col-sm-6"><a @click.prevent="newLitter" role="button" href="#">
                    <div class="info-box">
                        <span class="info-box-icon bg-gray text-muted"><i class="fa fa-plus"></i></span>
                        <div class="info-box-content text-muted"><h1>New Litter</h1>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </a>
            </div>
        </div>

        <!-- modal -->
        <div class="modal" id="breed">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-aqua">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Schedule Breed</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal row-paddings-compensation">
                        <div class="row">
                            <div class="form-group col-sm-6 col-xs-7">
                                <label class="col-sm-4 control-label">Buck</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="activeBreed.buck">
                                        <option value="-1">Choose buck...</option>
                                        <option v-for="(index, buck) in breeders.bucks" value="@{{ buck.id }}">@{{ buck.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-xs-7">
                                <label class="col-sm-4 control-label">Doe</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="activeBreed.doe">
                                        <option value="-1">Choose doe...</option>
                                        <option v-for="(index, doe) in breeders.does"  value="@{{ doe.id }}">@{{ doe.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-xs-7 col-sm-6">
                                <label class="col-sm-4 control-label">Date</label>
                                <div class="col-sm-8">
                                    <div class="input-group date" v-datepicker="activeBreed.date">
                                        <input type="text" class="form-control"><span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-th"></i></span>
                                    </div>
                                </div>

                            </div>
                        </div>

                            <div class="row">
                                <div class="col-sm-2"></div>
                                <div class="col-md-8">
                                    <div class="tab-pane active" id="timeline">
                                        <!-- The timeline -->
                                        <ul class="timeline timeline-inverse">

                                            <!-- timeline item -->
                                            <li v-for="(index, event) in dummyEvents">
                                                <i v-if="event.date" class="fa @{{ event.icon }}"></i>
                                                <div v-if="event.date" class="timeline-item">
                                                    <span class="time"><i class="fa fa-calendar"></i> @{{ event.date }}</span>
                                                    <h3 class="timeline-header">@{{ event.name }}</h3>
                                                </div>
                                            </li>
                                            <!-- END timeline item -->

                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- /.box-header -->
                            <!-- form start -->

                            <!-- /.box-body -->
                            <!-- /.box-footer -->
                        </form>
                    </div>
                    <div class="modal-footer bg-info">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close
                        </button>
                        <button @click="createBreed(activeBreed)" type="button" class="btn btn-info">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>


        <!-- modal -->

        @include("layouts.litter.modals.litter-birth")

        @include("layouts.litter.modals.litter")
        @include("layouts.breeders.partials.breeder")

<?php /*
        <div class="modal" id="birth">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-green">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Report Birth</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group col-sm-6">
                                <label for="inputPassword3" class="col-sm-4 control-label">Breeders</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="activeBirth.breedplan">
                                        <option value="-1">Select plan</option>
                                        <option v-for="plan in plans" value="@{{ plan.id }}">@{{ plan.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-4 control-label" for="inputPassword3">Litter ID</label>
                                <div class="col-sm-8">
                                    <input type="text" placeholder="Number in Litter" v-model="activeBirth.given_id" class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="inputEmail3" class="col-sm-4 control-label">Date</label>
                                <div class="col-sm-8">
                                    <div class="input-group date">
                                        <input type="text" class="form-control"  v-model="activeBirth.born">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-th"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="inputEmail3" class="col-sm-4 control-label"># Kits</label>
                                <div class="col-sm-8">
                                    <input type="text" placeholder="Kits amount" v-model="activeBirth.kits_amount"
                                           class="form-control">
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="exampleInputFile">Notes</label>
                                <div class="col-sm-10"><textarea v-model="activeBirth.notes" placeholder="Descriptions" rows="3"
                                                                 class="form-control"></textarea></div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer bg-success">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="recordBirth" class="btn btn-success">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>*/ ?>

    </section>


    <div id="litter-weight-modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <litter-weight :litter="litter" :litters="litters" :kits="activeKits"></litter-weight>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!--- Butcher --->
    <div id="litter-butcher-modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <litter-butcher :litters="litters" :litter="litter" :kits="activeKits"></litter-butcher>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>


    @include('layouts.litter.modals.butcher')

</template>