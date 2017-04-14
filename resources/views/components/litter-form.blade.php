<template id="litter-form-template">
    <div class="modal-header bg-success" v-bind:class="{ 'bg-info': litter.id }">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">
            <span v-if="!litter.id">New</span>
            <span v-if="litter.id">Edit</span>
            Litter</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal row-paddings-compensation">

            <div class="row">
                <div class="form-group col-xs-7 col-sm-6" v-bind:class="{ 'has-error': errors.given_id }">
                    <label class="col-sm-4 control-label">Litter ID</label>
                    <div class="col-sm-8">
                        <input type="text" v-model="litter.given_id" class="form-control" placeholder="Enter ...">
                        <small class="error" v-if="errors.given_id">@{{ errors.given_id[0] }}</small>
                    </div>
                </div>

                <div class="form-group col-xs-7 col-sm-6" v-bind:class="{ 'has-error': errors.kits_amount }">
                    <label class="col-sm-4 control-label"># Kits</label>
                    <div class="col-sm-8">
                        <input v-bind:disabled="litter.weighs > 0" type="text" v-model="litter.kits_amount" data-mobile-type="number" class="form-control" placeholder="Enter ...">
                        <small class="error" v-if="errors.kits_amount">@{{ errors.kits_amount[0] }}</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-xs-7 col-sm-6">
                    <label class="col-sm-4 control-label">Buck</label>

                    <div class="col-sm-8">
                        <select class="form-control" v-model="litter.father_id">
                            <option value="0" selected>Choose</option>
                            <option v-for="buck in bucks" v-bind:value="buck.id">
                                @{{ buck.name }}: @{{ buck.tattoo }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-xs-7 col-sm-6">
                    <label class="col-sm-4 control-label">Doe</label>

                    <div class="col-sm-8">
                        <select class="form-control" v-model="litter.mother_id">
                            <option value="0" selected>Choose</option>
                            <option v-for="doe in does" v-bind:value="doe.id">
                                @{{ doe.name }}: @{{ doe.tattoo }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6 col-xs-7">
                    <label class="col-sm-4 control-label">Bred</label>

                    <div class="col-sm-8">
                        <div id="datepick" class="input-group date" v-datepicker="litter.bred">
                            <input type="text" class="form-control"><span class="input-group-addon"><i
                                        class="glyphicon glyphicon-th"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-7">
                    <label class="col-sm-4 control-label">Born</label>

                    <div class="col-sm-8">
                        <div id="datepick" class="input-group date" v-datepicker="litter.born">
                            <input type="text" class="form-control"><span class="input-group-addon"><i
                                        class="glyphicon glyphicon-th"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12">
                    <label class="col-sm-2 control-label">Notes</label>

                    <div class="col-sm-10"><textarea v-model="litter.notes" class="form-control" rows="3" placeholder="Descriptions"></textarea>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <div class="modal-footer bg-success" v-bind:class="{ 'bg-info': litter.id }">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" @click="sendLitter" class="btn btn-success" v-bind:class="{ 'btn-info': litter.id }">Save changes</button>
    </div>
</template>