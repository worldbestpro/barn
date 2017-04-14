<template id="litter-weight-template">
    <div class="modal-header bg-yellow">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                    aria-hidden="true">×</span></button>
        <h4 class="modal-title">Record Litter Weights</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">

            <div class="row">
                <div class="form-group col-xs-7 col-sm-6">
                    <label class="col-sm-4 control-label">Litter</label>
                    <div class="col-sm-8">
                        <select class="form-control" v-model="selectedLitter">
                            <option value="0" v-if="!litter.id">select litter</option>
                            <option v-for="item in litters" v-bind:value="item.id">@{{ item.parents[0].name }}+@{{ item.parents[1].name }} - @{{ item.given_id }}</option>
                        </select>
                    </div>
                </div>
{{--
                <div class="form-group col-xs-7 col-sm-6">
                    <label class="col-sm-4 control-label"># Kits</label>
                    <div class="col-sm-8">
                        <input type="text" data-mobile-type="number" value="8" class="form-control" v-model="litter.kits_amount">
                    </div>
                </div>
            </div>

            <div class="row">
--}}
                <div class="form-group col-xs-7 col-sm-6">
                    <label class="col-sm-4 control-label">Date</label>
                    <div class="col-sm-8">
                        <div id="datepick" class="input-group date" v-datepicker="date">
                            <input type="text" class="form-control"><span class="input-group-addon"><i
                                        class="glyphicon glyphicon-th"></i></span>
                        </div>

                    </div>
                </div>
            </div>

            <div v-el:form class="box box-success box-solid" v-if="first">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Kit  @{{ (current+1)+'/'+kits.length }}</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-xs-7 col-sm-6">
                            <label class="col-sm-4 control-label">ID</label>
                            <div class="col-sm-8"><input type="text" v-model="activeKit.given_id" class="form-control">
                                <div class="checkbox">
                                    <label><input v-model="generate" type="checkbox"> Generate IDs</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-7 col-sm-6">
                            <label class="col-sm-4 control-label">Color</label>
                            <div class="col-sm-8">
                                <input type="text" v-model="activeKit.color" class="form-control"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-xs-7 col-sm-6">
                            <label class="col-sm-4 control-label">Weight</label>
                            <div class="col-sm-8">
                                <input type="number" v-model="activeKit.current_weight" class="form-control"  min="0" step=".1">
                                
                            </div>
                        </div>
                        <div class="form-group col-sm-6 col-xs-7">
                            <label class="col-xs-4 control-label">Sex</label>
                            <div class="col-xs-8">
                                <sex-select :model.sync="activeKit"></sex-select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <image-upload v-on:uploaded="imageUploaded" :breeder.sync="activeKit"></image-upload>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label">Notes</label>

                            <div class="col-sm-10"><textarea class="form-control" rows="3" v-model="activeKit.notes"
                                                             placeholder="Descriptions"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row margin">
                        <div class="col-sm-12 text-center">

                            <button type="button" class="btn btn-default" @click="prevKit(kit)">
                                <i class="fa fa-fw fa-arrow-circle-left"></i> Previous
                            </button>
                            <button class="btn btn-success btn-lg" type="button" @click="nextKit(kit)">Next Kit
                                <i class="fa fa-fw fa-arrow-circle-right"></i>
                            </button>

                            <button class="btn btn-danger" type="button" @click="diedNextKit(kit)">Died
                            <i class="fa fa-fw fa-arrow-circle-right"></i>
                            </button>

                        </div>
                    </div>

                </div>
            </div>

            <label v-if="first"><input type="checkbox" v-model="showNavigator"> show all kits</label>

            <div class="row" v-if="showNavigator">

                <div class="col-sm-6" v-if="kits.length !== weighedKits"  v-for="(index, kit) in kits" v-bind:class="{ 'cursor-pointer': first }">
                    <div class="info-box" @click.prevent="setActiveKit(kit, index)" v-bind:class="getGenderClass(kit.sex)"
                         v-bind:id="'kit-'+kit.id">

                        <span class="info-box-icon">
                            <img class="img-responsive img-circle" src="media/breeders/default.jpg"
                                 v-bind:src="kit.image.path" style="max-width: 80%; margin:10px auto;">
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-number">@{{ kit.given_id }} - @{{ kit.color }}</span>
                                            <span class="small"><i
                                                        class="fa fa-balance-scale"></i>@{{  showWeights(kit) }}</span>

                            <div v-if="!first" class="input-group input-group-sm"{{-- v-bind:class="{ has-success: kit.saved }"--}}>
                                <input type="number" class="form-control" value="" v-model="kit.current_weight" placeholder="Weight"
                                       name="message" min="0" step=".1">
                                <span class="input-group-btn">
                                    <button @click.prevent="saveWeight(kit)" type="button" class="btn btn-outline">
                                        Save
                                    </button>
                                </span>
                            </div>
                            <div style="margin-top: 5px">
                                <button v-if="first" @click.prevent="diedKit(kit)" class="btn btn-danger btn-xs">
                                    died
                                </button>
                            </div>

                        </div><!-- /.info-box-content -->
                    </div>
                </div>
                <div v-if="kits.length === weighedKits" class="col-sm-12 text-center">
                    <h3>All Done!</h3>
                </div>

            </div>
        </form>
        <div class="alert alert-danger" v-if="errors.error">
            @{{ errors.error }}
        </div>
    </div>

    <div class="modal-footer bg-warning">
        <button data-dismiss="modal" class="btn btn-default pull-left" type="button">Close</button>
        <button v-if="kits.length == weighedKits" class="btn btn-warning" @click="saveAll" type="button">Save changes</button>
        <div v-if="kits.length != weighedKits">Weighed: @{{ weighedKits }} / @{{ kits.length }}</div>
    </div>
</template>