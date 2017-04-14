<template id="litters-template">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Litters
            <div class="btn-group">       
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="false">
              <span class="caret"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul role="menu" class="dropdown-menu">
              <li><a v-link="{ path: '/litters', activeClass: 'bold', exact: true }" href="#">All</a></li>
              <li><a v-link="{ path: '/litters/archive', activeClass: 'bold' }" href="#">Archived</a</li>
              <li class="divider"></li>
              <li><a href="#" @click.prevent="newLitter">Add New</a></li>
            </ul>
          </div>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#" v-link="{ path: '/' }"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Litters</li>
        </ol>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-2 col-xs-6 pull-right"><select v-model="order" class="form-control minimal">
                    <option value="id|asc">Sort By:</option>
                    <option value="given_id|asc">ID</option>
                    <option value="born|desc">Age (asc)</option>
                    <option value="born|asc">Age (desc)</option>
                </select><br></div>
        </div>
        
        <div class="row" v-if="loading">
            <div class="col-md-12">
                <img src="/img/ajax-loader.gif" alt="Loading..." class="loader">
            </div>
        </div>

        <div class="row" v-if="!loading && !litters.length">
            <div class="col-md-12">
                <h3 class="text-orange">No litters</h3>
            </div>
        </div>
        <!-- Your Page Content Here -->
        <div class="row">

            <div class="col-lg-4 col-md-6" v-for="litter in litters">
                <!-- Widget: user widget style 1 -->
                <div v-link="{ path: '/litterprofile/'+litter.id }" class="box box-widget widget-user cursor-pointer display-block" v-bind:id="'id_'+litter.id">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header"
                         v-bind:class="{ 'bg-olive': litter.archived=='0', 'bg-gray-active': litter.archived=='1' }">
                        <div class="btn-group pull-right box-tools ">

                            <button v-if="litter.archived=='0'" @click.prevent="weightModal(litter)" class="btn btn-outline btn-sm" title="Weigh">
                                <i class="fa fa-balance-scale"></i></button>
                            <button v-if="litter.archived=='0'" @click.prevent="butcherModal(litter)" class="btn btn-outline btn-sm" title="Butcher"><i
                                        class="fa fa-cutlery"></i></button>
                            <button v-if="litter.archived=='0'" @click.prevent="archiveModal(litter)" class="btn btn-outline btn-sm" title="Archive"><i class="fa fa-archive"></i></button>
                            <button v-if="litter.archived=='1'" @click.prevent="unarchiveModal(litter)" class="btn btn-outline btn-sm" title="Unarchive"><i class="fa fa-expand"></i></button>
                            <button @click.prevent="deleteModal(litter)" class="btn btn-outline btn-sm" title="Delete"><i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="pull-left">
                            <h3 class="widget-user-username">@{{ father(litter.parents).name }}<br>@{{ mother(litter.parents).name }}</h3>
                            <h5 class="widget-user-desc">Litter: @{{ litter.given_id }}</h5>
                        </div>

                    </div>
                    <div class="widget-user-image pull-left litter">
                        <img class="img-circle" v-bind:src="father(litter.parents).image.path" v-bind:alt="father(litter.parents).name">
                        <img class="img-circle" v-bind:src="mother(litter.parents).image.path" v-bind:alt="mother(litter.parents).name">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-xs-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header nobreak">@{{ calcKits(litter) }}</h5>
                                    <span class="description-text">KITS</span>
                                </div><!-- /.description-block -->
                            </div>
                            <div class="col-xs-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header nobreak">@{{ litter.total_weight_slug.total }} <!--<span v-if="litter.total_weight">@{{ litter.weight_unit_short }}</span>--> <span v-if="!litter.total_weight && litter.total_weight != 0">&mdash;</span></h5>
                                    <span class="description-text">total</span>
                                </div><!-- /.description-block -->
                            </div><!-- /.col -->
                            <div class="col-xs-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header nobreak">@{{ litter.total_weight_slug.average }} <!--<span v-if="litter.average_weight">@{{ litter.weight_unit_short }}</span>--> <span v-if="!litter.average_weight && litter.average_weight != 0">&mdash;</span></h5>
                                    <span class="description-text">average</span>
                                </div><!-- /.description-block -->
                            </div><!-- /.col -->
                            <div class="col-xs-3">
                                <div class="description-block">
                                    <h5 class="description-header nobreak">@{{ age(litter.born) }}</h5>
                                    <span class="description-text">age</span>
                                </div><!-- /.description-block -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div>
                </div><!-- /.widget-user -->
            </div><!-- /.col -->
        </div><!--- /.row -->


        <div class="row">
            <div class="col-lg-4 col-md-6">
                <a href="#" @click.prevent="newLitter">
                    <div class="info-box">
                        <span class="info-box-icon bg-gray text-muted"><i class="fa fa-plus"></i></span>

                        <div class="info-box-content text-muted"><h1>Add New</h1>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </a>
            </div>
        </div>

    </section>

    @include('layouts.litter.modals.weight')
    @include('layouts.litter.modals.litter')
    @include('layouts.litter.modals.butcher')
    @include('layouts.litter.modals.litter-birth')
    @include('layouts.archive-delete')

</template>

