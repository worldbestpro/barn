<template id="image-upload-template">
    <div class="form-group col-sm-12">
        <label class="col-sm-2 col-xs-3 control-label">Photo</label>
        <div class="col-sm-3 col-xs-5">

            <div v-if="breeder.image.name">
                <div v-if="!breeder.image.delete">
                    <a href="#" @click.prevent="deleteImage" class="pull-right image-remove-icon"><i
                                class="fa fa-times"></i></a>
                </div>
                <img class="img-responsive img-circle profile-user-img" v-bind:alt="breeder.name"
                     v-bind:src="breeder.image.path" v-if="breeder.image.path">
            </div>
            <img @click="uploaderHelper" class="img-responsive img-circle profile-user-img" src=
            "{{ asset('media/breeders/default.jpg') }}" v-if="!breeder.image.name">

        </div>
        <div class="col-sm-7 pull-right">
            <input id="breeder-fileupload" v-el:image type="file" name="image" v-bind:alt="breeder.name">
            <img v-if="loading" src="/img/ajax-loader.gif" alt="Loading..." class="loader">
            {{--<p class="help-block">Select photo of litter</p>--}}
        </div>
    </div>
</template>