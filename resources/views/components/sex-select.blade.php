<template id="sex-select-template">
    <div class="icheck-group">
        <input v-el:doe type="radio" name="sex" value="doe" id="model-sex-doe" v-model="model.sex">
        <label for="model-sex-doe" class="icheck-label"> Doe</label> <br>
        <input v-el:buck type="radio" name="sex" value="buck" id="model-sex-buck" v-model="model.sex">
        <label for="model-sex-buck" class="icheck-label"> Buck</label>
    </div>
</template>