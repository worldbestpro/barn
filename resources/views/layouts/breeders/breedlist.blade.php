<template id="breeders-template">


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Breeders
            <div class="btn-group">       
            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="false">
              <span class="caret"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul role="menu" class="dropdown-menu">
              <li><a v-link="{ path: '/breeders', activeClass: 'bold', exact: true }" href="#">All</a></li>
              <li><a v-link="{ path: '/breeders/does', activeClass: 'bold' }" href="#">Does</a></li>
              <li><a v-link="{ path: '/breeders/bucks', activeClass: 'bold' }" href="#">Bucks</a></li>
              <li><a v-link="{ path: '/breeders/archive', activeClass: 'bold' }" href="#">Archived</a></li>
              <li class="divider"></li>
              <li><a href="#" @click.prevent="addNew">Add New</a></li>
            </ul>
          </div>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#" v-link="{ path: '/' }"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Breeders</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Your Page Content Here -->

        <div class="row">
            <div class="col-md-2 col-xs-6 pull-right"><select v-model="order" class="form-control minimal">
                    <option value="id">Sort By:</option>
                    <option value="name">Name</option>
                    <option value="tattoo">ID</option>
                    <option value="cage">Cage</option>
                </select><br></div>
        </div>
        
        <div class="row" v-if="loading">
            <div class="col-md-12">
                <img src="/img/ajax-loader.gif" alt="Loading..." class="loader">
            </div>
        </div>

        <div class="row" v-if="!loading && !breeders.length">
            <div class="col-md-12">
                <h3 class="text-orange">No breeders</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6" v-for="breed in breeders">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user cursor-pointer" v-bind:id="'id_'+breed.id" v-link="{ path: '/profile/'+breed.id }">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header" v-bind:class="getGenderClass(breed.sex)">
                        <div class="btn-group pull-right">
{{--                            <a role="button" href="#" v-link="{ path: '/profile/'+breed.id }" class="btn btn-outline"><i
                                        class="fa fa-pencil"></i></a>--}}
                            <button v-if="breed.archived == '0'" @click.prevent="editModal(breed)" class="btn btn-outline" title="Edit"><i class="fa fa-pencil"></i></button>
                            <button v-if="breed.archived == '0'" @click.prevent="archiveModal(breed)" class="btn btn-outline" title="Archive"><i class="fa fa-archive"></i></button>
                            <button v-if="breed.archived == '1'" @click.prevent="unarchiveModal(breed)" class="btn btn-outline" title="Unarchive"><i class="fa fa-expand"></i></button>
                            <button @click.prevent="deleteModal(breed)" class="btn btn-outline" title="Delete"><i class="fa fa-trash"></i></button>
                        </div>
                        <div class="pull-left">
                            <h3 class="widget-user-username">@{{ breed.name }}</h3>
                            <h5 class="widget-user-desc">Cage: @{{ breed.cage }} </h5>
                            <h5 class="widget-user-desc">ID: @{{ breed.tattoo }}</h5>
                            <h5 class="widget-user-desc">Breed: @{{ breed.breed }}</h5>
                        </div>
                    </div>
                    <div class="widget-user-image breeder">
                        <img v-bind:alt="breed.name" src="img/rabbit1.jpg" v-bind:src="breed.image.path" class="img-circle">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-xs-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        <span v-if="breed.litters_count">@{{ breed.litters_count }}</span>
                                        <span v-if="!breed.litters_count">&mdash;</span>
                                    </h5>
                                    <span class="description-text">LITTERS</span>
                                </div><!-- /.description-block -->
                            </div>
                            <div class="col-xs-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        <span v-if="breed.kits">@{{ breed.kits }}</span>
                                        <span v-if="!breed.kits">&mdash;</span>
                                    </h5>
                                    <span class="description-text">KITS</span>
                                </div><!-- /.description-block -->
                            </div><!-- /.col -->
                            <div class="col-xs-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        <span v-if="breed.live_kits">@{{ breed.live_kits }}</span>
                                        <span v-if="!breed.live_kits">&mdash;</span>
                                    </h5>
                                    <span class="description-text">LIVE KITS</span>
                                </div><!-- /.description-block -->
                            </div><!-- /.col -->
                            <div class="col-xs-3">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        <span v-if="breed.weight">@{{ breed.weight_slug }} <!--@{{ breed.user.weight_slug }}--></span>
                                        <span v-if="!breed.weight">&mdash;</span>
                                    </h5>
                                    <span class="description-text">weight</span>
                                </div><!-- /.description-block -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div>
                </div><!-- /.widget-user --> </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- /.row -->


        <div class="pull-right" v-show="pages > 1">
            <ul class="pagination">
                <li class="paginate_button previous" v-bind:class="{ disabled: page == 1 }">
                    <a href="#" tabindex="0" @click.prevent="prevPage">Previous</a>
                </li>

                <li class="paginate_button" v-for="_page in pages" v-bind:class="{ active: _page+1 == page }">
                    <a href="#" tabindex="0" v-link="{ path: currentRoute, query: {page: _page+1} }">@{{ _page+1 }}</a>
                </li>

                <li class="paginate_button next" v-bind:class="{ disabled: page == pages }">
                    <a href="#" tabindex="0" @click.prevent="nextPage">Next</a>
                </li>
            </ul>
        </div>


        <div class="row">
            <div class="col-lg-4 col-md-6"><a href="#" @click.prevent="addNew">
                    <div class="info-box">
                        <span class="info-box-icon bg-gray text-muted"><i class="fa fa-plus"></i></span>

                        <div class="info-box-content text-muted"><h1>Add New</h1>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </a>
            </div>
        </div>

    </section><!-- /.content -->


    @include('layouts.breeders.partials.breeder')
    @include('layouts.archive-delete')

    @include('layouts.litter.modals.weight')
    @include('layouts.litter.modals.litter')
    @include('layouts.litter.modals.butcher')
    @include('layouts.litter.modals.litter-birth')

</template>

