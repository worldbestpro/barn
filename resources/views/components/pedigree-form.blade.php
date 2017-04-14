<template id="pedigree-form-template">

    <div class="modal-header bg-success" v-bind:class="{ 'bg-info': breeder.id }">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <span v-if="breeder.id">Edit</span>
            <span v-if="!breeder.id">New</span> Entry
        </h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal row-paddings-compensation">
            <input v-if="breeder.id !=0" name="_method" v-model="breeder._method" type="hidden" value="PUT">
            <div class="row">
                <div class="form-group col-sm-6 col-xs-7" v-bind:class="{ 'has-error': errors.name }">
                    <label class="col-sm-4 control-label">Name</label>
                    <div class="col-sm-8">
                        <input type="text" v-model="breeder.name" placeholder="Enter ..." class="form-control">
                        <small class="error" v-if="errors.name">@{{ errors.name[0] }}</small>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-7" v-bind:class="{ 'has-warning': warnings.custom_id }">
                    <label class="col-sm-4 control-label">ID</label>
                    <div class="col-sm-8">
                        <input type="text" v-model="breeder.custom_id" @keyup="checkDoubledId | debounce 300" placeholder="Enter ..."
                               class="form-control" data-mobile-type="number">
                        <small class="warnings" v-if="warnings.custom_id">@{{ warnings.custom_id[0] }}</small>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="form-group col-sm-6 col-xs-7" v-bind:class="{ 'has-error': errors.day_of_birth }">
                    <label class="col-sm-4 control-label">DoB</label>
                    <div class="col-sm-8">
                        <div class="input-group date" id="datepick">
                            <input type="text" vv-model="breeder.day_of_birth" v-datepicker="breeder.day_of_birth" class="form-control">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-th"></i></span>
                        </div>
                        <small class="error" v-if="errors.day_of_birth">@{{ errors.day_of_birth[0] }}</small>
                    </div>
                </div>

                <div class="form-group col-sm-6 col-xs-7" v-bind:class="{ 'has-error': errors.breed }">
                    <label class="col-sm-4 control-label">Breed</label>
                    <div class="col-sm-8">
                        <div class="input-group " >
                            <input type="text" v-model="breeder.breed" class="form-control">

                        </div>
                        <small class="error" v-if="errors.breed">@{{ errors.breed[0] }}</small>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="form-group col-sm-6 col-xs-7">
                    <label class="col-xs-4 control-label">Sex</label>
                    <div class="col-xs-8">
                        <div class="icheck-group">
                            <input class="js_icheck-breeder-red"  type="radio" name="sex" value="doe" id="breeder-sex-doe" v-model="breeder.sex">
                            <label for="breeder-sex-doe" class="icheck-label"> Doe</label> <br />
                            <input class="js_icheck-breeder-blue" type="radio" name="sex" value="buck" id="breeder-sex-buck" v-model="breeder.sex">
                            <label for="breeder-sex-buck" class="icheck-label"> Buck</label>
                        </div>
                    </div>
                </div>
            </div>




            <div class="row">
                <image-upload :breeder.sync="breeder"></image-upload>
            </div>

            <div class="row">
                <div class="form-group col-sm-12">
                    <label class="col-sm-2 control-label">Notes</label>
                    <div class="col-sm-10">
                            <textarea v-model="breeder.notes" placeholder="Descriptions" rows="3"
                                      class="form-control"></textarea>
                    </div>
                </div>
            </div>

        </form>

    </div>
    <div class="modal-footer bg-success" v-bind:class="{ 'bg-info': breeder.id }">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" @click="sendBreeder" class="btn btn-success" v-bind:class="{ 'btn-info': breeder.id }">Save changes</button>
    </div>

</template>
