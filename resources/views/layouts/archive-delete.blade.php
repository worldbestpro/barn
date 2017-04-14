<div class="modal modal-danger" id="delete-breed-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 text-center"><h3><i class="fa fa-fw fa-warning"></i><br>
                            Do you want to delete @{{ confirmTarget }}?</h3>
                    </div>
                </div>
                <div class="row margin">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-outline" type="button" @click="delete"><i class="fa fa-check"></i> Yes</button>
                        <button type="button" class="btn btn-outline" data-dismiss="modal"><i
                                    class="fa fa-close"></i> No
                        </button>
                        <button data-dismiss="modal" class="btn btn-outline" @click="archive" type="button"><i
                                    class="fa fa-archive"></i> Archive
                        </button>
                    </div>
                </div>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<div class="modal modal-default" id="archive-breed-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body bg-gray">
                <div class="row">
                    <div class="col-sm-12 text-center"><h3><i class="fa fa-archive"></i><br>
                            Do you want to archive @{{ confirmTarget }}?</h3>
                    </div>
                </div>
                <div class="row margin">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-default" type="button" @click="archive"><i class="fa fa-check"></i> Yes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                    class="fa fa-close"></i> No
                        </button>
                        <button data-dismiss="modal" class="btn btn-default" @click="delete" type="button"><i
                                    class="fa fa-trash"></i> Delete
                        </button>
                    </div>
                </div>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal modal-default" id="unarchive-breed-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body bg-gray">
                <div class="row">
                    <div class="col-sm-12 text-center"><h3><i class="fa fa-expand"></i><br>
                            Do you want to unarchive @{{ confirmTarget }}?</h3>
                    </div>
                </div>
                <div class="row margin">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-default" type="button" @click="unarchive"><i class="fa fa-check"></i> Yes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                    class="fa fa-close"></i> No
                        </button>
                    </div>
                </div>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>