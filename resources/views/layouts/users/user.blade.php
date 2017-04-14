<template id="user-template">
    <section class="content">

        <div class="box box-solid box-success">
            <div class="box-header">Create user</div>
            <div class="box-body">

                <form autocomplete="off" class="user__form" @submit.prevent="sendUser" method="POST">
                    <div class="col-md-6">
                        <!--Name-->
                        <div class="form-group" v-bind:class="{ 'has-error': errors.name }">
                            <input class="form-control" placeholder="Name" autocomplete="off" type="text"
                                   name="username"
                                   v-model="user.name"/>
                            <span class="val-error" v-if="errors.name">@{{errors.name}}</span>
                        </div>
                        <!--Email-->
                        <div class="form-group" v-bind:class="{ 'has-error': errors.email }">
                            <input class="form-control" placeholder="E-mail" autocomplete="off" type="email"
                                   name="email"
                                   v-model="user.email"/>
                            <span class="val-error" v-if="errors.email">@{{errors.email}}</span>
                        </div>
                        <!--Role-->
                        <div class="form-group" v-bind:class="{ 'has-error': errors.role }">
                            <select class="form-control" v-model="user.role">
                                <option value="0" selected>Choose</option>
                                <option v-for="role in userRoles" v-bind:value="role.id">
                                    @{{ role.display_name }}
                                </option>
                            </select>
                        </div>

                        <!--Password-->
                        <div class="form-group" v-bind:class="{ 'has-error': errors.password }">
                            <input class="form-control" type="password" v-if="!user.id" placeholder="password"
                                   name="password"
                                   v-model="user.password"/>
                            <input class="form-control" type="password" v-else placeholder="new password"
                                   name="password"
                                   v-model="user.password"/>
                            <span class="val-error" v-if="errors.password">@{{errors.password}}</span>
                        </div>
                        <input type="hidden" name="id" v-model="user.id">
                        <input type="submit" class="btn btn-submit" v-if="!editMode" value="Create"/>
                        <input type="submit" class="btn btn-submit" v-else value="Update"/>
                        <a v-link="{ path: '/users' }" class="btn btn-danger" href="#" role="button">Cancel</a>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo">Avatar:</label>

                            <input id="logo" v-el:image type="file" name="image">

                            <p class="help-block">Please select a photo</p>

                            <div v-if="user.image.name">
                                <img class="img-responsive" v-bind:src="user.image.path" v-if="user.image.path">

                                <div v-if="!user.image.delete">
                                    <i @click.prevent="deleteImage(image)" class="fa fa-times pull-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>

</template>