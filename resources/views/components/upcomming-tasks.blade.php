<template id="upcomming-tasks-template">
    <!-- Your Page Content Here -->
    <section class="col-sm-6 col-md-5 col-lg-4 connectedSortable ui-sortable">
        <div class="box box-primary">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
                <i class="ion ion-clipboard"></i>
                <h3 class="box-title">Tasks</h3>
                <button @click.prevent="showRequestForm(null)" type="button" class="btn btn-xs pull-right">Clear</button>
            </div><!-- /.box-header -->

            <div class="box-body">
                <ul class="todo-list ui-sortable" id="todo-list-fastclick">
                    <li id="id_task_@{{task.id}}"  v-for="(index, task) in activeTasks">
                        <!-- checkbox -->
                        <input class="needsclick" @click="finishTask(task)" type="checkbox" v-model="task.closed == 1" name="">
                        <!-- todo text -->
                        <i class="fa @{{ task.icon }} circle-background"></i>
                        <span v-bind:class="['text', (task.closed == 1) ? 'task-is-close' : '']" > @{{ getFullTaskName(task) }} </span>
                        <!-- Emphasis label -->
                        <small v-if="getColorForDate(task)" v-bind:class="getColorForDate(task)"><i class="fa fa-clock-o"></i> @{{ getTimeLeft(task) }}</small>
                        <!-- General tools such as edit or delete-->
                        <div class="tools">
                        	<span class="time"><i class="fa fa-calendar"></i> @{{ task.date }}</span>
                            <!-- <i  v-if="!task.breed_id" @click.prevent="openTaskForm(task)" class="fa fa-edit"></i> -->
                            <i @click.prevent="openTaskForm(task)" class="fa fa-edit"></i>
                            <i @click.prevent="showRequestForm(task, index)" class="fa fa-trash-o"></i>
                        </div>
                    </li>
                </ul>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix no-border">
                <div v-if="pagination_page.length" class="box-tools pull-left">
                    <ul class="pagination pagination-sm inline">
                        <li><a @click.prevent="loadTasks(null, 0)" href="#">«</a></li>
                        <li v-for="(index, tag) in pagination_page"><a @click.prevent="loadTasks(null, tag)" href="#">@{{ tag }}</a></li>
                        <li><a @click.prevent="loadTasks(null, last_page)"href="#">»</a></li>
                    </ul>
                </div><button @click.prevent="openTaskForm()" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add New</button>
            </div>
        </div>

    </section>

    <div class="modal in" id="new_task" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header @{{ info_panel_class }}">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">@{{ name_of_modal }}</h4>
                </div>
                <div class="modal-body">
                    <validator name="activeTaskValidator">
                        <form class="form-horizontal row-paddings-compensation">

                            <div class="row">
                                <div class="form-group col-xs-7 col-sm-6">
                                    <label class="col-sm-4 control-label">Type</label>
                                    <div class="col-sm-8">
                                        <select id="taskType" class="form-control" v-model="activeTask.type">
                                            <option value="general">General</option>
                                            <option value="litter">Litter</option>
                                            <option value="breeder">Breeder</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-xs-7 col-sm-6" v-if="activeTask.type != 'general'" v-bind:class="{ 'has-error': ($activeTaskValidator.breederlitterform.touched && $activeTaskValidator.breederlitterform.invalid) }">
                                    <label class="col-sm-4 control-label">@{{ capitalize(activeTask.type) }}</label>
                                    <div class="col-sm-8">
                                        <select id="breederlitterform" class="form-control" v-model="activeTask.relation.id" v-validate:breederlitterform="{required: (activeTask.type != 'general')}">
                                            <option value="">Choose @{{ activeTask.type }}...</option>
                                            <option v-if="activeTask.type == 'litter'" value="@{{bl.id}}" v-for="(index, bl) in breeder_litter">@{{ bl.given_id }}
                                                : @{{ bl.name }}</option>
                                            <option v-if="activeTask.type != 'litter'" value="@{{bl.id}}" v-for="(index, bl) in breeder_litter">@{{ bl.id }}
                                                : @{{ bl.name }}</option>
                                        </select>
                                        <small class="error" v-if="$activeTaskValidator.breederlitterform.touched && $activeTaskValidator.breederlitterform.invalid">Field is mandatory.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6 col-xs-7" v-bind:class="{ 'has-error': ($activeTaskValidator.name.touched && $activeTaskValidator.name.invalid) }">
                                    <label class="col-sm-4 control-label">Name</label>

                                    <div class="col-sm-8">
                                        <input id="name" type="text" class="form-control" placeholder="Enter ..." v-validate:name="['required']" v-model="activeTask.name" >
                                        <small class="error" v-if="$activeTaskValidator.name.touched && $activeTaskValidator.name.required">Field is mandatory.</small>
                                    </div>
                                </div>

                                <div class="form-group col-xs-7 col-sm-6" v-bind:class="{ 'has-error': ($activeTaskValidator.date.touched && $activeTaskValidator.date.invalid) }">
                                    <label class="col-sm-4 control-label">Date</label>
                                    <div class="col-sm-8">
                                        <div id="datepick" class="input-group date" v-datepicker="activeTask.date">
                                            <input id="date" type="text" class="form-control" v-validate:date="['required']" data-disable-past="false">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                        </div>
                                        <small class="error" v-if="$activeTaskValidator.date.touched && $activeTaskValidator.date.required">Field is mandatory.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-xs-7 col-sm-6">
                                    <label class="col-sm-4 control-label">Recurring</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" v-model="activeTask.recurring">
                                            <option value="1">Once</option>
                                            <option value="2">Every Week</option>
                                            <option value="3">Every 2 Weeks</option>
                                            <option value="4">Every Month</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-xs-7 col-sm-6"  v-bind:class="{ 'has-error': errors.icon }">
                                    <label class="col-sm-4 control-label">Icon</label>
                                    <div class="col-sm-8">
                                        <div class="select-icon-of-task">
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-cutlery bg-red" /><i class="fa fa-cutlery icon-circle" v-bind:class="{'bg-red': iconBackground['bg-red']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-venus-mars bg-blue" /><i class="fa fa-venus-mars icon-circle" v-bind:class="{'bg-blue': iconBackground['bg-blue']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-check bg-maroon" /><i class="fa fa-check icon-circle" v-bind:class="{'bg-maroon': iconBackground['bg-maroon']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-birthday-cake bg-green" /><i class="fa fa-birthday-cake icon-circle" v-bind:class="{'bg-green': iconBackground['bg-green']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-balance-scale bg-yellow" /><i class="fa fa-balance-scale icon-circle" v-bind:class="{'bg-yellow': iconBackground['bg-yellow']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-calendar bg-gray" /><i class="fa fa-calendar icon-circle" v-bind:class="{'bg-black': iconBackground['fa-calendar']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-heart bg-gray" /><i class="fa fa-heart icon-circle" v-bind:class="{'bg-black': iconBackground['fa-heart']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-asterisk bg-gray" /><i class="fa fa-asterisk icon-circle" v-bind:class="{'bg-black': iconBackground['fa-asterisk']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-bookmark bg-gray" /><i class="fa fa-bookmark icon-circle" v-bind:class="{'bg-black': iconBackground['fa-bookmark']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-eye bg-gray" /><i class="fa fa-eye icon-circle" v-bind:class="{'bg-black': iconBackground['fa-eye']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-flag bg-gray" /><i class="fa fa-flag icon-circle" v-bind:class="{'bg-black': iconBackground['fa-flag']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-medkit bg-gray" /><i class="fa fa-medkit icon-circle" v-bind:class="{'bg-black': iconBackground['fa-medkit']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-paw bg-gray" /><i class="fa fa-paw icon-circle" v-bind:class="{'bg-black': iconBackground['fa-paw']}"></i></label>
                                            <label><input type="radio" name="selecticon" v-model="activeTask.icon" value="fa-trophy bg-gray" /><i class="fa fa-trophy icon-circle" v-bind:class="{'bg-black': iconBackground['fa-trophy']}"></i></label>
                                        </div>
                                        <small class="error" v-if="errors.icon" >@{{ errors.icon }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-if="modeOfTask == 'edit'">
                                <div class="form-group col-xs-7 col-sm-6">
                                    <label for="taskIsDone" class="col-sm-4 control-label">
                                        <input type="checkbox" id="taskIsDone" v-model="activeTask.closed"  v-bind:true-value="'1'"  v-bind:false-value="'0'"> Done. </label>
                                </div>
                            </div>
                        </form>
                    </validator>
                </div>
                <div class="modal-footer @{{ info_panel_class }}">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button @click.prevent="createTask(modeOfTask)" type="button" class="btn @{{ button_class }}">Save changes</button>
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

</template>