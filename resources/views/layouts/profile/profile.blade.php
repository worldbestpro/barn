<template id="profile-template">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @{{ breedSex }} Profile </h1>
        <ol class="breadcrumb">
            <li><a href="#" v-link="{ path: '/' }"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#" v-link="{ path: '/breeders' }">Breeders</a></li>
            <li class="active">@{{ breeder.name }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-5 col-lg-3">
                <!-- Profile Image -->
                <div class="box" v-bind:class="breedSexClass">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle breeder" src="img/rabbit1.jpg"
                             v-bind:alt="breeder.name" v-bind:src="breeder.image.path">
                        <h3 class="profile-username text-center">@{{ breeder.name }}</h3>
                        <div class="row">

                            <div class="col-xs-4 border-right"><p class=" text-center"><strong>ID</strong><br>@{{ breeder.tattoo }}</p>
                            </div>
                            <div class="col-xs-4 border-right"><p class=" text-center"><strong>Cage</strong><br>@{{ breeder.cage }} </p>
                            </div>
                            <div class="col-xs-4"><p class="text-center"><strong>Aquired</strong><br>@{{ breeder.aquired }}
                                </p></div>
                        </div>
                        <hr class="margin">
                        <div class="row">
                            <div class="col-xs-4 border-right "><p class="text-center"><strong>Breed</strong><br>@{{ breeder.breed }}</p></div>
							<div class="col-xs-4 border-right "><p class="text-center"><strong>Color</strong><br>@{{ breeder.color }}</p></div>
                            <div class="col-xs-4"><p class="text-center"><strong>Weight</strong><br>@{{ breeder.weight }} lbs</p></div>

                        </div>
                        <hr class="margin">
                        <div class="row">
                            <div class="col-xs-6 border-right "><p class="text-muted text-center">
                                    <strong>Mother</strong><br>@{{ getParent(breeder.mother) }}</p></div>

                            <div class="col-xs-6"><p class="text-muted text-center"><strong>Father</strong>
                                    <br>@{{ getParent(breeder.father) }}
                                </p></div>

                        </div>
                        <hr class="margin">
                        <div class="row">
                            <div class="col-xs-6 border-right "><p class="text-center"><strong>Live Kits</strong><br>@{{ breeder.live_kits }}
                                </p></div>

                            <div class="col-xs-6"><p class="text-center"><strong>Kits To Date</strong><br>@{{ breeder.kits }}</p></div>
                        </div>
                        <hr class="margin">
                        <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>
                        <p>@{{ breeder.notes }}</p>
                        <div class="box-footer text-center">
                            <button class="btn btn-default" @click="edit">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <button class="btn btn-default" @click="archiveModal(breeder.id)">
                                <i class="fa fa-archive"></i> Archive
                            </button>
                            <button class="btn btn-default" @click="deleteModal((breeder.id))">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                            <br /><br />
                            <a href="#/pedigree/@{{ breeder.id }}" class="btn btn-primary">
                                <i class="fa fa-share-alt"></i> Pedigree
                            </a>
                        </div>


                    </div><!-- /.box-body -->
                </div><!-- /.box -->

                <!-- /.box -->
            </div>
            <div class="col-md-7 col-lg-9">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a aria-expanded="true" href="#litters" data-toggle="tab">Litters</a></li>
                        <li class=""><a @click.prevent="loadRabbit(breeder, 'breeders')" aria-expanded="false" href="#timeline" data-toggle="tab">Timeline</a></li>
                    </ul>
                    <div class="tab-content">

                        <div id="litters" class="tab-pane active">
                            <div v-for="litter in litters">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-success" v-bind:class="{ 'box-default': litter.archived == 1, 'collapsed-box': litter.archived == 1 }">
                                            <a data-widget="collapse" href="#">
                                                <div class="box-header" v-bind:class="{ 'bg-gray-active': litter.archived == 1, 'bg-olive': litter.archived == 0,
                                                 'collapsed-box': litter.archived == 1 }">
                                                    <h3 class="box-title">Litter @{{ litter.given_id }}</h3>
                                                    <h5 class="widget-user-desc">Born: @{{ litter.born }}
                                                    <h5 class="widget-user-desc">@{{ getSecondParentSex(litter) }}: @{{ getSecondParentName(litter) }}
                                                        <span v-if="litter.archived == 1"><br>Butchered: @{{ makeDate(litter.updated_at) }}</span></h5>
   {{--                                                 <div class="box-statistics" >
                                                        <div></div>
                                                        <div>Total weight: <b>@{{ litter.total_weight }}</b></div>
                                                        <div>Average weight: <b>@{{ litter.total_weight }}</b></div>
                                                    </div>--}}
                                                    <div class="box-tools pull-right btn-group">
                                                        <button v-if="litter.archived != 1" href="#"
                                                                class="btn btn-outline"
                                                                @click.prevent="editLitterModal(litter)"
                                                                title="Edit"><i class="fa fa-pencil"></i>
                                                        </button>
                                                        <button v-if="litter.archived != 1" href="#"
                                                                class="btn btn-outline"
                                                                @click.prevent="weightLitter(litter)"
                                                                title="Weigh"><i class="fa fa-balance-scale"></i>
                                                        </button>
                                                        <button v-if="litter.archived != 1" class="btn btn-outline"
                                                                href="#"
                                                                @click.prevent="butcherModal(litter)" title="Butcher">
                                                            <i class="fa fa-cutlery"></i></button>
                                                        <button v-if="litter.archived != 1" class="btn btn-outline"
                                                                @click.prevent="archiveLitterModal(litter)" title="Archive"><i
                                                                    class="fa fa-archive"></i></button>
                                                        <button class="btn btn-outline"
                                                                @click.prevent="deleteLitterModal(litter)" title="Delete"><i
                                                                    class="fa fa-trash"></i></button>
                                                    </div>
                                                    <!-- /.box-tools -->
                                                </div><!-- /.box-header -->
                                            </a>
                                            <div class="box-body" v-bind:style="{ display: litter.archived == 1 ? 'none' : 'block' }">

                                            <litter-box v-on:edit-kit="editKit" :litter.sync="litter"></litter-box>

                                            </div><!-- /.box-body -->
                                        </div><!-- /.box -->
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="tab-pane" id="timeline">
                            @include('layouts.profile._timeline')
                        </div><!-- /.tab-pane -->

                    </div><!-- /.tab-content -->
                </div><!-- /.nav-tabs-custom -->

            </div><!-- /.col -->
        </div><!-- /.row -->



        @include('layouts.breeders.partials.breeder')
        @include('layouts.archive-delete')
        @include('layouts.litter.modals.butcher')


        <div class="modal modal-danger" id="delete-litter-modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 text-center"><h3><i class="fa fa-fw fa-warning"></i><br>
                                    Do you want to delete this litter?</h3>
                            </div>
                        </div>
                        <div class="row margin">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-outline" type="button" @click="deleteLitter"><i class="fa fa-check"></i> Yes</button>
                                <button type="button" class="btn btn-outline" data-dismiss="modal"><i
                                            class="fa fa-close"></i> No
                                </button>
                                <button data-dismiss="modal" class="btn btn-outline" @click="archiveLitter" type="button"><i
                                        class="fa fa-archive"></i> Archive
                                </button>
                            </div>
                        </div>

                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>


        <div class="modal modal-default" id="archive-litter-modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-body bg-gray">
                        <div class="row">
                            <div class="col-sm-12 text-center"><h3><i class="fa fa-archive"></i><br>
                                    Do you want to archive this litter?</h3>
                            </div>
                        </div>
                        <div class="row margin">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-default" type="button" @click="archiveLitter"><i class="fa fa-check"></i> Yes</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                            class="fa fa-close"></i> No
                                </button>
                                <button data-dismiss="modal" class="btn btn-default" @click="deleteLitter" type="button"><i
                                        class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>

                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <div class="modal modal-danger in" id="delete_task" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <h3><i class="fa fa-fw fa-warning"></i><br>Do you want to delete @{{ activeTask.name || 'closed tasks' }}?</h3>
                            </div>
                        </div>
                        <div class="row margin">
                            <div class="col-sm-12 text-center">
                                <button @click.prevent="deleteTask(activeTask)" class="btn btn-outline" type="button"><i class="fa fa-check"></i> Yes</button>
                                <button type="button" class="btn btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> No </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- /.modal-dialog -->
        </div>

        <div class="modal in" id="new_task" style="display: none;">
            <div class="modal-dialog">
                <task-form :activeTask="activeTask"></task-form>
            </div><!-- /.modal-dialog -->
        </div>

        <div id="litter-weight-modal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <litter-weight :litter="activeLitter" :litters="litters" :kits.sync="activeKits"></litter-weight>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>


        <div id="litter-form" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <litter-form :litter="activeLitter" :litters="litters" :kits.sync="activeKits"></litter-form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>


        <div class="modal" id="kit-form-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <kit-form :kit.sync="activeKit" :litters.sync="litters" v-on:refresh-kits="refreshKits"></kit-form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>


    </section><!-- /.content -->
</template>