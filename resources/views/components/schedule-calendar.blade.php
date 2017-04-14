<template id="schedule-calendar-template">
    <!-- Your Page Content Here -->
    <section class="col-sm-6 col-md-7 col-lg-8 connectedSortable ui-sortable home-schedule">
        <div style="" class="box box-solid box-primary ">
            <div style="cursor: move;" class="box-header ui-sortable-handle">
                <i class="fa fa-calendar"></i>
                <h3 class="box-title">Schedule</h3>
                <!-- tools box -->
                <!-- /. tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="date-lines">
                    <div v-if="calendar_events.length" class="date-line" v-for="(index, date) in week">
                        <span class="date">@{{ date.comment || makeTimelineDate(date.date) }}</span><hr>
                    </div>
                </div>

                <div id="schedule-calendar-scroll-box" class="row row-horizon schedule" v-on:scroll="scrollHandlerLazyLoad">
                    <!-- Tasks -->
                    <div class="col-xs-3 col-md-2 col-lg-1" v-for="(index, rabbit) in calendar_events">
                        <!-- The timeline -->
                        <ul class="timeline timeline-inverse">
                            <li title="" @click="showAllTasksForRabbit(rabbit, rabbit.type)" data-placement="bottom" data-toggle="tooltip" style="margin-bottom:30px;" data-original-title="@{{ rabbit.name || (rabbit.given_id ? ('Litter: ' + rabbit.given_id) : false) || 'General Tasks' }}">
                                <img v-if="'breeders' == rabbit.type" class="img-circle" v-bind:src="rabbit.image.path" style="max-width: 50px; margin-left:8px;">
                                <div v-if="'breeders' != rabbit.type" class="letter-in-circle"><span class="letter">@{{ rabbit.given_id || rabbit.id || "G" }}</span></div>
                            </li>
                            <!-- timeline item -->
                            <li v-for="(index, events) in rabbit.events">
                                <i v-if="events.count" v-for="(index, event) in events" @click.prevent="openTaskForm(events, rabbit, rabbit.type)" title="" data-placement="top" data-toggle="tooltip" class="fa @{{ event.icon || 'fa-list' }}" data-original-title="@{{ eventTitle(event) }}"></i>
                            </li>
                            <!-- END timeline item -->
                        </ul>
                    </div>
                    <div class="col-xs-3 col-md-2 col-lg-1"></div>
                    <div class="col-xs-3 col-md-2 col-lg-1"></div>
                </div>
                <!--The calendar -->
            </div><!-- /.box-body -->

        </div>
    </section>

    <div class="modal" id="more_tasks" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-aqua">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">@{{ more_task_modal_title }}</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal row-paddings-compensation">

                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">

                                <div class="tab-pane active" id="timeline">
                                    <!-- The timeline -->
                                    <ul class="timeline timeline-inverse">
                                        <!-- timeline item -->
                                        <li v-for="(index, event) in activeTasks">
                                            <i class="fa @{{ event.icon }}"></i>
                                            <div class="timeline-item">
                                                <span class="tools">
                                                	<!-- <i v-if="!event.breed_id" @click.prevent="openTaskForm(event)" class="fa fa-edit"></i> -->
                           							<i @click.prevent="openTaskForm(event)" class="fa fa-edit"></i>
                                                    <i @click.prevent="showRequestForm(event)" class="fa fa-trash-o"></i>
                                                </span>
                                                <span class="time"><i class="fa fa-calendar"></i>@{{ event.date }}</span>
                                                <h3 class="timeline-header">@{{ event.name }}</h3>
                                            </div>
                                        </li>
                                        <!-- END timeline item -->
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer bg-info">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div>

</template>