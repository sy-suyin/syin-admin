<template>
    <el-input
        v-model="defaultValue"
        type="textarea"
        @blur="handleBlur"
        @focus="onFocus"
        @change="handleChange"
        @input="handleInput"
        @clear="handleClear"
    ></el-input>
</template>

<script>
// 文本
export default {
    props: {
        value: '',
        propValue: ''
    },
    data(){
        return {
            defaultValue: this.value,
        }
    },
    methods: {
        handleBlur(event){
            this.$emit('blur', event.detail.value);
        },
        onFocus(){
			this.$emit('focus');
        },
        handleChange(event){
            this.$emit('input', event);
        },
        handleInput(event){
            this.$emit('input', event);
        },
        handleClear(){
			this.$emit('input', '');

        },
    }
}
</script>

<style lang="scss">

</style>