<template>
    <div>
        <slot v-if="type == 'custom'"></slot>
        <div v-else :is="typeName" :value="value" :prop-value="propValue" @input="handleInput" :options="options" :propValue="propValue"></div>
    </div>
</template>

<script>
import syString from './items/sy-string';
import syText from './items/sy-text';
import syEditor from './items/sy-editor';
import syNumber from './items/sy-number';
import syDate from './items/sy-date';
import syTime from './items/sy-time';
import syDatetime from './items/sy-datetime';
import syDatetimerange from './items/sy-datetimerange';
import sySelect from './items/sy-select';
import sySelects from './items/sy-selects';
import syImage from './items/sy-image';
import syImages from './items/sy-images';
import syFile from './items/sy-file';
import syFiles from './items/sy-files';
import sySwitch from './items/sy-switch';
import syCheckbox from './items/sy-checkbox';
import syRadio from './items/sy-radio';
import syCity from './items/sy-city';
import syJson from './items/sy-json';

export default {
    components: {
        syString, syText, syEditor, syNumber, syDate, syTime, syDatetime, syDatetimerange, sySelect, sySelects, syImage, syImages, syFile, syFiles, sySwitch, syCheckbox, syRadio, syCity, syJson
    },
    props: {
        type: {
            type: String,
            default: 'string',
        },
        value: '',
        propValue: '',
        options: false,
    },
    mounted(){},
    methods: {
        handleInput(value){
            this.$emit('input', value);
        }
    },
    computed: {
        typeName(){
            return `sy-${this.type}`;
        }
    }
}
</script>

<style lang="scss">

</style>