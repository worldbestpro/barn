<template id="litter-profile-template">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Litter Profile </h1>
        <ol class="breadcrumb">
            <li><a href="#" v-link="{ path: '/' }"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#" v-link="{ path: '/litters' }">Litters</a></li>
            <li class="active">Litter profile</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row" v-if="!litterLoad">
            <div class="col-md-12 text-center">
                <i class="fa fa-spinner fa-spin fa-3x"></i>
            </div>
        </div>

        <div class="row" v-if="litterLoad">
            <div class="col-md-5 col-lg-3">

                <div class="box box-success">
                    <div class="box-body box-profile">
                        <div class="row">
                            <div class="col-xs-6">
                                <img v-bind:alt="father().name" v-bind:src="father().image.path"
                                     class="profile-user-img img-responsive img-circle buck-avatar-border litter">
                                <h3 class="profile-username text-center">@{{ father().name  }}</h3></div>

                            <div class="col-xs-6"><img class="profile-user-img img-responsive img-circle doe-avatar-border litter"
                                                       v-bind:src="mother().image.path" v-bind:alt="mother().name">
                                <h3 class="profile-username text-center">@{{ mother().name }}</h3></div>
                        </div>
                        <hr class="margin">
                        <div class="row">
                            <div class="col-xs-6 border-right"><p class=" text-center"><strong>Bred</strong><br>@{{ litter.bred }}
                                </p></div>
                            <div class="col-xs-6"><p class="text-center"><strong>Born</strong><br>@{{ litter.born }}</p></div>
                        </div>
                        <hr class="margin">
                        <div class="row">
                            <div class="col-xs-6 border-right"><p class="text-center"><strong>Litter ID</strong><br>@{{ litter.given_id }}</p>
                            </div>

                            <div class="col-xs-6"><p class="text-center"><strong>Live Kits</strong><br>@{{ aliveKits }}</p></div>
                        </div>
                        <hr class="margin">
                        <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>
                        <p>@{{ litter.notes }}</p>
                        <div class="box-footer text-center">
                            <a href="#" class="btn btn-default" @click.prevent="editModal">
                                <i class="fa fa-pencil" ></i> Edit
                            </a>
                            <a href="#" class="btn btn-default" @click.prevent="archiveModal">
                                <i class="fa fa-archive"></i> Archive
                            </a>
                            <a href="#" class="btn btn-default" @click.prevent="deleteModal">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                            <br><br>
                            <a href="#" class="btn btn-warning" @click.prevent="weightModal">
                                <i class="fa fa-balance-scale"></i> Weigh
                            </a>
                            <a href="#" class="btn btn-danger" @click.prevent="butcherModal">
                                <i class="fa fa-cutlery"></i> Butcher
                            </a>
                        </div>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>

            <div class="col-md-7 col-lg-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#litters" aria-expanded="true">Kits</a></li>
                        <li class=""><a data-toggle="tab" @click.prevent="loadRabbit(litter, 'litters')" href="#timeline" aria-expanded="false">Timeline</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="litters">
                            <div class="row">

                                <litter-box @edit-kit="editKit" @alive-kits="updateAlive" :litter.sync="litter"></litter-box>

                            </div>
                        </div>

                        <div id="timeline" class="tab-pane">
                            <div class="tab-pane" id="timeline">
                                @include('layouts.profile._timeline')
                            </div><!-- /.tab-pane -->
                        </div><!-- /.tab-pane -->

                    </div><!-- /.tab-content -->
                </div><!-- /.nav-tabs-custom -->

            </div>
        </div>

    </section>


    <div class="modal" id="kit-form-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <kit-form :kit.sync="activeKit" :litter.sync="litter" v-on:refresh-kits="refreshKits"></kit-form>
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

    <!--- Butcher --->
    <div id="litter-butcher-modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <litter-butcher v-on:refresh-kits="refreshKits" :litters="litters" :litter="litter" :kits.sync="kits"></litter-butcher>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div id="litter-weight-modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <litter-weight v-on:refresh-kits="refreshKits" :litter.sync="activeLitter" :litters="[activeLitter]" :kits.sync="activeKits"></litter-weight>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    @include('layouts.litter.modals.litter')
    @include('layouts.litter.modals.litter-birth')
    @include('layouts.archive-delete')

</template>