<template>
        <el-select
        v-model="defaultValue"
        @change="handleChange"
        @clear="handleClear"
        @blur="handleBlur"
        @focus="onFocus"
        @removeTag="handleRemoveTag"
        :multiple="true"
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
// 列表(多选)
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
        options: false
    },
    data(){
        return {
            defaultValue: this.value,
        }
    },
    mounted(){
        console.log(this.propValue);
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
        handleRemoveTag(event){
            console.log(event);
        }
    }
}
</script>

<style lang="scss">

</style>