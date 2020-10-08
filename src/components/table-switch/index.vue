<template>
    <el-switch
        v-model="value"
        :active-text="active_text"
        :inactive-text="inactive_text"
        active-value="1"
        inactive-value="0"
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
        }
    },
    mounted(){
        this.value = this.row[this.column.field] || 0;

        if(this.column.hasOwnProperty('txt')){
            this.inactive_text = this.column.txt[0] || '';
            this.active_text = this.column.txt[1] || '';
        }
    },
    methods: {
        change(val){
            console.log(val);
        }
    }
}
</script>