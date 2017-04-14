<template id="users-template" xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>@{{ usersTotal }}</h3>
                        <p>Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>

                </div>
            </div><div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>@{{ breedersTotal }}</h3>
                        <p>Breeders</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-venus-mars"></i>
                    </div>

                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>@{{ littersTotal }}</h3>
                        <p>Litters</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-th"></i>
                    </div>

                </div>
            </div><!-- ./col -->
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>@{{ kitsTotal }}</h3>
                        <p>Kits</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-birthday-cake"></i>
                    </div>

                </div>
            </div><!-- ./col -->
        </div>
        <div class="box box-solid box-default">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-user"></i> Users</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in users">
                        <td>@{{ user.name }}</td>
                        <td>@{{ user.email }}</td>
                        <td>
                            <a v-link="{ name: 'userEdit', params: { userId: user.id }}">
                                <i class="fa fa-pencil fa-2x"></i>
                            </a>
                        </td>
                        <td><a @click.prevent="confirmDelete(user)" href="#"><i class="fa fa-times fa-2x"></i></a></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pull-right">
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
            <div class="col-md-3 col-sm-6 col-xs-12"><a data-toggle="modal" role="button" v-link="{ path: '/users/create' }" href="#">
                    <div class="info-box">
                        <span class="info-box-icon bg-gray text-muted"><i class="fa fa-plus"></i></span>
                        <div class="info-box-content text-muted"><h1>Add New</h1>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </a>
            </div>
        </div>
    </section>


    <div class="modal modal-default" id="delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body bg-gray">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h3><i class="fa fa-archive"></i><br>
                                Are you sure you want to delete this user?</h3>
                        </div>
                    </div>
                    <div class="row margin">
                        <div class="col-sm-12 text-center">
                            <button class="btn btn-default" type="button" data-dismiss="modal" @click="deleteUser()">
                                <i class="fa fa-check"></i> Yes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <i class="fa fa-close"></i> No
                            </button>
                        </div>
                    </div>

                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

</template>