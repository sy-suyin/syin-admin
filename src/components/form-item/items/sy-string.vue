<template>
    <el-input
        v-model="defaultValue"
        @blur="handleBlur"
        @focus="onFocus"
        @change="handleChange"
        @input="handleInput"
        @clear="handleClear"
        :placeholder="propValue.placeholder"
    ></el-input>
</template>

<script>
// 字符
export default {
    props: {
        value: '',
        propValue: {
            default(){
                return {
                    placeholder: '请选择',  
                }
            }
        },
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