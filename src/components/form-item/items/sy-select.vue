<template>
    <el-select
        v-model="defaultValue"
        @change="handleChange"
        @clear="handleClear"
        @blur="handleBlur"
        @focus="onFocus"
        :multiple="false"
        :size="propValue.size"
        :clearable="propValue.clearable"
        :disabled="propValue.disabled"
        :placeholder="propValue.placeholder"
    >
        <el-option
            v-for="item in options"
            :label="item.label"
            :key="item.value"
            :value="item.value"
        >
        </el-option>
    </el-select>
</template>

<script>
// 列表
export default {
    props: {
        value: '',
        options: false,
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
        handleClear(){
			this.$emit('input', '');
        },
    }
}
</script>

<style lang="scss">

</style>