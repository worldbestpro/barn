<template id="notification-tab-template">
    <li class="dropdown tasks-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-flag-o"></i>
            <span class="label label-danger">@{{ activeTasks.length || '' }}</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header">You have @{{ activeTasks.length }} tasks</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <li v-for="($index, task) in activeTasks">
                        <a @click.prevent="openManagingModal(task)" href="#">
                            <h3>
                                <i class="fa @{{ task.icon }} circle-background"></i>
                                @{{ getFullTaskName(task) }}
                            </h3>
                        </a>
                    </li>

                    <li><!-- Task item -->
                    </li><!-- end task item -->
                </ul>
            </li>
            <li class="footer">
                <a href="#!/schedule">View all tasks</a>
            </li>
        </ul>
    </li>

</template>