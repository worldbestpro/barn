<div class="row row-horizon">
    <div class="col-md-4"  v-for="(index, eventForDay) in activeTimeline">
        <!-- The timeline -->
        <ul class="timeline timeline-inverse">

            <!-- timeline item -->
            <li v-for="(index, event) in eventForDay">
                <i v-if="event.date" class="fa @{{ event.icon }}"></i>
                <div v-if="event.date" class="timeline-item">
                    <span class="tools">
                        <i @click.prevent="openTaskForm(event)" class="fa fa-edit"></i>
                        <i @click.prevent="showRequestForm(event)" class="fa fa-trash-o"></i>
                    </span>
                    <span class="time"><i class="fa fa-calendar"></i> @{{ event.date }}</span>
                    <h3 class="timeline-header">@{{ event.name }}</h3>
                </div>
            </li>
            <!-- END timeline item -->

        </ul>
    </div>
</div>
