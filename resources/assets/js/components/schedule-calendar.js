(function () {


    App.Components.ScheduleCalendar = {
        template: "#schedule-calendar-template",
        data: function () {
            return {
                activeTasks: [],
                activeTask: {},
                breeders: [],
                litters: [],
                generals:[],
                calendar_events: [],
                week: [],
                activeTasksForOneRabbit : [],
                activeRabbit: {},
                errors: { name: null, date: null, icon: null},
                type_of_task: 'breeder',
                perPage: 12,
                more_task_modal_title: '',
                scrollableElementId: "#schedule-calendar-scroll-box",
                scrollableElement: null,
                skipBreeders: 0,
                skipLitters: 'all',
                firstLoadAllTasks: true
            }
        },
        props: ['externalTasks'],
        computed: {},
        watch:{},
        components: {},
        events: {
            'schedule-calendar-filtering': function(msg){
                this.loadCalendarEvents(msg);
            },
            'schedule-calendar-all-task-of-current-week': function(msg){
                this.showTasksForCurrentWeek(msg);
            },
            'type-events-was-changed': function(msg){
                //msg-format :{typeOfTask: VALUE, page: PAGE_NUMBER}
                //typeOfTask: [ALL|GENERAL|BREEDER|LITTER]
                //PAGE_NUMBER: [0->oo];
                this.calendar_events = [];
                this.type_of_task = msg.typeOfTask;
                this.$emit('schedule-calendar-filtering', msg.typeOfTask);
            },
            'schedule-calendar-more-tasks-show': function(msg){
                this.more_task_modal_title = msg;
                $('#more_tasks').modal('show');
            },
            'schedule-calendar-more-tasks-hide': function(msg){
                $('#more_tasks').modal('hide');
            },
            'schedule-calendar-download-was-done':function(msg){},
            'schedule-calendar-reload':function(msg){
                this.$emit('schedule-calendar-filtering', msg.typeOfTask);
            },
            'schedule-calendar-lazy-load-tasks': function(msg){
                var self = this;
                this.loadGeneralTasks(self.firstLoadAllTasks, function(){
                    self.lazyLoadTasks();
                    self.firstLoadAllTasks = false;
                });
            }

        },
        methods: {
            getDateLeft: function(date, days) {
                var dateCopy = new Date(date);
                dateCopy.setDate(date.getDate() + days);
                return dateCopy;
            },
            addRecurringTasks: function(recurringTasks, item){
                var recurringEveryWeekId  = 2; // need add 7 times
                var recurring2WeekId      = 3; // need add 4 times
                var recurringEveryMonthId = 4; // need add 2 times

                var calendarDays          = 49;// for 7 weeks
                var currentDate           = new Date(item.date);
                var lastDay               = this.getDateLeft(currentDate, calendarDays);

                if(!recurringTasks[App.formatDate(currentDate)]) recurringTasks[App.formatDate(currentDate)] = [];
                recurringTasks[App.formatDate(currentDate)].push(item);

                if(item.recurring == recurringEveryWeekId){
                    while(currentDate <= lastDay){
                        currentDate.setDate(currentDate.getDate() + 7);

                        var itemNew = $.extend({}, item);
                        itemNew.date = App.formatDate(currentDate);

                        if(!recurringTasks[App.formatDate(currentDate)]) recurringTasks[App.formatDate(currentDate)] = [];
                        recurringTasks[App.formatDate(currentDate)].push(itemNew);

                    };
                }

                if(item.recurring == recurring2WeekId){
                    while(currentDate <= lastDay){
                        currentDate.setDate(currentDate.getDate() + 14);
                        var itemNew = $.extend({}, item);
                        itemNew.date = App.formatDate(currentDate);

                        if(!recurringTasks[App.formatDate(currentDate)]) recurringTasks[App.formatDate(currentDate)] = [];
                        recurringTasks[App.formatDate(currentDate)].push(itemNew);


                    };
                }

                if(item.recurring == recurringEveryMonthId){
                    while(currentDate <= lastDay) {
                        currentDate.setMonth(currentDate.getMonth() + 1);

                        var itemNew = $.extend({}, item);
                        itemNew.date = App.formatDate(currentDate);

                        if (!recurringTasks[App.formatDate(currentDate)]) recurringTasks[App.formatDate(currentDate)] = [];
                        recurringTasks[App.formatDate(currentDate)].push(itemNew);

                    }
                }


            },
            showAllTasksForRabbit: function (rabbit, type) {
                this.activeRabbit = rabbit;
                var ntype = (type != 'general') ? type : 'users';
                var typeId = this.activeRabbit.id ? '/' + this.activeRabbit.id : '';
                var request = '/admin/'+ ntype + typeId +'/events';
                var titleForModal = '';
                var self = this;
                var recurringTasks = {};

                var recurringOnceId       = 1;

                this.$http.get(request, {}, function (data) {
                    var recurringTasks = {};
                    var currentDate = new Date();

                    data.forEach(function(item, i, arr){
                        var itemDate = new Date(item);
                        if(item.recurring > recurringOnceId){
                            self.addRecurringTasks(recurringTasks, item);
                        }
                    });

                    if(type == 'general'){
                        var temp = [];
                        data.forEach(function(item, i, arr){if(item.type == 'general') temp.push(item);});
                        this.activeTasks = temp;
                        titleForModal = 'All General Tasks.';
                    } else {
                        this.activeTasks = [];
                        data.forEach(function(item, i, arr){
                            self.activeTasks.push($.extend({}, item));

                            $.each(recurringTasks, function(recurringTaskDate, i, arr){
                                if(recurringTaskDate == item.date){
                                    recurringTasks[recurringTaskDate].forEach(function(recurringItem, i, arr){
                                        if(recurringTaskDate != item.date){
                                            self.activeTasks.push($.extend({}, recurringItem))
                                        }
                                    });

                                    delete recurringTasks[recurringTaskDate];
                                }
                            });
                        });

                        $.each(recurringTasks, function(recurringTaskDate, i, arr){
                            recurringTasks[recurringTaskDate].forEach(function(item, i, arr){
                               self.activeTasks.push($.extend({}, item));
                            });
                        });

                        if(ntype == 'litters') titleForModal = 'All Tasks for Litter ' + this.activeRabbit.given_id;
                        else titleForModal = 'All Tasks for Breeder ' + this.activeRabbit.name;
                    }
                    this.$emit('schedule-calendar-more-tasks-show', titleForModal);
                });
            },
            showTasksForCurrentWeek: function(msg){
                this.activeRabbit = msg.rabbit;
                var weekStart   = msg.events.start_date;
                var type        = msg.type != 'general' ? msg.type : 'users';
                var request = '/admin/'+type+'/'+this.activeRabbit.id+'/events?weekStart='+weekStart;

                if(type == 'users'){
                    request = '/admin/'+type+'/events?weekStart='+weekStart;
                }

                this.$http.get(request, {}, function (data) {
                    this.activeTasks = data;
                    this.$emit('schedule-calendar-download-was-done');
                });

                this.$emit('schedule-calendar-more-tasks-show');
            },
            parseWeeklyEvents: function(events, arrayData){
                var eventsForDates = [];
                arrayData.forEach(function(itemData, dateIndex, arr){
                    eventsForDates[dateIndex] = [];
                    events.forEach(function(itemEvent, i, arr){
                        if(itemData.date === itemEvent['date']){
                            eventsForDates[dateIndex].push(itemEvent);
                        }
                    });
                });
                return eventsForDates;
            },
            loadBreederTasks: function(){
                var request = "/admin/events?for=breeder";
                this.$http.get(request, {perPage: this.perPage}, function (data) {
                    var temp = [];
                    data['data'].forEach(function(item, i, arr){
                        item.type = 'breeders';
                        temp.push(item);
                    });
                    this.calendar_events = temp;
                    this.$emit('schedule-calendar-download-was-done');
                });
            },
            loadGeneralTasks: function(attachGeneralTasks, callbackFunction){
                var request = "/admin/events?for=user";
                this.$http.get(request, {perPage: this.perPage}, function (data) {
                    if(attachGeneralTasks){
                        var general_events = [];
                        var dataIsEmpty = false;

                        data.forEach(function(item, i, arr){
                            if(item.count) dataIsEmpty = true;
                        });

                        if(dataIsEmpty) general_events.push({events: data, type : 'general'});

                        this.calendar_events = general_events;
                    }

                    if(callbackFunction) callbackFunction();

                    this.$emit('schedule-calendar-download-was-done');
                });
            },
            loadLitterTasks: function(){
                var request = "/admin/events?for=litter&weekStart=" + App.formatDate(this.getFirstDayOfCurrentWeek());
                this.$http.get(request, {perPage: this.perPage}, function (data) {
                    var temp = [];
                    data['data'].forEach(function(item, i, arr){
                        item.type = 'litters';
                        temp.push(item);
                    });
                    this.calendar_events = temp;
                });
            },
            loadCalendarEvents: function(type){
                switch(type){
                    case 'general': {
                        this.loadGeneralTasks(true); break;}
                    case 'litter':  {
                        this.loadLitterTasks();  break;}
                    case 'breeder': {
                        this.loadBreederTasks(); break;}
                    case 'all':{}
                    default: {
                        this.skipBreeders = 0;
                        this.firstLoadAllTasks = true;
                        this.skipLitters = 'all';
                        this.$emit('schedule-calendar-lazy-load-tasks', {cleanCalendar: true});
                    };
                }
            },
            openTaskForm: function(events, rabbit, type){
                if(events.count == 1){
                    if(!events.event.breed_id){
                        this.$dispatch('task-managing', events.event);
                    }
                } else if( !events.count){
                    this.$dispatch('task-managing', events);
                } else {
                    this.$dispatch('schedule-calendar-all-task-of-current-week', {rabbit: rabbit, events: events, type: type});
                }
            },
            makeTimelineDate: function(date){
                return moment(date).format('MM/DD/YY');
            },
            getFirstDayOfCurrentWeek: function(){
                var dayForTimTable = 7;
                var today = new Date();
                var currentYear = today.getFullYear();
                var newYear = new Date(currentYear, 0, 1);
                var newYearDay = newYear.getDay();
                var delta = Math.floor((today.getTime() - newYear.getTime())/1000/60/60/24);
                var wNum = Math.floor((delta + newYearDay)/7) - 1;
                var n = wNum;
                var w = 1;
                var firstDay = newYear.getDay();
                var days = firstDay == w ? 1 : (n + 1) * 7 + w - (firstDay - 1);
                var mondayOfCurrentWeek = new Date(currentYear, 0, days);
                return mondayOfCurrentWeek; //return date of Monday for current week.
            },
            makeCalendarLabels: function(){
                this.week = [];
                var dayForTimTable = 7;
                var mondayOfCurrentWeek = this.getFirstDayOfCurrentWeek();
                today = App.formatDate(new Date());
                this.week.push({date: App.formatDate(mondayOfCurrentWeek)});

                for(var i=1; i < dayForTimTable; i++){
                    mondayOfCurrentWeek.setDate(mondayOfCurrentWeek.getDate()+7);
                    var newData = App.formatDate(mondayOfCurrentWeek);
                    var comment = null;
                    if(today == newData) comment = 'Today';

                    this.week.push({date: newData, comment: comment});
                }
            },
            showRequestForm: function(task){
                this.$dispatch('events-for-deleting', task);
                this.$emit('schedule-calendar-more-tasks-hide', task);
            },
            lazyLoadTasks: function(){
                var request = "/admin/events";
                var self = this;

                this.$http.get(request, {'for':'all', 'perPage':this.perPage, 'skipBreeders': this.skipBreeders, 'skipLitters': this.skipLitters}, function (data) {
                    if(data['breeders']){
                        var countOfRabbits = 0;
                        var fetchedAll = false;

                        data['breeders'].forEach(function(item, i, arr){
                            if(item.events){
                                item['type'] = 'breeders';
                                self.calendar_events.push(item);
                                countOfRabbits++;
                            } else if(item == 'fetchedAll'){
                                fetchedAll = true;
                            }
                        });

                        this.skipBreeders = (fetchedAll) ? 'all' : countOfRabbits;

                        if(this.skipBreeders == 'all') {
                            this.skipLitters = 0;
                            this.$emit('schedule-calendar-lazy-load-tasks', {});
                        }
                    };

                    if(data['litters']){
                        countOfRabbits = 0;
                        var fetchedAll = false;
                        data['litters'].forEach(function(item, i, arr){
                            if(item.events){
                                item['type'] = 'litters';
                                self.calendar_events.push(item);
                            } else if(item == 'fetchedAll'){
                                fetchedAll = true;
                            }
                        });
                        this.skipLitters = (fetchedAll) ? 'all' : countOfRabbits;
                        if(this.skipLitters == 'all') this.skipBreeders = 'all';
                    }
                });
            },
            scrollHandlerLazyLoad: function(event){
                var newScrollLeft = this.scrollableElement.scrollLeft();
                var width         = this.scrollableElement.width();
                var scrollWidth   = this.scrollableElement.get(0).scrollWidth;

                if ((scrollWidth - newScrollLeft - width) <= 1) {
                    this.$emit('schedule-calendar-lazy-load-tasks', {});
                }
            },

            eventTitle: function(event){
                if(event.name && event.date){
                    return event.name +': '+ event.date
                }
                return "View All";
            }
        },
        ready: function () {
            this.makeCalendarLabels();
            this.scrollableElement = $(this.scrollableElementId);
            this.$dispatch('schedule-calendar-filtering', 'all');
        },
    };


})();