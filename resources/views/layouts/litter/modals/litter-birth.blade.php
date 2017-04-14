<div class="modal" id="birth" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Report Birth</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal row-paddings-compensation">
                <div class="row">
                    <div class="form-group col-sm-6 col-xs-7">
                        <label class="col-sm-4 control-label">Breeders</label>
                        <div class="col-sm-8">
                            <select class="form-control" v-model="activeBirth.breedplan">
                                <option value="-1">Select plan</option>
                                <option v-for="plan in plans" value="@{{ plan.id }}">@{{ plan.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-xs-7">
                        <label class="col-sm-4 control-label">Litter ID</label>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Litter Number" v-model="activeBirth.given_id" class="form-control">
                        </div>
                    </div>
                 </div>
                 <div class="row">

                    <div class="form-group col-sm-6 col-xs-7">
                        <label class="col-sm-4 control-label">Date</label>
                        <div class="col-sm-8">
                            <div class="input-group date" v-datepicker="activeBirth.born">
                                <input type="text" class="form-control">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-th"></i>
                                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-sm-6 col-xs-7">
                        <label class="col-sm-4 control-label"># Kits</label>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Number Born" v-model="activeBirth.kits_amount"
                                   class="form-control">
                        </div>

                    </div>
				</div>
                 <div class="row">
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label">Notes</label>
                        <div class="col-sm-10"><textarea v-model="activeBirth.notes" placeholder="Descriptions" rows="3"
                                                         class="form-control"></textarea></div>
                    </div>
                  </div>

                </form>
            </div>
            <div class="modal-footer bg-success">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close
                </button>
                <button type="button" @click="recordBirth" class="btn btn-success">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>