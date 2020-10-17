<template>
    <el-switch
        v-model="value"
        :active-color="active_color"
        :inactive-color="inactive_color"
        :active-text="active_text"
        :inactive-text="inactive_text"
        :active-value="1"
        :inactive-value="0"
        :disabled="disabled"
        @change="change"
    >
    </el-switch>
</template>

<script>
export default {
    props: {
        row: Object,
        column: Object,
        disabled: {
            type: Boolean,
            default: false
        },
    },
    data(){
        return {
            value: 0,
            active_text: '',
            inactive_text: '',
            active_color: '',
            inactive_color: '',
        }
    },
    created(){
        this.value = this.row[this.column.prop] || 0;

        if(this.column.hasOwnProperty('txt')){
            this.inactive_text = this.column.txt[0] || '';
            this.active_text = this.column.txt[1] || '';
        }

        if(this.column.hasOwnProperty('color')){
            this.inactive_color = this.column.color[0] || '';
            this.active_color = this.column.color[1] || '';
        }
    },
    methods: {
        change(val){
            this.$emit('switch', val);
        }
    }
}
</script>