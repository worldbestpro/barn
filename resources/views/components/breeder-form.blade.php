<template id="breeder-form-template">

    <div class="modal-header bg-success" v-bind:class="{ 'bg-info': breeder.id }">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <span v-if="breeder.id">Edit</span>
            <span v-if="!breeder.id">New</span> Breeder
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
                <div class="form-group col-sm-6 col-xs-7" v-bind:class="{ 'has-warning': warnings.tattoo }">
                    <label class="col-sm-4 control-label">ID</label>
                    <div class="col-sm-8">
                        <input type="text" v-model="breeder.tattoo" id="breeder-tattoo" @keyup="checkDoubledId | debounce 300" placeholder="Enter ..."
                               class="form-control typeahead">
                        <small class="warnings" v-if="warnings.tattoo">@{{ warnings.tattoo[0] }}</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 col-xs-7">
                    <label class="col-sm-4 control-label">Cage</label>
                    <div class="col-sm-8">
                        <input type="text" v-model="breeder.cage" id="breeder-cage" placeholder="Enter ..." class="form-control typeahead">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-7">
                    <label class="col-sm-4 control-label">Color</label>
                    <div class="col-sm-8">
                        <input type="text" v-model="breeder.color" id="breeder-color" placeholder="Enter ..." class="form-control typeahead">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6 col-xs-7">
                    <label class="col-sm-4 control-label">Breed</label>
                    <div class="col-sm-8">
                        <input type="text" v-model="breeder.breed" id="breeder-breed" class="form-control typeahead" placeholder="Enter ...">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-7">
                    <label class="col-sm-4 control-label">Weight</label>
                    <div class="col-sm-8">
                        <input type="number" v-model="breeder.weight" placeholder="Enter ..."
                               class="form-control"  min="0" step=".1">
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
                <div class="form-group col-xs-7 col-sm-6">
                    <label class="col-sm-4 control-label">Aquired</label>
                    <div class="col-sm-8">
                        <div class="input-group date" v-datepicker="breeder.aquired" id="datepick">
                            <input type="text" class="form-control">
                            <span class="input-group-addon">
                            <i class="glyphicon glyphicon-th"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6 col-xs-7">
                    <label class="col-sm-4 control-label">Father</label>
                    <div class="col-sm-8">
                        <select id="buck_select" class="form-control" v-model="breeder.father_id">
                            <option value="0">Choose</option>
                            <option v-for="buck in bucks" v-bind:value="buck.id">
                                @{{ buck.name }}: @{{ buck.tattoo }}
                            </option>
                            <option value="-1">Other ...</option>
                        </select>
                        <div v-if="breeder.father_id == -1">
                            <div class="clearfix">
                                <input style="width: 60%;float: left;" type="text" v-model="newBuck.name" placeholder="name" class="form-control">
                                <input style="width: 40%;float: left;" type="text" v-model="newBuck.tattoo" placeholder="ID" class="form-control">
                            </div>
                            <a href="#" @click.prevent="addNewBuck" class="btn btn-block btn-success"><i class="fa fa-plus"></i> Add father buck</a>
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm-6 col-xs-7">
                    <label class="col-sm-4 control-label">Mother</label>
                    <div class="col-sm-8">
                        <select class="form-control" v-model="breeder.mother_id">
                            <option value="0">Choose</option>
                            <option v-for="doe in does" v-bind:value="doe.id">
                                @{{ doe.name }}: @{{ doe.tattoo }}
                            </option>
                            <option value="-1">Other ...</option>
                        </select>
                        <div v-if="breeder.mother_id == -1">
                            <div class="clearfix">
                                <input style="width: 60%;float: left;" type="text" v-model="newDoe.name" placeholder="name" class="form-control">
                                <input style="width: 40%;float: left;" type="text" v-model="newDoe.tattoo" placeholder="ID" class="form-control">
                            </div>
                            <a href="#" @click.prevent="addNewDoe" class="btn btn-block btn-success"><i class="fa fa-plus"></i> Add mother doe</a>
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
