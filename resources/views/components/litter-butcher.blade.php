<template id="litter-butcher-template">

    <div class="modal-header bg-red">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Butcher Litter</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal row-paddings-compensation">
            <div class="row">
                <div class="form-group col-xs-7 col-sm-6 col-xs-12">
                    <label class="col-sm-4 control-label">Litter</label>
                    <div class="col-sm-8">
                        <select class="form-control" v-model="selectedLitter">
                            <option value="0" v-if="!litter.id">select litter</option>
                            <option v-for="item in litters" v-bind:value="item.id">Litter: @{{ item.given_id }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-xs-7 col-sm-6 col-xs-12">
                    <label class="col-sm-4 control-label">Date</label>
                    <div class="col-sm-8">
                        <div id="datepick" class="input-group date" v-datepicker="date">
                            <input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    Select the fryers to be butchered
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th class="col-xs-1"><input type="checkbox" v-el:checkall></th>
                            <th class="col-xs-2">ID</th>
                            <th class="col-xs-2"></th>
                            <th class="col-xs-1">Color</th>
                            <th class="text-center">Weight</th>
                        </tr>
                        <tr v-for="kit in kits" v-bind:class="getGenderClass(kit.sex)">
                            <td><input type="checkbox" v-model="kit.selected" class="js_butcher_checkbox"></td>
                            <td>@{{ kit.given_id }}</td>
                            <td>
                                <img class="img-responsive img-circle" v-bind:src="kit.image.path">
                            </td>
                            <td>@{{ kit.color }}</td>
                            <td><input type="text" data-mobile-type="number" v-model="kit.current_weight" class="form-control js_only-numbers"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        </form>
    </div>
    <div class="modal-footer bg-danger">
        <button data-dismiss="modal" class="btn btn-default pull-left" type="button">Close
        </button>
        <button class="btn btn-danger" type="button" @click="sendToButcher">Save changes</button>
    </div>

</template>