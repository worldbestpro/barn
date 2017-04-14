<template id="schedule-template">

    <section class="content-header">
        <h1>Schedule 
        
        <div class="btn-group">       
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="false">
              <span class="caret"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul role="menu" class="dropdown-menu">
              <li><a @click.prevent="changeTypeForFilter('all')" href="#">All</a</li>
              <li><a @click.prevent="changeTypeForFilter('general')" href="#">General</a></li>
              <li><a @click.prevent="changeTypeForFilter('breeder')" href="#">Breeders</a></li>
              <li><a @click.prevent="changeTypeForFilter('litter')" href="#">Litters</a></li>
            </ul>
          </div>
        
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Schedule</li>
        </ol>
    </section>

    <section class="content">

        <!-- Your Page Content Here -->
        <div class="row">
            <upcomming-tasks :external-tasks.sync="activeTasks"></upcomming-tasks>
            <schedule-calendar></schedule-calendar>
        </div>

        <div class="row"><div v-if="false" class="col-md-3 col-sm-6 col-xs-12"><a data-toggle="modal" role="button" href="#new_breeder">
                    <div class="info-box">
                        <span class="info-box-icon bg-gray text-muted"><i class="fa fa-plus"></i></span>
                        <div class="info-box-content text-muted"><h1>Add New</h1>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </a>
            </div></div>


        <!-- modal -->
        <div class="modal" id="new_breeder" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">New Breeder</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" v-on="submit: validateTest123">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmail3">Name</label>
                                <div class="col-sm-10"><input type="text" placeholder="Enter ..." class="form-control">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmail3">Cage</label>
                                <div class="col-sm-4"><input type="text" placeholder="Enter ..." class="form-control">

                                </div>

                                <label class="col-sm-2 control-label" for="inputEmail3">Tattoo</label>
                                <div class="col-sm-4"><input type="text" placeholder="Enter ..." class="form-control">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputPassword3">Father</label>
                                <div class="col-sm-4">
                                    <select class="form-control">
                                        <option>Sara</option>
                                        <option>Wilma</option>
                                        <option>Queen</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label" for="inputPassword3">Mother</label>
                                <div class="col-sm-4">
                                    <select class="form-control">
                                        <option>Sara</option>
                                        <option>Wilma</option>
                                        <option>Queen</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmail3">Color</label>
                                <div class="col-sm-4"><input type="text" placeholder="Enter ..." class="form-control">

                                </div>

                                <label class="col-sm-2 control-label" for="inputEmail3">Aquired</label>
                                <div class="col-sm-4">
                                    <div class="input-group date" id="datepick">
                                        <input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                    </div>
                                </div>

                            </div><div class="form-group">
                                <label class="col-sm-2 control-label" for="exampleInputFile">Photo</label>
                                <div class="col-sm-10">
                                    <input type="file" id="exampleInputFile">
                                    <p class="help-block">Select photo of litter</p></div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="exampleInputFile">Notes</label>
                                <div class="col-sm-10"><textarea placeholder="Descriptions" rows="3" class="form-control"></textarea></div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer bg-info">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </section>

    @include('layouts.litter.modals.weight')
    @include('layouts.litter.modals.litter')
    @include('layouts.litter.modals.butcher')
    @include("layouts.litter.modals.litter-birth")

</template>