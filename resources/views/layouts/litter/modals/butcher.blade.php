<!--- Butcher --->
<div id="litter-butcher-modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <litter-butcher :litters.sync="litters" :litter.sync="activeLitter" :kits.sync="activeKits"></litter-butcher>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
