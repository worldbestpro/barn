<template id="kit-form-template">
    <div class="modal-header bg-success">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Edit Kit</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal row-paddings-compensation">

            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="inputEmail3" class="col-sm-4 control-label">Litter</label>
                    <div class="col-sm-8">
                        <select class="form-control" disabled>
                            <option selected>@{{ father().name  }}+@{{ mother().name  }} - @{{ litter.given_id }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="inputEmail3" class="col-sm-4 control-label">ID</label>
                    <div class="col-sm-8"><input type="text" class="form-control" v-model="kit.given_id"></div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="inputEmail3" class="col-sm-4 control-label">Color</label>
                    <div class="col-sm-8"><input type="text" class="form-control" v-model="kit.color"></div>
                </div>

                <div class="form-group col-sm-6">
                    <label for="inputPassword3" class="col-sm-4 control-label">Sex</label>
                    <div class="col-sm-8">
                        <div class="icheck-group">
                            <input class="js_icheck-kit-red"  type="radio" name="sex" value="doe" id="kit-sex-doe" v-model="kit.sex">
                            <label for="kit-sex-doe" class="icheck-label"> Doe</label> &nbsp;
                            <input class="js_icheck-kit-blue" type="radio" name="sex" value="buck" id="kit-sex-buck" v-model="kit.sex">
                            <label for="kit-sex-buck" class="icheck-label"> Buck</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-xs-12">
                    <label for="inputPassword3" class="col-sm-2 control-label">Weight</label>
                    <div v-if="kit.weight.length > 0">
                        <div class="col-sm-2" v-for="(index, item) in kit_weight">
                            <input type="number" v-model="kit.weight[index]" class="form-control" {{--v-bind:value="item.value"--}}  min="0" step=".1">
                        </div>
                    </div>
                    <div class="col-sm-2" v-if="!kit.weight || kit.weight.length < 3"><input type="number" class="form-control" v-model="kit.new_weight"  min="0" step=".1"></div>
                </div>
            </div>

            <div class="row">
                <image-upload :breeder.sync="kit"></image-upload>
            </div>

            <div class="row">
                <div class="form-group col-xs-12">
                    <label class="col-sm-2 control-label" for="exampleInputFile">Notes</label>
                    <div class="col-sm-10"><textarea v-model="kit.notes" rows="3" class="form-control"></textarea></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <button @click.prevent="kitDied(kit)" class="btn btn-danger" type="button">Died</button>
                    <button @click.prevent="makeBreeder(kit)" class="btn btn-info" type="button">Breeder</button>
                    <button v-if="kit.archived=='0'" @click.prevent="archive(kit)" class="btn btn-default" type="button">Archive</button>
                    <button v-if="kit.archived=='1'" @click.prevent="unarchive(kit)" class="btn btn-default" type="button">Unarchive</button>
                    <button @click.prevent="kitDelete(kit)" class="btn btn-default" type="button">Delete</button>
                </div>
            </div>

        </form>
    </div>
    <div class="modal-footer bg-success">
        <button data-dismiss="modal" class="btn btn-default pull-left" type="button">Close</button>
        <button class="btn btn-success" @click="saveKit(kit)" type="button">Save changes</button>
    </div>
</template>